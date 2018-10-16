<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\MessageCollection;
use App\Http\Resources\Mobile\MessageCollection as MobileMessageCollection;
use App\Models\Guest;
use App\Models\Label;
use App\Models\Message;
use Illuminate\Support\Facades\Cache;
use EasyWeChat;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class MessageRepository extends Repository
{
    /**
     * @var
     */
    protected $staff;
    /**
     * @var string
     */
    protected $cache_name;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'messages';
        $this->staff = EasyWeChat::staff();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getSysLists()
    {
        try {

            $messages = Message::recent($this->cache_name);

            $filter_messages = $messages->filter(function ($item) {
                return !$item->user_id;
            })->all();

            return response()->json(new MessageCollection(collect(array_values($filter_messages))));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLists()
    {
        try {

            $messages = Message::recent($this->cache_name);

            $filter_messages = $messages->filter(function ($item) {
                return $item->user_id;
            })->all();

            return response()->json(new MessageCollection(collect($filter_messages)));
        } catch (\Exception $e) {

            report($e);

        }

    }

    /**
     * @return MobileMessageCollection
     */
    public function get_mobile_list()
    {

        $messages = Cache::get('guest_message_list');
        if (!$messages) {
            $messages = guest_user()->messages()->latest()->paginate(8);

            Cache::tags($this->cache_name)->put('guest_message_list', $messages, 21600);
        }

        return new MobileMessageCollection($messages);
    }

    /**
     * @param $id
     * @return \App\Http\Resources\Mobile\Message
     */
    public function show($id)
    {
        $message = Message::getCache($id, $this->cache_name);

        Message::updateCache($id, ['type' => 1], $this->cache_name);//状态已读

        return new \App\Http\Resources\Mobile\Message($message);
    }

    /**
     * @param $content
     * @return \Illuminate\Http\JsonResponse
     */
    public function staff_message($content)
    {

        $guests = Guest::where('openid', config('other.staff_openid'))->get();
        if ($guests->isEmpty()) {
            return response()->json(['status' => false, 'message' => '没有客服微信账号']);
        } else {
            $staff = \EasyWeChat::staff();
            $text = new EasyWeChat\Message\Text(['content' => $content]);


            $reslut = $staff->message($text)->to(config('other.staff_openid'))->send();

            if ($reslut['errcode'] == 0) {

                /*储存群发消息*/
                if (store_template_message($guests->first()->id, '反馈建议消息', $content, null)) {

                    return response()->json(['status' => true, 'message' => '发送成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '发送失败']);
                }

            } else {

                return response()->json(['status' => false, 'message' =>$reslut['errmsg']]);

            }
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_messages($data)
    {
        try {

            $label = Label::getCache($data['label_id'], 'labels');
            if (strlen($data['content']) < 6) {
                return response()->json(['status' => false, 'message' => '消息不能少于6个字符']);
            }

            $guests = $label->guests;
            if ($guests->isEmpty()) {
                return response()->json(['status' => false, 'message' => '请选择便签']);
            }

            $send_counts = 0;
            foreach ($guests as $guest) {

                if (array_key_exists('url', $data) && array_key_exists('picture', $data)) {
                    //图文消息

                    $news = new EasyWeChat\Message\News([
                        'title'   => $data['title'],
                        'content' => $data['content'],
                        'url'     => $data['url'],
                        'image'   => $data['picture'],
                    ]);

                    $reslut = $this->staff->message($news)->to($guest->openid)->send();

                    if ($reslut['errcode'] == 0) {

                        store_template_message($guests, $data['title'], $data['content'], $label->name, auth_user()->id, $data['url'], $data['picture']);
                        /*储存群发消息*/
                        $send_counts++;
                    }else{
                        return response()->json(['status' => false, 'message' =>$reslut['errmsg'] ]);
                    }

                } else {
                    //文本消息

                    $content = strip_tags(str_replace('</div>', "\n", $data['content']), "<a>");
                    $text = new EasyWeChat\Message\Text(['content' => $data['content']]);

                    $reslut = $this->staff->message($text)->to($guest->openid)->send();

                    if ($reslut['errcode'] == 0) {

                        store_template_message($guests, $data['title'], $content, $label->name, auth_user()->id);
                        /*储存群发消息*/
                        $send_counts++;
                    }else{
                        return response()->json(['status' => false, 'message' =>$reslut['errmsg'] ]);
                    }
                }

            }

            return response()->json(['status' => true, 'message' => '发送成功' . $send_counts . '条消息']);

        } catch (\Exception $e) {

            report($e);

        }

    }


}