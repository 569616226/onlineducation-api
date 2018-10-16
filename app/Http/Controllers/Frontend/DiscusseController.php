<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Discusse;
use App\Repositories\DiscusseRepository;
use Illuminate\Http\Request;

/**
 * Class DiscusseController
 * @package App\Http\Controllers\Frontend
 */
class DiscusseController extends Controller
{

    /**
     * @var DiscusseRepository
     */
    private $repository;

    /**
     * DiscusseController constructor.
     * @param DiscusseRepository $repository
     */
    public function __construct(DiscusseRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param $lesson_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function lessonDiscusse($lesson_id)
    {

        return $this->repository->lesson_discusse($lesson_id);

    }


    /**
     * @param $lesson_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function myLessonDiscusse($lesson_id)
    {
        return $this->repository->my_lesson_discusse($lesson_id);


    }

    /**
     * 评论课程
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $lesson_id)
    {

        $content = htmlentities(addslashes($request->get('content')));

        $data = [
            'content'   => $content,
            'guest_id'  => guest_user()->id,
            'pid'       => 0,
            'is_better' => 0,
            'lesson_id' => $lesson_id,
        ];

        return $this->repository->store_guest_discusse($data);

    }


    /**
     * 删除评论
     * @param Discusse $discusse
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

        return $this->repository->delete($id);
    }


}
