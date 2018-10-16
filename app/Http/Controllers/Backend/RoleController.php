<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Genre\EditGenreRequest;
use App\Http\Requests\Role\AllRoleRequest;
use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\DelRoleRequest;
use App\Http\Requests\Role\EditRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

/**
 * Class RoleController
 * @package App\Http\Controllers\Backend
 */
class RoleController extends Controller
{

    /**
     * @var RoleRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param RoleRepository $repository
     */
    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(AllRoleRequest $request)
    {

        return $this->repository->getList();

    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRoleRequest $request)
    {

        return $this->repository->create();

    }


    /**
     * @param $id
     * @param EditRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditRoleRequest $request, $id)
    {
        return $this->repository->edit($id);
    }


    /**
     * @param StoreRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {

        return $this->repository->store($request->all());
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateRoleRequest $request)
    {

        return $this->repository->updateRole($id, $request->all());

    }


    /**
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelRoleRequest $request, $id)
    {

        return $this->repository->delete($id);

    }

}
