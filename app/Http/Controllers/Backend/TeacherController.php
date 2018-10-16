<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\AllTeacherNameRequest;
use App\Http\Requests\Teacher\AllTeacherRequest;
use App\Http\Requests\Teacher\CreateTeacherRequest;
use App\Http\Requests\Teacher\DelTeacherRequest;
use App\Http\Requests\Teacher\GetTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Repositories\TeacherRepository;
use Cache;
use Illuminate\Http\Request;

/**
 * Class TeacherController
 * @package App\Http\Controllers\Backend
 */
class TeacherController extends Controller
{

    /**
     * @var TeacherRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param TeacherRepository $repository
     */
    public function __construct(TeacherRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param AllTeacherRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllTeacherRequest $request)
    {
        return $this->repository->getLists();

    }

	/**
	 *	所有讲师名称
     * @param AllTeacherNameRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function names(AllTeacherNameRequest $request)
	{

	    return $this->repository->names();

	}

    /**
     *编辑
     * @param Teacher $teacher
     * @param GetTeacherRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetTeacherRequest $request, $id )
    {
        return $this->repository->edit($id);


    }

    /**
     *更新
     * @param Teacher $teacher
     * @param UpdateTeacherRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( $id, UpdateTeacherRequest $request )
    {

        return $this->repository->update($request->all(),$id);

    }

    /**
     *创建
     * @param CreateTeacherRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( CreateTeacherRequest $request )
    {
        return $this->repository->store($request->all());


    }

    /**
     *删除
     * @param $id
     * @param DelTeacherRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DelTeacherRequest $request, $id )
    {
        return $this->repository->delete($id);


    }
}
