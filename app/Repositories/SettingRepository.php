<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Models\Lesson;
use App\Models\Setting;
use App\Models\Train;
use Illuminate\Support\Facades\DB;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class SettingRepository extends Repository
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
        $this->cache_name = 'settings';
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_index_type($id)
    {

        $lessons_not_top = Lesson::notTopUpLessons();
        $lessons_top = Lesson::topUpLessons();

        $not_top_trains = Train::notTopNotStopTrains();
        $top_trains = Train::topNotStopTrains();

        $index_type = [
            'index_type'  => $this->getSetting($id)->index_type,
            'index_count' => $this->getSetting($id)->index_count,
            'top_lessons' => array_values($lessons_top),
            'lessons'     => array_values($lessons_not_top),
            'top_trains'  => array_values($top_trains),
            'trains'      => array_values($not_top_trains),
        ];

        return response()->json($index_type);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_vip_send_time($id)
    {

        $index_type = [
            'vip_send_seting' => $this->getSetting($id)->vip_send_seting,
        ];

        return response()->json($index_type);
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_wechat_sub($id)
    {

        $index_type = [
            'wechat_sub' => html_entity_decode(stripslashes($this->getSetting($id)->wechat_sub)),
        ];

        return response()->json($index_type);
    }


    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(array $data, $id)
    {

        try {

            $setting = Setting::updateCache($id, $data, $this->cache_name);

            if ($setting) {

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
    public function signStartTime($id)
    {
        try {

            $setting = Setting::getCache($id, $this->cache_name);
            return response()->json([
                'id'              => $setting->id,
                'sign_start_time' => $setting->sign_start_time,
            ]);

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param $id
     * @return mixed
     */
    protected function getSetting($id)
    {
        return Setting::getCache($id, 'settings');
    }


}