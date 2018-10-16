<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\AllLessonNameRequest;
use App\Http\Requests\Lesson\AllLessonRequest;
use App\Http\Requests\Lesson\AllUpLessonRequest;
use App\Http\Requests\Lesson\CreateLessonRequest;
use App\Http\Requests\Lesson\DelLessonRequest;
use App\Http\Requests\Lesson\DownLessonRequest;
use App\Http\Requests\Lesson\GetLessonRequest;
use App\Http\Requests\Lesson\GetLessonStudentsRequest;
use App\Http\Requests\Lesson\SetLessonOutLikeRequest;
use App\Http\Requests\Lesson\SetLessonOutPlayTimeRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Http\Requests\Lesson\UpLessonRequest;
use App\Http\Resources\GuestCollection;
use App\Repositories\LessonRepository;
use Illuminate\Http\Request;

/**
 * Class LessonController
 * @package App\Http\Controllers\Backend
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
     * @param AllLessonRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllLessonRequest $request)
    {

        return $this->repository->getLists();

    }

    /**
     * 课程名称
     * @param AllLessonNameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function names(AllLessonNameRequest $request)
    {

        return $this->repository->names();

    }

    /**
     * 上架课程数据（用图文和广告选择课程 ）
     * @param AllUpLessonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upLessonList(AllUpLessonRequest $request)
    {

        return $this->repository->up_lesson_list();


    }

    /**
     * 新建
     * @param CreateLessonRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateLessonRequest $request)
    {
        $data = [
            'name'           => $request->name,
            'title'          => $request->title,
            'type'           => $request->type,
            'status'         => 1,
            'pictrue'        => $request->pictrue,
            'price'          => $request->price ?? 0,
            'for'            => $request->for ?? '',
            'learning'       => $request->learning ? implode('--', $request->learning) : null,
            'describle'      => $request->describle ?? '',
            'educational_id' => $request->educational_id,
            'nav_id'         => $request->nav_id,
            'teacher_id'     => $request->teacher_id,
        ];

        $genre_ids = $request->genre_ids;

        return $this->repository->storeLesson($data, $genre_ids);


    }

    /**
     * 编辑
     * @param $id
     * @param GetLessonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetLessonRequest $request, $id)
    {

        return $this->repository->edit($id);


    }


    /**
     * @param $id
     * @param UpdateLessonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateLessonRequest $request)
    {
        $data = [
            'name'           => $request->name,
            'title'          => $request->title,
            'type'           => $request->type,
            'pictrue'        => $request->pictrue,
            'price'          => $request->price ?? 0,
            'for'            => $request->for ?? '',
            'learning'       => $request->learning ? implode('--', $request->learning) : null,
            'describle'      => $request->describle ?? '',
            'educational_id' => $request->educational_id,
            'nav_id'         => $request->nav_id,
            'teacher_id'     => $request->teacher_id,
        ];

        $genre_ids = $request->genre_ids;
        $section_ids = $request->section_ids;
        $is_frees = $request->is_frees;

        return $this->repository->updateLesson($data, $id, $genre_ids, $section_ids, $is_frees);

    }


    /**
     * @param $id
     * @param DelLessonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelLessonRequest $request, $id)
    {

        return $this->repository->delete($id);


    }


    /**
     * @param $id
     * @param UpLessonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function up(UpLessonRequest $request, $id)
    {

        return $this->repository->up_lesson($id);


    }


    /**
     * @param $id
     * @param DownLessonRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function down(DownLessonRequest $request, $id)
    {

        return $this->repository->down_lesson($id);


    }

    /**
     * 学员列表
     * @param $id
     * @param GetLessonStudentsRequest $request
     * @return GuestCollection
     */
    public function students(GetLessonStudentsRequest $request, $id)
    {

        return $this->repository->get_students($id);

    }

    /**
     * @param SetLessonOutPlayTimeRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setOutPlayTimes(SetLessonOutPlayTimeRequest $request, $id)
    {
        $data = [
            'out_play_times' => $request->out_play_times,
        ];

        return $this->repository->set_out_data($id, $data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setOutLike(SetLessonOutLikeRequest $request, $id)
    {
        $data = [
            'out_like' => $request->out_like,
        ];

        return $this->repository->set_out_data($id, $data);
    }



}
