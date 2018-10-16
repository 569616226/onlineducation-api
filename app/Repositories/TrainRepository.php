<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\Mobile\MobileTrainList;
use App\Http\Resources\Mobile\MobileTrainListCollection;
use App\Http\Resources\TrainList;
use App\Http\Resources\TrainListCollection;
use App\Models\Sign;
use App\Models\Train;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class TrainRepository extends Repository
{

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
        $this->cache_name = 'trains';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $trains = Train::recent($this->cache_name);

            return response()->json(new TrainListCollection($trains));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTrain(array $data, array $genre_ids)
    {

        try {

            DB::beginTransaction();

            $train = Train::storeCache($data, $this->cache_name);

            if ($train) {

                $train->genres()->attach($genre_ids);

                $qrcode_url = '/qrcodes/' . $train->id . '/';

                if (!is_file(public_path($qrcode_url . 'qrcode.png'))) {

                    if (!is_dir(public_path($qrcode_url))) {
                        mkdir(public_path($qrcode_url), 0777, true);
                    }

                    $content_url = config('other.mobile_url') . '#/ConferenceSign/' . $train->id;
                    \QrCode::format('png')->size(400)->encoding('UTF-8')
                        ->generate($content_url, public_path($qrcode_url . 'qrcode.png'));
                }

                $url = asset($qrcode_url . 'qrcode.png');
                $train->update(['qr_code' => $url]);

                DB::commit();
                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                DB::rollBack();
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            DB::rollBack();
            report($e);

        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {

            $train = Train::getCache($id, $this->cache_name, ['signs', 'nav', 'genres']);

            return response()->json([
                'train' => new TrainList($train)
            ]);

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_mobile_edit($id)
    {
        try {

            $train = Train::getCache($id, $this->cache_name, ['signs', 'nav', 'genres']);

            return response()->json([
                'train' => new MobileTrainList($train)
            ]);

        } catch (\Exception $e) {

            report($e);
        }

    }


    /**
     * @param array $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTrain(array $data, $id, array $genre_ids)
    {
        try {

            DB::beginTransaction();

            $data['start_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $data['start_at'])->timestamp;
            $data['end_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $data['end_at'])->timestamp;

            $train = Train::updateCache($id, $data, $this->cache_name);

            if ($train) {

                $train->genres()->sync($genre_ids);

                DB::commit();
                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                DB::rollBack();
                return response()->json(['status' => false, 'message' => '操作失败']);
            }


        } catch (\Exception $e) {

            DB::rollBack();
            report($e);
        }

    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {

            $train = Train::getCache($id, $this->cache_name, ['signs', 'nav', 'genres']);

            if (!$train->status) {
                $is_del = Train::deleteCache($id, $this->cache_name);
                if ($is_del) {
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '操作失败']);
                }
            } else {
                $message = $train->status == 1 ? '不能删除进行中的活动' : '不能删除已经结束的活动';
                return response()->json(['status' => false, 'message' => $message], 201);
            }


        } catch (\Exception $e) {

            report($e);
        }
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download_qrcode($id)
    {
        try {

            $train = Train::getCache($id, 'trains');

            $pathToFile = public_path('/qrcodes/' . $id . '/qrcode.png');

            return response()->download($pathToFile, $train->name . '.png');

        } catch (\Exception $exception) {
            report($exception);
        }
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download_template($pathToFile)
    {
        try {
            return response()->download($pathToFile, '签到导入模板' . '.xls');
        } catch (\Exception $exception) {
            report($exception);
        }
    }

    /**
     * @param $nav_id
     */
    public function nav_trains($nav_id)
    {

        $trains = Cache::get('guest_nav_train_list');
        if (!$trains) {
            $trains = Train::whereIn('status', [0, 1])->latest()->where('nav_id', $nav_id)->paginate(8);

            Cache::tags($this->cache_name)->put('guest_nav_train_list', $trains, 21600);
        }

        return new MobileTrainListCollection($trains);
    }

    /**
     * @param $nav_id
     * @param $genre_id
     */
    public function genre_trains($nav_id, $genre_id)
    {
        $trains = Cache::get('guest_genre_train_list');
        if (!$trains) {
            $trains = \App\Models\Genre::getCache($genre_id, 'genres', [$this->cache_name])
                ->trains()
                ->whereIn('status', [0, 1])
                ->where('nav_id', $nav_id)
                ->paginate(8);

            Cache::tags($this->cache_name)->put('guest_genre_train_list', $trains, 21600);
        }

        return new MobileTrainListCollection($trains);
    }


    /**
     * @param $excelfile
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function import_trains($excelfile, $id)
    {

        $file_name = $excelfile->getClientOriginalName();
        $realPath = $excelfile->getRealPath();
        $ext = substr($file_name, -3, 3);
        $status = true;
        $msg = '导入成功';

        if ($ext === 'xls' || $ext === 'csv') {

            if ($excelfile) {

                $filename = rtrim($file_name, '.' . $ext) . '-' . uniqid() . '.' . $ext;
                $bool = \Illuminate\Support\Facades\Storage::disk('trains')->put($filename, file_get_contents($realPath));
                if ($bool) {

                    $path = storage_path('app/public/uploads/trains/' . $filename);

                } else {

                    return response()->json(['status' => false, 'msg' => '简历导入失败']);
                }


                Excel::load($path, function ($reader) use ($id, &$status, &$msg) {

                    $head = [
                        '姓名'  => 'name',
                        '电话'  => 'tel',
                        '推荐人' => 'referee',
                        '公司名' => 'company',
                        '职位'  => 'offer',
                    ];

                    $results = $reader->toArray()[0];/*获取所有数据*/

                    if (!count($results)) {

                        $msg = '模板数据为空';
                        $status = false;

                    } else {

                        if (!array_keys($results[0]) === array_keys($head)) {

                            $msg = '模板不对';
                            $status = false;

                        } else {

//                            $datas = [];

                            foreach ($results as $result) {

                                foreach ($result as $key => $value) {
                                    $data[$head[$key]] = $value;
                                }

                                $data['train_id'] = $id;

                                $signs = Train::find($id)->signs()->whereTel($data['tel'])->get();

                                if ($signs->isEmpty()) {

//                                    \Log::info($data);

                                    $sign = Sign::storeCache($data,'signs');

                                    if(!$sign){

                                        continue;

                                    }

                                }else{

                                    continue;
                                }

//                                array_push($datas, $data);
                            }

//                            DB::table('signs')->insert($datas);
                        }

                    }

                });

            } else {

                $msg = '请选择文件';
                $status = false;
            }

        } else {

            $msg = '导入错误格式文件';
            $status = false;

        }

        return response()->json(['status' => $status, 'message' => $msg]);
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect_train($id)
    {
        $train = Train::getCache($id, $this->cache_name);
        $collect_guest_ids = $train->collect_guest_ids ?? [];

        if (in_array(guest_user()->id, $collect_guest_ids)) {

            return response()->json(['status' => true, 'message' => '收藏成功']);

        } else {

            array_push($collect_guest_ids, guest_user()->id);

            $data = [
                'collect_guest_ids' => array_unique($collect_guest_ids)
            ];

            $update_train = Train::updateCache($id, $data, $this->cache_name);


            if (in_array(guest_user()->id, $update_train->collect_guest_ids)) {

                return response()->json(['status' => true, 'message' => '收藏成功']);

            } else {

                return response()->json(['status' => false, 'message' => '取消收藏']);
            }
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function uncollect_train($id)
    {
        $train = Train::getCache($id, $this->cache_name);
        $collect_guest_ids = $train->collect_guest_ids ?? [];

        if (in_array(guest_user()->id, $collect_guest_ids)) {

            $filter_collect_guest_ids = collect($collect_guest_ids)->filter(function ($item) {
                return !$item == guest_user()->id;
            })->toArray();

            $data = [
                'collect_guest_ids' => $filter_collect_guest_ids
            ];

            $update_train = Train::updateCache($id, $data, $this->cache_name);

            if (!in_array(guest_user()->id, $update_train->collect_guest_ids)) {

                return response()->json(['status' => true, 'message' => '取消成功']);

            } else {

                return response()->json(['status' => false, 'message' => '取消失败']);
            }

        } else {
            return response()->json(['status' => true, 'message' => '取消成功']);
        }

    }

}