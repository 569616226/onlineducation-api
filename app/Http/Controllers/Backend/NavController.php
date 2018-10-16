<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nav\AllNavNamesRequest;
use App\Http\Requests\Nav\AllNavRequest;
use App\Http\Requests\Nav\ChangeNavOrderRequest;
use App\Http\Requests\Nav\CreateNavRequest;
use App\Http\Requests\Nav\DelNavRequest;
use App\Http\Requests\Nav\GetNavRequest;
use App\Http\Requests\Nav\UpdateNavRequest;
use App\Models\Nav;
use App\Repositories\NavRepository;
use Illuminate\Http\Request;

/**
 * Class NavController
 * @package App\Http\Controllers\Backend
 */
class NavController extends Controller
{

    /**
     * @var NavRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param NavRepository $repository
     */
    public function __construct(NavRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param AllNavRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllNavRequest $request)
    {

        return $this->repository->getLists();

    }

    /**
     * 栏目名称
     * @param AllNavNamesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function names(AllNavNamesRequest $request)
    {
        return $this->repository->names();
    }

    /**
     * 保存标签
     * @param CreateNavRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateNavRequest $request)
    {

        return $this->repository->store($request->all());

    }

    /**
     * 显示数据
     * @param Nav $nav
     * @param GetNavRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetNavRequest $request, $id)
    {

        return $this->repository->edit($id);

    }

    /**
     * 更新数据
     * @param Nav $nav
     * @param UpdateNavRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateNavRequest $request)
    {

        $data = $request->all();

        return $this->repository->update($data, $id);

    }

    /**
     * 删除数据
     * @param DelNavRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DelNavRequest $request, $id)
    {

      return  $this->repository->delete($id);

    }

    /**
     * 栏目排序
     * @param ChangeNavOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeNavOrder(ChangeNavOrderRequest $request)
    {
        $nav_datas = $request->nav_datas;

        return $this->repository->change_nav_order($nav_datas);
    }

}
