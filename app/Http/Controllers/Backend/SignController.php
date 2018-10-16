<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sign\AllSignRequest;
use App\Http\Requests\Sign\DelSignRequest;
use App\Http\Requests\Sign\GetSignRequest;
use App\Http\Requests\Sign\UpdateSignRequest;
use App\Models\Sign;
use App\Repositories\SignRepository;
use QrCode;

/**
 * Class SignController
 * @package App\Http\Controllers\Backend
 */
class SignController extends Controller
{

    /**
     * @var SignRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param SignRepository $repository
     */
    public function __construct(SignRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param AllSignRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllSignRequest $request, $train_id)
    {

        return $this->repository->getLists($train_id);

    }

    /**
     * 显示数据
     * @param Sign $nav
     * @param GetSignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetSignRequest $request, $id)
    {

        return $this->repository->edit($id);

    }

    /**
     * 更新数据
     * @param Sign $nav
     * @param UpdateSignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateSignRequest $request)
    {

        $data = $request->all();
        return $this->repository->update($data, $id);

    }

    /**
     * 删除数据
     * @param $id
     * @param DelSignRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DelSignRequest $request, $id)
    {

      return  $this->repository->delete($id);

    }


}
