<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Label\AllLabelNameRequest;
use App\Http\Requests\Label\AllLabelRequest;
use App\Http\Requests\Label\CreateLabelRequest;
use App\Http\Requests\Label\DelLabelRequest;
use App\Http\Requests\Label\GetLabelRequest;
use App\Http\Requests\Label\UpdateLabelRequest;
use App\Repositories\LabelRepository;
use Illuminate\Http\Request;

/**
 * Class LabelController
 * @package App\Http\Controllers\Backend
 */
class LabelController extends Controller
{

    /**
     * @var LabelRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param LabelRepository $repository
     */
    public function __construct(LabelRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     *
     * @param AllLabelRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllLabelRequest $request)
    {

        return $this->repository->getLists();

    }


    /**
     * 创建
     * @param CreateLabelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateLabelRequest $request)
    {

        return $this->repository->store($request->all());


    }

    /**
     *    所有标签名称
     * @param AllLabelNameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function names(AllLabelNameRequest $request)
    {

        return $this->repository->names();

    }

    /**
     * 编辑
     * @param $id
     * @param GetLabelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetLabelRequest $request, $id)
    {
        return $this->repository->edit($id);

    }


    /**
     * 更新
     * @param         $id
     * @param UpdateLabelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateLabelRequest $request)
    {
        return $this->repository->update($request->all(),$id);

    }


    /**
     * 删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelLabelRequest $request, $id)
    {

        return $this->repository->delete($id);

    }
}
