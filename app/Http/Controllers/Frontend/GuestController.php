<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\MobileTrainListCollection;
use App\Http\Resources\Mobile\NavLessonListCollection;
use App\Models\Guest;
use App\Models\Message;
use App\Models\Sign;
use App\Models\Train;
use Carbon\Carbon;
use EasyWeChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Qcloud\Sms\SmsSingleSender;
use \Illuminate\Http\Request;

/**
 * Class GuestController
 * @package App\Http\Controllers\Frontend
 */
class GuestController extends Controller
{

    /**
     * @var mixed
     */
    protected $appid;
    /**
     * @var mixed
     */
    protected $appkey;
    /**
     * @var mixed
     */
    protected $templId;

    /**
     * GuestController constructor.
     * @param mixed $appid
     * @param mixed $appkey
     * @param mixed $templId
     */
    public function __construct()
    {
        $this->appid = env('QCLOUDSMS_APPID', null);
        $this->appkey = env('QCLOUDSMS_APPKEY', null);
        $this->templId = env('QCLOUDSMS_TEMPLID', null);
    }

    /**
     * 显示指定用户信息
     *
     * @param  Guest $guest
     * @return
     */
    public function profile()
    {
        try {

            $messages_count = guest_user()->messages()->whereType(0)->get()->count();//未读消息

            return response()->json([
                'guest'          => new \App\Http\Resources\Mobile\Guest(guest_user()),
                'messages_count' => $messages_count
            ]);
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * 发送验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSms(Request $request)
    {

        try {

            $phoneNumber = $request->phone;
            $code = $this->makeCode($phoneNumber);

            $guests = Guest::wherePhone($phoneNumber)->get();
            if(!$guests->isEmpty()){
                return response()->json(['status' => false, 'message' => '此号码已被使用']);
            }

            $sender = new SmsSingleSender($this->appid, $this->appkey);
            $params = [$code];
            // 假设模板内容为：测试短信，{1}，{2}，{3}，上学。
            $result = $sender->sendWithParam("86", $phoneNumber, $this->templId, $params, '');

            $rsp = json_decode($result, true);


            if ($rsp['result'] == 0) {
                return response()->json(['status' => true, 'message' => '发送成功']);
            } else {
                return response()->json(['status' => false, 'message' => '发送失败']);
            }

        } catch (\Exception $e) {
            report($e);

            return response()->json(['status' => false, 'message' => $e]);
        }


    }

    /**
     * 生成6为验码
     * @return string
     */
    public function makeCode($phoneNumber)
    {
        try {

            //生成6位验证码
            $chars = '0123456789';
            mt_srand((double)microtime() * 1000000 * getmypid());
            $smscode = "";

            while (strlen($smscode) < 6)
                $smscode .= substr($chars, (mt_rand() % strlen($chars)), 1);

            Cache::put('sms_code_' . $phoneNumber, $smscode, 10);

            return $smscode;
        } catch (\Exception $e) {
            report($e);
        }

    }

    /**
     * 检测验证码，并绑定手机
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTel(Request $request, $id)
    {
        try {

            $code = $request->sms_code;
            $phone = $request->phone;

            $data = [
                'phone' => $phone
            ];

            $signs = Sign::recent('signs');
            $filter_sign = $signs->filter(function ($item) use ($phone) {
                return $item->tel == $phone;
            })->first();


            /*如果有签到数据，就获取最新的签到数据保存到个人信息*/
            if ($filter_sign) {
                $filter_sign_data = array_only($filter_sign->toArray(), ['name', 'tel', 'referee', 'company', 'offer']);
                $data = array_merge($data, $filter_sign_data);
            }

            if (Cache::get('sms_code_' . $data['phone'])) {

                if (Cache::get('sms_code_' . $data['phone']) == $code) {

                    $guest = Guest::updateCache($id, $data, 'guests');

                    if ($guest) {

                        Cache::forget('sms_code_' . $data['phone']);//验证码

                        return response()->json(['status' => true, 'message' => '绑定成功']);

                    } else {

                        return response()->json(['status' => false, 'message' => '绑定失败']);
                    }

                } else {

                    return response()->json(['status' => false, 'message' => '验证码错误']);
                }

            } else {

                return response()->json(['status' => false, 'message' => '验证码已过期']);
            }

        } catch (\Exception $e) {
            report($e);
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $data = $request->all();
            $guest = Guest::updateCache($id, $data, 'guests');
            $guest_data = array_only($guest->toArray(), ['name', 'company', 'offer', 'tel', 'referee']);

            if ($guest && $data = $guest_data) {

                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                return response()->json(['status' => false, 'message' => '操作失败']);

            }

        } catch (\Exception $e) {
            report($e);
        }

    }

    /**
     * @return mixed
     */
    public function myCollectTrain()
    {

        $my_collect_trains = Cache::get('my_collect_train_' . guest_user()->id);

        if (!$my_collect_trains) {

            $trains = Train::recent('trains');

            $filter_ids = $trains->filter(function ($item) {
                return in_array(guest_user()->id, $item->collect_guest_ids ?? []);
            })->pluck('id')->toArray();

            if (count($filter_ids)) {

                $my_collect_trains = Train::whereIn('id', $filter_ids)->paginate(10);

                Cache::tags('trains')->put('my_collect_train_' . guest_user()->id, $my_collect_trains, 21600);

                return new MobileTrainListCollection($my_collect_trains);

            } else {

                return response()->json(['data' => []]);
            }

        } else {

            return new MobileTrainListCollection($my_collect_trains);

        }
    }

    /**
     * 收藏课程
     * @return NavLessonListCollection
     */
    public function collectLessons()
    {

        $lessons = guest_user()->collect_lessons()->withTrashed()->paginate(8);

        return new NavLessonListCollection($lessons);

    }


}
