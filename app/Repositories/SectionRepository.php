<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Listeners\SendWechatMessageNotification;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class SectionRepository extends Repository
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
        $this->cache_name = 'sections';
    }


    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSection(array $data)
    {

        try {

            $section = Section::storeCache($data, $this->cache_name);

            if ($section) {

                \App::make(\Illuminate\Contracts\Bus\Dispatcher::class)
                    ->dispatch(new SendWechatMessageNotification($section->lesson));

                Cache::forget('video_success_list');
                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param array $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSection(array $data, $id)
    {
        try {

            $section = Section::updateCache($id, $data, $this->cache_name);

            if ($section) {

                \App::make(\Illuminate\Contracts\Bus\Dispatcher::class)
                    ->dispatch(new SendWechatMessageNotification($section->lesson));

                Cache::forget('video_success_list');

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

            $is_del = Section::deleteCache($id, $this->cache_name);

            if ($is_del) {

                $section_del = Section::withTrashed()->find($id);

                \App::make(\Illuminate\Contracts\Bus\Dispatcher::class)
                    ->dispatch(new SendWechatMessageNotification($section_del->lesson));

                return response()->json(['status' => true, 'message' => '操作成功']);


            } else {

                return response()->json(['status' => false, 'message' => '操作失败']);

            }

        } catch (\Exception $e) {

            report($e);
        }
    }


}