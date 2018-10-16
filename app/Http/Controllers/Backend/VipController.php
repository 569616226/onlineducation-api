<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vip\AllVipNameRequest;
use App\Http\Requests\Vip\AllVipRequest;
use App\Http\Requests\Vip\CreateVipRequest;
use App\Http\Requests\Vip\DelVipRequest;
use App\Http\Requests\Vip\DownVipRequest;
use App\Http\Requests\Vip\GetVipRequest;
use App\Http\Requests\Vip\SetVipDownTimeRequest;
use App\Http\Requests\Vip\SetVipUpTimeRequest;
use App\Http\Requests\Vip\UpdateVipRequest;
use App\Http\Requests\Vip\UpVipRequest;
use App\Models\Vip;
use App\Repositories\VipRepository;
use Illuminate\Http\Request;

/**
 * Class VipController
 * @package App\Http\Controllers\Backend
 */
class VipController extends Controller
{

    /**
     * @var VipRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param VipRepository $repository
     */
    public function __construct(VipRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param AllVipRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllVipRequest $request)
    {
        return $this->repository->getLists();
    }

	/**
	 *	所有vip名称
     * @param AllVipNameRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function names(AllVipNameRequest $request)
	{
        return $this->repository->names();
	}

    /**
     *创建
     * @param CreateVipRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( CreateVipRequest $request )
    {

        return $this->repository->store($request->all());

    }

    /**
     *编辑
     * @param Vip $vip
     * @param GetVipRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetVipRequest $request, $id )
    {

        return $this->repository->edit($id);

    }

    /**
     *更新
     * @param Vip $vip
     * @param UpdateVipRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( $id, UpdateVipRequest $request )
    {

        return $this->repository->update($request->all(),$id);

    }


    /**
     *  删除
     * @param Vip $vip
     * @param DelVipRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DelVipRequest $request, $id )
    {
        return $this->repository->delete($id);
    }

    /**
     *上架
     * @param Vip $vip
     * @param UpVipRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function up(UpVipRequest $request, $id )
    {
        return $this->repository->up($id);

    }


    /**
     *下架
     * @param Vip $vip
     * @param DownVipRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function down(DownVipRequest $request, $id )
    {
        return $this->repository->down($id);

    }


}
