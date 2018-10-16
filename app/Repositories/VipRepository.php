<?php
/**
 * Created by PhpStorm.
 * Vip: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\VipCollection;
use App\Models\Vip;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class VipRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;
    /**
     * @var
     */
    protected $vip_names;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'vips';
        $this->vip_names = Vip::names($this->cache_name);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $vips = Vip::recent($this->cache_name, ['guests']);

            return response()->json(new VipCollection($vips));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_vip_mobile_list()
    {
        $vips = Vip::recent($this->cache_name, ['guests']);
        $filter_vips = $vips->filter(function($item){
                return $item->status == 1;
        })->all();

        return response()->json( new VipCollection( collect(array_values($filter_vips)) ) );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function names()
    {
        try {

            return response()->json($this->vip_names);

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(array $data)
    {
        try {

            $vip = Vip::storeCache($data, $this->cache_name);

            if ($vip) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {

            $vip = Vip::getCache($id, $this->cache_name, ['guests']);
            $vip_names = array_values(array_diff($this->vip_names,[$vip->name]));

            return response()->json([
                'vip'       => new \App\Http\Resources\Vip($vip),
                'vip_names' => $vip_names
            ]);

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param array $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(array $data, $id)
    {
        try {

            $vip = Vip::updateCache($id, $data, $this->cache_name);

            if ($vip) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            report($e);
        }

    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {

            $vip = Vip::getCache($id, $this->cache_name, ['guests']);

            if (!$vip->guests->isEmpty()) {

                return response()->json(['status' => false, 'message' => '不能删除有学员的vip'], 201);

            } else {

                $vip = Vip::deleteCache($vip->id, $this->cache_name);

                if ($vip) {
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '操作失败']);
                }
            }

        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function up($id)
    {
        try {

            $vip = Vip::updateCache($id, ['status' => 1], $this->cache_name);

            if ($vip->status == 1) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            report($e);
        }
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function down($id)
    {
        try {

            $vip = Vip::updateCache($id, ['status' => 3], $this->cache_name);

            if ($vip->status == 3) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            report($e);
        }
    }


}