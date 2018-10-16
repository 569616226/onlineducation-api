<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\LessonListCollection;
use App\Http\Resources\Mobile\LessonListCollection as MobileLessonListCollection;
use App\Http\Resources\Mobile\NavCollection;
use App\Http\Resources\Mobile\NavLessonListCollection;
use App\Http\Resources\Mobile\OrderCollection;
use App\Http\Resources\StudentCollection;
use App\Listeners\SendWechatMessageNotification;
use App\Models\Genre;
use App\Models\GuestLesson;
use App\Models\Lesson;
use App\Models\Nav;
use App\Models\Section;
use App\Models\Train;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class LessonRepository extends Repository
{
    const LESSON_UP_STATUS = 3;
    const LESSON_DOWN_STATUS = 2;
    const NAV_ORDER_TYPE_1 = 1;
    const NAV_ORDER_TYPE_2 = 2;
    const SETTING_INDEX_TYPE_1 = 1;
    const SETTING_INDEX_TYPE_2 = 2;

    /**
     * @var string
     */
    protected $cache_name;
    /**
     * @var
     */
    protected $lesson_names;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'lessons';
        $this->lesson_names = Lesson::names($this->cache_name);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $lessons = Lesson::recent($this->cache_name, ['nav', 'genres']);

            $nav = Nav::recent('navs');

            return response()->json([
                'nav'     => $nav,
                'lessons' => new LessonListCollection($lessons)
            ]);

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $upLessons = change_lesson_other_data(Lesson::upLessons());
        $notStopTrains = Train::notStopTrains();

        foreach ($notStopTrains as $filter_train) {
            $filter_train->is_train = true;
        }

        if (get_settings('index_type') == self::SETTING_INDEX_TYPE_1) {//最新更新

            $index_count = get_settings('index_count');
            $lesson_merage_trains = array_merge($upLessons->toArray(), $notStopTrains->toArray());
            $last_lesson_trains = collect($lesson_merage_trains)
                ->sortByDesc('created_at')
                ->take($index_count)
                ->all();

        } elseif (get_settings('index_type') == self::SETTING_INDEX_TYPE_2) {//自定义

            $topUpLessons = change_lesson_other_data(Lesson::topUpLessons());
            $topNotStopTrains = Train::topNotStopTrains();

            foreach ($topNotStopTrains as $filter_train) {
                $filter_train['is_train'] = true;
            }

            $last_lesson_trains = array_merge($topNotStopTrains, $topUpLessons);

        }

        /*首页搜索课程活动 显示使用*/

        $lesson_merage_trains = array_merge($upLessons->pluck('name')->toArray(), $notStopTrains->pluck('name')->toArray());

        $navs = Nav::notHideNavs();

        return response()->json([
            'last_lesson_trains' => array_values($last_lesson_trains),
            'nav'                => new NavCollection($navs),
            'lesson_train_names' => $lesson_merage_trains,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function names()
    {
        try {

            return response()->json($this->lesson_names);

        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function up_lesson_list()
    {
        try {

            $lessons = Lesson::upLessons();

            return response()->json(new \App\Http\Resources\LessonListCollection($lessons));

        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLesson(array $data, array $genre_ids)
    {

        try {

            DB::beginTransaction();

            $lesson = Lesson::storeCache($data, $this->cache_name);

            if ($lesson) {

                $lesson->genres()->sync($genre_ids);

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

            $lesson = Lesson::getCache($id, $this->cache_name, ['nav', 'genres', 'teacher', 'educational', 'sections.video.video_urls']);
            $lesson_names = array_values(array_diff($this->lesson_names, [$lesson->name]));

            return response()->json([
                'lesson'       => new \App\Http\Resources\Lesson($lesson),
                'lesson_names' => $lesson_names
            ]);

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit_mobile($id)
    {
        $lesson = Lesson::with(['teacher', 'educational', 'sections'])->withTrashed()->whereId($id)->first();

        if ($lesson->deleted_at) {
            return response()->json(['status' => false, 'msg' => '该课程资源已被删除'], 201);
        } else {
            if ($lesson->status == self::LESSON_UP_STATUS) {
                return response()->json(new \App\Http\Resources\Mobile\Lesson($lesson));
            } else {
                return response()->json(['status' => false, 'msg' => '该课程已经下架'], 201);
            }
        }
    }

    /**
     * @param $nav_id
     * @return NavLessonListCollection
     */
    public function nav_lessons($nav_id)
    {
        $nav = Nav::getCache($nav_id, 'navs');

        if ($nav->order_type == self::NAV_ORDER_TYPE_1) {
            $lessons = $nav->lessons()
                ->whereStatus(self::LESSON_UP_STATUS)
                ->latest()
                ->paginate(8);
        } elseif ($nav->order_type == self::NAV_ORDER_TYPE_2) {
            $lessons = $nav->lessons()
                ->whereStatus(self::LESSON_UP_STATUS)
                ->latest('out_play_times')
                ->paginate(8);
        }

        return new NavLessonListCollection($lessons);
    }


    /**
     * @param $nav_id
     * @param $genre_id
     * @return NavLessonListCollection
     */
    public function genre_lessons($nav_id, $genre_id)
    {

        $genre = Genre::getCache($genre_id, 'genres', [$this->cache_name]);
        $lessons = $genre->lessons()
            ->where('nav_id', $nav_id)
            ->whereStatus(self::LESSON_UP_STATUS)
            ->paginate(8);

        return new NavLessonListCollection($lessons);
    }

    /**
     * @param $lesson_id
     * @param $action_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect($lesson_id)
    {
        try {

            $guest_lessons = GuestLesson::where('lesson_id', $lesson_id)->where('guest_id', guest_user()->id)->get();

            if ($guest_lessons->isEmpty()) {

                $data = [
                    'is_collect'   => true,
                    'is_pay'       => false,
                    'sections'     => false,
                    'last_section' => false,
                    'add_date'     => null,
                    'collect_date' => now(),
                    'is_like'      => false,
                ];

                guest_user()->lessons()->attach($lesson_id, $data);

                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                $guest_lesson = $guest_lessons->first();

                if ($guest_lesson->is_collect) {
                    $data = [
                        'is_collect'   => false,
                        'is_like'      => $guest_lesson->is_like,
                        'is_pay'       => $guest_lesson->is_pay,
                        'sections'     => $guest_lesson->sections,
                        'last_section' => $guest_lesson->last_section,
                        'add_date'     => $guest_lesson->add_date,
                        'collect_date' => null,
                    ];

                    guest_user()->lessons()->updateExistingPivot($lesson_id, $data);

                    return response()->json(['status' => true, 'message' => '操作成功']);

                } else {

                    $data = [
                        'is_collect'   => true,
                        'is_like'      => $guest_lesson->is_like,
                        'is_pay'       => $guest_lesson->is_pay,
                        'sections'     => $guest_lesson->sections,
                        'last_section' => $guest_lesson->last_section,
                        'add_date'     => $guest_lesson->add_date,
                        'collect_date' => now(),
                    ];

                    guest_user()->lessons()->updateExistingPivot($lesson_id, $data);

                    return response()->json(['status' => true, 'message' => '操作成功']);

                }
            }

        } catch (\Exception $e) {
            report($e);
        }


    }

    /**
     * @param $lesson_id
     * @param $action_type
     * @return \Illuminate\Http\JsonResponse
     */
    public function like($lesson_id)
    {
        try {

            $guest_lessons = GuestLesson::where('lesson_id', $lesson_id)->where('guest_id', guest_user()->id)->get();

            if ($guest_lessons->isEmpty()) {

                $data = [
                    'is_collect'   => false,
                    'is_pay'       => false,
                    'sections'     => false,
                    'last_section' => false,
                    'add_date'     => null,
                    'collect_date' => null,
                    'is_like'      => true,
                ];

                guest_user()->lessons()->attach($lesson_id, $data);

                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                $guest_lesson = $guest_lessons->first();

                if (!$guest_lesson->is_like) {

                    $data = [
                        'is_collect'   => $guest_lesson->is_collect,
                        'is_like'      => true,
                        'is_pay'       => $guest_lesson->is_pay,
                        'sections'     => $guest_lesson->sections,
                        'last_section' => $guest_lesson->last_section,
                        'add_date'     => $guest_lesson->add_date,
                        'collect_date' => $guest_lesson->collect_date,
                    ];

                    guest_user()->lessons()->updateExistingPivot($lesson_id, $data);

                    return response()->json(['status' => true, 'message' => '操作成功']);

                }
            }

        } catch (\Exception $e) {
            report($e);
        }


    }


    /**
     * @return OrderCollection
     */
    public function pay_orders()
    {
        $orders = guest_user()->orders()->whereStatus(1)->latest()->paginate(8);

        return new OrderCollection($orders);
    }


    /**
     * @return MobileLessonListCollection
     */
    public function learned_lessons()
    {

        $learned_lessons = guest_user()->learned_lessons()->withTrashed()->paginate(8);


        return new MobileLessonListCollection($learned_lessons);
    }

    /**
     * @param $word
     * @param $page
     * @param $pagesize
     * @return NavLessonListCollection
     */
    public function search($word, $page, $pagesize)
    {
        $lessons = Lesson::where('name', 'like', '%' . $word . '%')
            ->where('status', self::LESSON_UP_STATUS)
            ->latest()
            ->get()
            ->toArray();

        $trains = Train::where('name', 'like', '%' . $word . '%')
            ->whereIn('status', [0, 1])
            ->latest()
            ->get()
            ->toArray();

        foreach ($trains as $train) {
            $train['is_train'] = true;
        }

        $train_lessons = array_merge($trains, $lessons);

        $chuck_data = collect($train_lessons)->forPage($page, $pagesize)->all();

        return $chuck_data;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function preview($id)
    {
        $lesson = Lesson::with(['teacher', 'educational', 'sections'])->withTrashed()->whereId($id)->first();

        if ($lesson->deleted_at) {
            return response()->json(['status' => false, 'msg' => '该课程资源已被删除'], 201);
        } else {

            return response()->json(new \App\Http\Resources\Mobile\Lesson($lesson));

        }
    }

    /**
     * @param array $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLesson(array $data, $id, array $genre_ids, $section_ids, $is_frees)
    {
        try {

            DB::beginTransaction();

            $lesson = Lesson::updateCache($id, $data, $this->cache_name);

            if ($lesson) {

                /*更新课程章节*/

                if (is_array($section_ids) && count($section_ids)) {
                    Section::whereIn('id', $section_ids)->update(['lesson_id' => $id]);
                    clear_cache('sections');
                }

                /*更新课程免费章节*/

                if (is_array($is_frees) && count($is_frees)) {
                    Section::whereIn('id', $is_frees)->update(['is_free' => 1]);
                    clear_cache('sections');
                }

                if (is_array($genre_ids) && count($genre_ids)) {
                    $lesson->genres()->sync($genre_ids);
                }

                \App::make(\Illuminate\Contracts\Bus\Dispatcher::class)
                    ->dispatch(new SendWechatMessageNotification($lesson));

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

            DB::beginTransaction();

            $lesson = Lesson::getCache($id, $this->cache_name, ['nav', 'genres', 'teacher', 'educational', 'sections.video.video_urls']);

            if (in_array($id, $lesson->nav->nav_lesson_ids ?? [])) {

                DB::rollback();
                return response()->json(['status' => false, 'message' => '不能删除推荐课程'], 201);

            } else {

                if ($lesson->status == self::LESSON_UP_STATUS) {

                    DB::rollback();
                    return response()->json(['status' => false, 'message' => '不能删除上架中课程'], 201);

                } else {

                    $lesson->genres()->detach();
                    $lesson->sections()->delete();

                    $is_del = Lesson::deleteCache($id, $this->cache_name);
                    if ($is_del) {

                        DB::commit();
                        return response()->json(['status' => true, 'message' => '操作成功']);
                    } else {

                        DB::rollback();
                        return response()->json(['status' => false, 'message' => '操作失败']);
                    }

                }
            }

        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function up_lesson($id)
    {
        try {

            $lesson = Lesson::getCache($id,$this->cache_name);
            if($lesson->sections->isEmpty()){
                return response()->json(['status' => false, 'message' => '不能上架没有章节的课程']);
            }else{

                $up_lesson = Lesson::updateCache($id, ['status' => self::LESSON_UP_STATUS], $this->cache_name);

                if ($up_lesson->status == self::LESSON_UP_STATUS) {
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '操作失败']);

                }
            }

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function down_lesson($id)
    {
        try {

            $lesson = Lesson::getCache($id, $this->cache_name, ['nav', 'genres', 'teacher', 'educational', 'sections.video.video_urls']);

            if (!in_array($id, $lesson->nav->nav_lesson_ids ?? [])) {

                $lesson = Lesson::updateCache($id, ['status' => self::LESSON_DOWN_STATUS], $this->cache_name);

                if ($lesson) {
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '操作失败']);
                }

            } else {
                return response()->json(['status' => false, 'message' => '不能下架推荐课程'], 201);
            }

        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get_students($id)
    {
        try {

            $lesson = Lesson::getCache($id, $this->cache_name, ['students']);
            $students = $lesson->students()->get();
            $lesson_sections = $lesson->sections()->withTrashed()->get()->count();

            foreach ($students as $student) {
                $student->learned_per = number_format(count($student->pivot->sections) / $lesson_sections * 100, 2) . '%';//学习进度
            }

            return new StudentCollection($students);

        } catch (\Exception $e) {

            report($e);
        }
    }


    /**
     * @param $id
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function set_out_data($id, array $data)
    {
        try {

            $lesson = Lesson::updateCache($id, $data, $this->cache_name);

            if ($lesson) {

                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            report($e);
        }
    }


}