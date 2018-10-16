<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\LessonListCollection;
use App\Http\Resources\Mobile\NavLessonListCollection;
use App\Http\Resources\Mobile\OrderCollection;
use App\Models\Lesson;
use App\Repositories\LessonRepository;
use Illuminate\Support\Facades\Cache;

/**
 * Class LessonController
 * @package App\Http\Controllers\Frontend
 */
class LessonController extends Controller
{
    /**
     * @var LessonRepository
     */
    private $repository;

    /**
     * LessonController constructor.
     * @param LessonRepository $repository
     */
    public function __construct(LessonRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->repository->index();
    }

    /**
     * @编辑
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        return $this->repository->edit_mobile($id);
    }

    /**
     * 栏目课程
     * @param $nav_id
     * @return NavLessonListCollection
     */
    public function navLessons($id)
    {
        return $this->repository->nav_lessons($id);
    }

    /**
     * 标签课程
     * @param $genre_id
     * @return NavLessonListCollection
     */
    public function genreLessons($nav_id, $genre_id)
    {
        return $this->repository->genre_lessons($nav_id, $genre_id);

    }

    /**
     * 收藏课程
     * @param Lesson $lesson
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect($lesson_id)
    {

        return $this->repository->collect($lesson_id);

    }

    /**
     * 点赞
     * @param Lesson $lesson
     * @return \Illuminate\Http\JsonResponse
     */
    public function like($lesson_id)
    {

        return $this->repository->like($lesson_id);

    }

    /**
     * 购买记录列表
     * @return OrderCollection
     */
    public function payOrders()
    {
        return $this->repository->pay_orders();

    }


    /**
     * @return LessonListCollection
     */
    public function learnedLessons()
    {
        return $this->repository->learned_lessons();

    }

    /**
     * 课程 模糊搜索 name
     * @return NavLessonListCollection
     */
    public function search()
    {
        $word = request('word');
        $page = request('page') ? request('page') : 1;
        $pagesize = request('pagesize') ? request('pagesize') : 8;

        return $this->repository->search($word,$page,$pagesize);

    }


    /**
     * 课程预览
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function preview($id)
    {

        return $this->repository->preview($id);

    }

}
