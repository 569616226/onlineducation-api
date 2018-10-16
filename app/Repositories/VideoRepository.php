<?php
/**
 * Created by PhpStorm.
 * Video: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\VideoCollection;
use App\Models\Section;
use App\Models\Video;
use App\Models\VideoUrl;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Vod\VodApi;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class VideoRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;
    /**
     * @var
     */
    protected $video_names;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'videos';
        $this->video_names = Video::names($this->cache_name);
    }


    /**
     * @return VideoCollection
     */
    public function getLists()
    {
        try {

            $videos = Video::recent($this->cache_name);

            return new VideoCollection($videos);

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return VideoCollection
     */
    public function successList()
    {
        $sections = Section::recent($this->cache_name);
        $section_video_ids = $sections->pluck('video_id')->toArray();
        $videos = Cache::get('video_success_list');
        if (!$videos) {
            $videos = Video::with(['video_urls'])->latest()->whereStatus(2)->whereNotIn('id', $section_video_ids)->get();
            Cache::tags($this->cache_name)->put('video_success_list', $videos, 21600);
        }

        return new VideoCollection($videos);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function names()
    {
        try {

            return response()->json($this->video_names);

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

            $video = Video::storeCache($data, $this->cache_name);
            if ($video) {

                $is_encode = $this->encodeTransVideo($data['fileId']);//转码

                if ($is_encode) {
                    return response()->json(['status' => true, 'message' => '操作成功']);

                } else {
                    return response()->json(['status' => false, 'message' => '视频转码失败']);
                }

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

            $video = Video::getCache($id, $this->cache_name);
            $video_names = array_values(array_diff($this->video_names, [$video->name]));

            return response()->json([
                'video'       => new \App\Http\Resources\Video($video),
                'video_names' => $video_names
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
    public function updateVideo($id, $newName, $update_origin_name = false)
    {
        try {

            $fileId = Video::getCache($id, $this->cache_name)->fileId;

            $parms = [
                'fileId' => $fileId, 'fileName' => $newName,
            ];

            /*更新视频源文件名称*/
            if ($update_origin_name) {
                $video = videoCommon('ModifyVodInfo', $parms, now()->timestamp);
                if ($video['code'] != 0 || $video['codeDesc'] != 'Success') {

                    return response()->json(['status' => false, 'message' => '操作失败']);
                }
            } else {
                $video = Video::updateCache($id, ['name' => $newName], $this->cache_name);
                if ($video) {
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {
                    return response()->json(['status' => false, 'message' => '操作失败']);
                }
            }

        } catch (\Exception $e) {

            DB::rollback();
            report($e);
        }

    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteVideo($id, $del_origin_video = false)
    {
        try {

            DB::beginTransaction();

            $video = Video::getCache($id, $this->cache_name);
            $parms = [
                'fileId' => $video->fileId, 'priority' => 0,
            ];

            if ($video->section) {
                DB::rollback();
                return response()->json(['status' => false, 'message' => '不能删除使用中的视频']);

            } else {

                /*删除视频源文件*/
                if ($del_origin_video) {

                    $result = videoCommon('DeleteVodFile', $parms, now()->timestamp);

                    if ($result['code'] != 0 && ($result['code'] == 4000 || $result['message'] == '(10008)视频不存在')) {

                        DB::rollback();
                        return response()->json(['status' => false, 'code' => $result['code'], 'message' => $result['message']]);
                    }

                } else {
                    VideoUrl::whereIn('id', $video->video_urls->pluck('id')->toArray())->delete();

                    clear_cache('video_urls');

                    $is_del = Video::deleteCache($video->id, $this->cache_name);
                    if ($is_del) {

                        DB::commit();
                        return response()->json(['status' => true, 'message' => '操作成功']);

                    } else {

                        DB::rollback();
                        return response()->json(['status' => false, 'message' => '操作失败']);

                    }

                }

            }


        } catch (\Exception $e) {

            DB::rollback();
            report($e);
        }
    }

    /**
     * @param $edk
     * @param $fileId
     * @param $keySource
     * @return bool|\Illuminate\Http\JsonResponse|string
     */
    public function getKeyUrl($edk, $fileId, $keySource)
    {
        $video = Video::where('fileId', $fileId)->firstOrFail();

        if (optional($video)->edk_key == $edk && $keySource == 'VodBuildInKMS') {

            $parms = [
                'edkList.0' => $edk,
            ];

            $result = videoCommon('DescribeDrmDataKey', $parms, now()->timestamp);
            if ($result['code'] == 0) {

                $dk = base64_decode($result['data']['keyList'][0]['dk']);//dkData为Base64编码的密钥数据
                return $dk;

            } else {

                return response()->json(['status' => false, 'code' => $result['code'], 'message' => $result['message']]);

            }
        }
    }


    /**
     * @param $fileId
     * @return bool
     */
    private function encodeTransVideo($fileId)
    {
        try {

            /*跳过视频转码加密*/
            if (config('app.env') === 'testing') {
                return true;
            }

            $parms = [
                'fileId'                         => $fileId,
                'notifyMode'                     => 'Finish',
                'transcode.definition.0'         => 210,
                'transcode.definition.1'         => 220,
                'transcode.definition.3'         => 230,
                'transcode.definition.4'         => 240,
                'transcode.disableHigherBitrate' => 1,
                'transcode.drm.definition'       => 10,
                'transcode.hlsMasterPlaylist'    => 1,

            ];

            $result = videoCommon('ProcessFile', $parms, now()->timestamp);

            if ($result['code'] == 0) {

                return true;

            } else {

                return false;
            }

        } catch (\Exception $e) {

            report($e);
        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_video_signature()
    {
        $secret_id = config('other.qcloud_secretid');
        $secret_key = config('other.qcloud_secretkey');

        // 确定签名的当前时间和失效时间
        $current = time();
        $expired = $current + 86400;  // 签名有效期：1天

        // 向参数列表填入参数
        $arg_list = array(
            "secretId"         => $secret_id,
            "currentTimeStamp" => $current,
            "expireTime"       => $expired,
            "random"           => rand()
        );

        // 计算签名
        $orignal = http_build_query($arg_list);
        $signature = base64_encode(hash_hmac('SHA1', $orignal, $secret_key, true) . $orignal);

        return response()->json(['signature' => $signature]);
    }


}