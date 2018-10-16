<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\Advert as AdvertResource;
use App\Models\Advert;
use App\Models\Lesson;
use Illuminate\Http\Request;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class AdvertRepository extends Repository
{

    const LESSON_TRAIN_TYPE = 4;
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
        $this->cache_name = 'adverts';
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getLists()
    {
        try {

            $adverts = Advert::recent($this->cache_name);

            return response()->json( AdvertResource::collection($adverts));

        } catch (\Exception $e) {
            report($e);
        }
    }

    public function update(array $data, $id)
    {
        try {

            if($data['type']){
                $lesson_type = Lesson::getCache($data['url'],'lessons')->type;

                if($lesson_type == self::LESSON_TRAIN_TYPE){
                    $data['url'] = env('MOBILE_URL') . '#/InterviewDetail/' .  $data['url'];
                }else{
                    $data['url'] = env('MOBILE_URL') . '#/details/' .  $data['url'];
                }

            }

            $advert = Advert::updateCache($id, $data, $this->cache_name);


            if($advert){
                return response()->json(['status' => true, 'message' => '操作成功']);
            }else{
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

            $advert = Advert::getCache($id, $this->cache_name);

            return response()->json(new \App\Http\Resources\Advert($advert));

        } catch (\Exception $e) {
            report($e);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(array $data)
    {

        try {

            if($data['type']){
                $lesson_type = Lesson::getCache($data['url'],'lessons')->type;

                if($lesson_type == self::LESSON_TRAIN_TYPE){
                    $data['url'] = env('MOBILE_URL') . '#/InterviewDetail/' .  $data['url'];
                }else{
                    $data['url'] = env('MOBILE_URL') . '#/details/' .  $data['url'];
                }

            }

            $advert = Advert::storeCache($data, $this->cache_name);

            if($advert){
                return response()->json(['status' => true, 'message' => '操作成功']);
            }else{
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

            $advert = Advert::deleteCache($id, $this->cache_name);

            if($advert){
                return response()->json(['status' => true, 'message' => '操作成功']);
            }else{
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {
            report($e);
        }

    }


}