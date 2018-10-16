<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\NavListCollection;
use App\Models\Nav;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class NavRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;
    /**
     * @var
     */
    protected $nav_names;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'navs';
        $this->nav_names = Nav::names($this->cache_name);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $navs = Cache::get('nav_list');

            if (!$navs) {

                $navs = Nav::oldest('ordered')->get();

                Cache::tags($this->cache_name)->put('nav_list', $navs, 21600);
            }

            return response()->json(new NavListCollection($navs));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_mobile_list()
    {

        $navs = Nav::recent($this->cache_name);

        $filter_navs = $navs->filter(function ($item) {
            return !$item->is_hide;
        })->sortBy('ordered')
            ->values()
            ->all();

        return response()->json(collect($filter_navs));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function names()
    {

        return response()->json($this->nav_names);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($data)
    {
        try {

            $navs = Nav::recent($this->cache_name);
            $data['ordered'] = $navs->count() + 1;

            $nav = Nav::storeCache($data, $this->cache_name);

            if ($nav) {
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

            $nav = Nav::getCache($id, $this->cache_name, ['lessons', 'trains']);
            $nav_names = array_values(array_diff($this->nav_names, [$nav->name]));

            return response()->json([
                'nav'       => new \App\Http\Resources\NavList($nav),
                'nav_names' => $nav_names
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

            $nav = Nav::updateCache($id, $data, $this->cache_name);

            if ($nav) {

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

            $nav = Nav::getCache($id, $this->cache_name, ['lessons']);

            if (!$nav->lessons->count()) {
                $is_del = Nav::deleteCache($id, $this->cache_name);
                if ($is_del) {
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '操作失败']);
                }
            } else {
                return response()->json(['status' => false, 'message' => '不能删除有课程的栏目'], 201);
            }


        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @param array $nav_datas
     * @return \Illuminate\Http\JsonResponse
     */
    public function change_nav_order(array $nav_datas)
    {

        try {

            DB::beginTransaction();

            $nav_first_ordered = ['ordered' =>$nav_datas[0]['ordered']];
            $nav_second_ordered = ['ordered' => $nav_datas[1]['ordered']];

            $nav_first_update = Nav::updateCache($nav_datas[0]['id'],$nav_first_ordered, $this->cache_name);

            if (!$nav_first_update) {

                DB::rollBack();

                return response()->json(['status' => false, 'message' => '操作失败']);
            }

            $nav_second_update = Nav::updateCache($nav_datas[1]['id'],$nav_second_ordered, $this->cache_name);

            if (!$nav_second_update) {

                DB::rollBack();

                return response()->json(['status' => false, 'message' => '操作失败']);
            }

            DB::commit();

            return response()->json(['status' => true, 'message' => '操作成功']);

        } catch (\Exception $e) {

            DB::rollBack();
            report($e);
        }
    }

}