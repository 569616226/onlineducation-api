<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discusse\AllDiscusseRequest;
use App\Http\Requests\Discusse\DelDiscusseRequest;
use App\Http\Requests\Discusse\NusetBetterDiscusseRequest;
use App\Http\Requests\Discusse\SetBetterDiscusseRequest;
use App\Http\Requests\Discusse\StoreDiscusseRequest;
use App\Models\Discusse;
use App\Repositories\DiscusseRepository;

class DiscusseController extends Controller
{

    private $repository;

    public function __construct(DiscusseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * 返回有评论的课程
     * @param AllDiscusseRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function lists(AllDiscusseRequest $request)
    {

        return $this->repository->getLists();

    }

    /**
     * 精选
     * @param Discusse $discusse
     * @param SetBetterDiscusseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function better(SetBetterDiscusseRequest $request, $id)
    {
        return $this->repository->setBetter($id);

    }

    /**
     * 取消精选
     * @param Discusse $discusse
     * @param NusetBetterDiscusseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unBetter(NusetBetterDiscusseRequest $request, $id)
    {

        return $this->repository->unsetBetter($id);

    }

    /**
     * 回复评论
     * @param StoreDiscusseRequest $request
     * @param         $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDiscusseRequest $request, $id)
    {

        $content = htmlentities(addslashes($request->get('content')));

        $data = [
            'content'  => $content,
            'guest_id' => auth_user()->id,
            'pid'      => $id,

        ];

        return $this->repository->store($data, $id);

    }

    /**
     * 删除
     * @param Discusse $discusse
     * @param DelDiscusseRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DelDiscusseRequest $request, $id)
    {
        return $this->repository->delete($id);

    }

}
