<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Section\CreateSectionRequest;
use App\Http\Requests\Section\DelSectionRequest;
use App\Http\Requests\Section\UpdateSectionRequest;
use App\Models\Lesson;
use App\Models\Section;
use App\Repositories\SectionRepository;
use Illuminate\Http\Request;

/**
 * Class SectionController
 * @package App\Http\Controllers\Backend
 */
class SectionController extends Controller
{

    /**
     * @var SectionRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param SectionRepository $repository
     */
    public function __construct(SectionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Lesson $lesson
     * @param CreateSectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Lesson $lesson, CreateSectionRequest $request)
    {
        $data = [
            'name'      => $request->name,
            'is_free'   => 0,//默认不免费
            'video_id'  => $request->video_id,
            'lesson_id' => $lesson->id,
        ];


        return $this->repository->storeSection($data);

    }

    /**
     * @param Section $section
     * @param UpdateSectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Section $section, UpdateSectionRequest $request)
    {

        return $this->repository->updateSection($request->all(), $section->id);

    }

    /**
     * @param $id
     * @param DelSectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DelSectionRequest $request, $id)
    {

        return $this->repository->delete($id);

    }


}
