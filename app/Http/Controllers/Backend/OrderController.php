<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\AllOrderRequest;
use App\Http\Requests\Order\AllVipOrderRequest;
use App\Http\Requests\Order\DelOrderRequest;
use App\Http\Requests\Order\GetOrderRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;

/**
 * Class OrderController
 * @package App\Http\Controllers\Backend
 */
class OrderController extends Controller
{

    /**
     * @var OrderRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AllOrderRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllOrderRequest $request)
    {

        return $this->repository->getLists();

    }

    /**
     * @param AllVipOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vipOrderList(AllVipOrderRequest $request)
    {

        return $this->repository->vip_order_list();

    }

}
