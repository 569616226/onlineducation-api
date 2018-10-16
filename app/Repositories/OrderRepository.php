<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderList;
use App\Http\Resources\OrderListCollection;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class OrderRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'orders';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $orders = Order::recent($this->cache_name, ['guest']);

            return response()->json(new OrderCollection($orders));

        } catch (\Exception $e) {

            report($e);

        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function vip_order_list()
    {
        try {

            $orders = Cache::get('vip_order_list');
            if (!$orders) {
                $orders = Order::with(['guest'])->whereStatus(1)->latest()->whereType(2)->get();
                Cache::tags($this->cache_name)->put('vip_order_list', $orders, 21600);
            }

            return response()->json(new OrderCollection($orders));
        } catch (\Exception $e) {

            report($e);

        }
    }


}