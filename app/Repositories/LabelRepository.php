<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\Label as LabelResources;
use App\Http\Resources\LabelCollection;
use App\Models\Educational;
use App\Models\Label;
use Illuminate\Support\Facades\Cache;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class LabelRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;
    protected $label_names;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'labels';
        $this->label_names = Label::names($this->cache_name);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $labels = Label::recent($this->cache_name);

            return response()->json(new LabelCollection($labels));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function names()
    {
        try {

            return response()->json($this->label_names);

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

            $label = Label::storeCache($data, $this->cache_name);

            if ($label) {
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

            $label = Label::getCache($id, $this->cache_name, ['guests']);
            $label_names =  array_values(array_diff($this->label_names,[$label->name]));

            return response()->json([
                'label'       => new LabelResources($label),
                'label_names' => $label_names
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

            $label = Label::updateCache($id, $data, 'labels');

            if ($label) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }


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

            $label = Label::getCache($id, $this->cache_name, ['guests']);

            if (!$label->guests->isEmpty()) {

                return response()->json(['status' => false, 'message' => '不能删除有学员的标签'], 201);

            } else {

                $is_del = Label::deleteCache($label->id, 'labels', 'guests');

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