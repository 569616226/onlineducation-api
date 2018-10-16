<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\SignList;
use App\Http\Resources\SignListCollection;
use App\Models\Sign;
use App\Models\Train;


/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class SignRepository extends Repository
{

    /**
     *活动结束状态
     */
    const TRAIN_STOP_STATUS = 2;
    /**
     *活动签到状态
     */
    const SIGNED_STATUS = 1;
    const INSER_TYPE_2 = 2;
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
        $this->cache_name = 'signs';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists($train_id)
    {
        try {

            $signs = Sign::recent($this->cache_name);
            $filter_signs = $signs->filter(function ($item) use ($train_id) {
                return $item->train_id == $train_id;
            })->all();

            return response()->json(new SignListCollection(collect($filter_signs)));

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

            $sign = Sign::getCache($id, $this->cache_name, ['train']);

            return response()->json([
                'sign' => new SignList($sign)
            ]);

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

            $train = Train::getCache($data['train_id'], 'trains');
            if ($train->status == self::TRAIN_STOP_STATUS) {
                return response()->json(['status' => false, 'message' => '此活动已经结束']);
            }

            $sign = Sign::where('train_id', $data['train_id'])->whereTel($data['tel'])->get()->isEmpty();

            if ($sign) {

                $new_sign = Sign::storeCache($data, $this->cache_name, ['train']);

                if ($new_sign) {

                    return response()->json(['status' => true, 'message' => '操作成功']);

                } else {

                    return response()->json(['status' => false, 'message' => '操作失败']);
                }

            } else {

                return response()->json(['status' => false, 'message' => '您已经报名此活动，无需重复报名']);

            }


        } catch (\Exception $e) {

            report($e);
        }
    }

    /**
     * @param $tel
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_sign_by_tel($tel, $train_id)
    {
        $signs = Sign::recent($this->cache_name);

        $filter_signs = array_values($signs->filter(function ($item) use ($tel) {
            return $item->tel == $tel;
        })->all());

        if (count($filter_signs)) {

            $is_submit = in_array($train_id, array_pluck($filter_signs, 'train_id'));

            return response()->json([
                'data' => new \App\Http\Resources\Mobile\Sign($filter_signs[0]),
                'is_submit' => $is_submit
            ]);

        } else {

            return response()->json([
                'data' => null,
                'is_submit' => false
            ]);

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

            $sign = Sign::updateCache($id, $data, $this->cache_name);

            if ($sign) {

                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {


            report($e);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function guest_signed($train_id, $data)
    {
        try {

            $train = Train::getCache($train_id, 'trains');
            if ($train->status == self::TRAIN_STOP_STATUS) {
                return response()->json(['status' => false, 'message' => '此活动已经结束']);
            }

            /*签到开始时间*/
            $sign_begin_time_setting = get_settings('sign_start_time') * 60;
            $sign_begin_time = $train->start_at->timestamp - $sign_begin_time_setting;
            if (now()->timestamp <= $sign_begin_time) {

                return response()->json(['status' => false, 'message' => '未到活动签到时间']);

            }

            $sign = Sign::whereTel($data['tel'])->where('train_id', $train_id)->first();

            if (!$sign) {
                $data['train_id'] = $train_id;
                $data['status'] = self::SIGNED_STATUS;
                $data['inser_type'] = self::INSER_TYPE_2;
                $update_sign = Sign::storeCache($data, $this->cache_name);
            } else {

                if ($sign->status == self::SIGNED_STATUS) {
                    return response()->json(['status' => false, 'message' => '您已经签到此活动，无需重复签到']);
                }

                $update_sign = Sign::updateCache($sign->id, ['status' => self::SIGNED_STATUS], $this->cache_name);

            }

            if ($update_sign && $update_sign->status == self::SIGNED_STATUS) {

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

            $sign = Sign::getCache($id, $this->cache_name, ['train']);

            /*签到开始时间*/
            $sign_begin_time_setting = get_settings('sign_start_time') * 60;
            $sign_begin_time = $sign->train->start_at->timestamp - $sign_begin_time_setting;

            if ($sign->inser_type) {

                return response()->json(['status' => false, 'message' => '不能删除报名用户'], 201);

            } elseif ($sign->status) {

                return response()->json(['status' => false, 'message' => '不能删除已签到的用户'], 201);

            } elseif (now()->timestamp >= $sign_begin_time) {

                return response()->json(['status' => false, 'message' => '签到时间内不能删除用户'], 201);

            } else {

                $is_del = Sign::deleteCache($id, $this->cache_name);
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