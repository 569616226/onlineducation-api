<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Advert\AllAdvertsRequest;
use App\Http\Requests\Advert\CreateAdvertRequest;
use App\Http\Requests\Advert\DelAdvertRequest;
use App\Http\Requests\Advert\EditAdvertRequest;
use App\Http\Requests\Advert\UpdateAdvertRequest;
use App\Repositories\AdvertRepository;

/**
 * Class AdvertController
 * @package App\Http\Controllers\Backend
 */
class AdvertController extends Controller
{

    /**
     * @var AdvertRepository
     */
    private $repository;

    /**
     * AdvertController constructor.
     * @param AdvertRepository $repository
     */
    public function __construct(AdvertRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AllAdvertsRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function lists(AllAdvertsRequest $request)
    {
       return $this->repository->getLists();
    }

    /**
     * 编辑数据
     * @param $id
     * @param EditAdvertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditAdvertRequest $request,$id)
    {
        return $this->repository->edit($id);

    }

    /**
     * 更新
     * @param $id
     * @param UpdateAdvertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAdvertRequest $request, $id)
    {

        return $this->repository->update($request->all(),$id);

    }

    /**
     * 保存
     * @param CreateAdvertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAdvertRequest $request)
    {

        return $this->repository->store($request->all());

    }

    /**
     * @test
     * @param $id
     * * @param DelAdvertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelAdvertRequest $request,$id)
    {
        return $this->repository->delete($id);

    }
}
