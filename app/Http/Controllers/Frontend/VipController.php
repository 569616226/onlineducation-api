<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\VipCollection;
use App\Models\Vip;
use App\Repositories\VipRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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
     *列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        return $this->repository->get_vip_mobile_list();

    }

}
