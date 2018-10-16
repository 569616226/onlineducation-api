<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\Educational as EducationalResources;
use App\Http\Resources\EducationalCollection;
use App\Models\Educational;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class EducationRepository extends Repository
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
        $this->cache_name = 'educationals';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $educationals = Educational::recent($this->cache_name);

            return response()->json(new EducationalCollection($educationals));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($data)
    {
        try {

            $edu = Educational::storeCache($data, $this->cache_name);

            if ($edu) {
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

            $educational = new EducationalResources(Educational::getCache($id, $this->cache_name));
            return response()->json($educational);

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

            Educational::updateCache($id, $data, $this->cache_name);

            return response()->json(['status' => true, 'message' => '操作成功']);

        } catch (\Exception $e) {

            report($e);
        }

    }


    /**
     * @param Educational $educational
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {

            $educational = Educational::getCache($id, $this->cache_name);

            if ($educational->lessons) {

                return response()->json(['status' => false, 'message' => '不能删除有课程的教务模版'], 201);

            } else {

                $is_del =  Educational::deleteCache($id, $this->cache_name);

                if ($is_del) {
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '操作失败']);
                }
            }

        } catch (\Exception $e) {

            report($e);
        }
    }

}