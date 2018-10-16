<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\Discusse as LessonDiscusse;
use App\Http\Resources\Mobile\Discusse as MobileDiscusse;
use App\Listeners\SendDiscussesBetterMessageNotification;
use App\Listeners\SendTeacherReturnMessageNotification;
use App\Models\Discusse;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class DiscusseRepository extends Repository
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
        $this->cache_name = 'discusses';
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getLists()
    {
        try {

            $lessons = Cache::get('lesson_has_discusses');

            if (!$lessons) {
                $lessons = Lesson::with(['discusses.guest'])->has('discusses')->latest()->get();
                Cache::tags($this->cache_name)->put('lesson_has_discusses', $lessons, 21600);
            }

            return LessonDiscusse::collection($lessons);

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setBetter($id)
    {
        try {

            \DB::beginTransaction();

            $discusse = Discusse::updateCache($id, ['is_better' => 1], $this->cache_name);

            \App::make(\Illuminate\Contracts\Bus\Dispatcher::class)
                ->dispatch(new SendDiscussesBetterMessageNotification($discusse));

            \DB::commit();
            return response()->json([
                'status'  => true,
                'message' => '操作成功'
            ]);

        } catch (\Exception $e) {

            \DB::rollback();
            report($e);

        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsetBetter($id)
    {
        try {

            $discusse = Discusse::updateCache($id, ['is_better' => 0], $this->cache_name);

            if ($discusse->is_better) {
                return response()->json([
                    'status'  => false,
                    'message' => '操作失败'
                ]);
            } else {
                return response()->json([
                    'status'  => true,
                    'message' => '操作成功'
                ]);
            }

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($data, $id)
    {
        try {

            \DB::beginTransaction();

            $discusse = Discusse::getCache($id, $this->cache_name);
            $data['lesson_id'] = $discusse->lesson_id;
            $update_discusse = Discusse::storeCache($data, $this->cache_name);

            if ($update_discusse) {

                \App::make(\Illuminate\Contracts\Bus\Dispatcher::class)
                    ->dispatch(new SendTeacherReturnMessageNotification($discusse));

                \DB::commit();
                return response()->json(['status' => true, 'message' => '回复成功']);

            } else {

                \DB::rollback();
                return response()->json(['status' => false, 'message' => '回复失败']);

            }

        } catch (\Exception $e) {

            \DB::rollback();
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

            $is_del = Discusse::deleteCache($id, $this->cache_name);

            if ($is_del) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            report($e);
        }
    }


    /**
     * @param $lesson_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function lesson_discusse($lesson_id)
    {
        try {

            $lesson = Lesson::getCache($lesson_id, 'lessons', ['discusses.guest']);

            $discusses = $lesson->better_discusses()->paginate(8);

            return MobileDiscusse::collection($discusses);

        } catch (\Exception $e) {

            report($e);
        }
    }


    /**
     * @param $lesson_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function my_lesson_discusse($lesson_id)
    {
        try {
            $lesson = Lesson::getCache($lesson_id, 'lessons', ['discusses.guest']);

            $discusses = $lesson->discusses()->where('guest_id', guest_user()->id)->paginate(8);

            return MobileDiscusse::collection($discusses);

        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function store_guest_discusse(array $data)
    {
        try {

            $discusse = Discusse::storeCache($data, $this->cache_name);//发送成功

            if ($discusse) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'word' => '', 'message' => '操作失败']);
            }

        } catch (\Exception $e) {
            report($e);

        }
    }

}