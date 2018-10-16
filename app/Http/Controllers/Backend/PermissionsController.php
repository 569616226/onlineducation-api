<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\AllPermissionRequest;
use App\Http\Requests\Permission\CreatePermissionRequest;
use App\Http\Requests\Permission\DelPermissionRequest;
use App\Http\Requests\Permission\GetPermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;

/**
 * Class PermissionsController
 * @package App\Http\Controllers\Backend
 */
class PermissionsController extends Controller
{

    /**
     * @var PermissionRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param PermissionRepository $repository
     */
    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @param GetPermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetPermissionRequest $request, $id )
    {
        return $this->repository->edit($id);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function allPermissions(AllPermissionRequest $request )
    {
        return $this->repository->getAllPermissions();
    }


    /**
     * @param CreatePermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreatePermissionRequest $request )
    {
        return $this->repository->store($request->all());
    }


    /**
     * @param $id
     * @param UpdatePermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdatePermissionRequest $request )
    {
        return $this->repository->update($id,$request->all());
    }


    /**
     * @param $id
     * @param DelPermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelPermissionRequest $request, $id )
    {
        return $this->repository->delete($id);
    }
}
