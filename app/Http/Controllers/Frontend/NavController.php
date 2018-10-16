<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Nav;
use App\Repositories\NavRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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
     * 标签列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {

        return $this->repository->get_mobile_list();

    }

}
