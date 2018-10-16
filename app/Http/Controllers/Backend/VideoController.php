<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Video\AllSuccessVideoRequest;
use App\Http\Requests\Video\AllVideoNameRequest;
use App\Http\Requests\Video\AllVideoRequest;
use App\Http\Requests\Video\DelVideoRequest;
use App\Http\Requests\Video\GetVideoRequest;
use App\Http\Requests\Video\GetVideoSignatureRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Http\Resources\VideoCollection;
use App\Models\Video;
use App\Repositories\VideoRepository;

/**
 * Class VideoController
 * @package App\Http\Controllers\Backend
 */
class VideoController extends Controller
{

    /**
     * @var VideoRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param VideoRepository $repository
     */
    public function __construct(VideoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 视频列表
     * @param AllVideoRequest $request
     * @return \App\Http\Resources\VideoCollection
     */
    public function lists(AllVideoRequest $request)
    {

        return $this->repository->getLists();


    }

    /**
     * 视频名称
     * @param AllVideoNameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function names(AllVideoNameRequest $request)
    {
        return $this->repository->names();
    }

    /**
     * 返回转码成功，并且没有被章节使用的视频列表
     * @param AllSuccessVideoRequest $request
     * @return VideoCollection
     */
    public function successList(AllSuccessVideoRequest $request)
    {

        return $this->repository->successList();

    }

    /**
     * 视频查看
     * @param Video $video
     * @param GetVideoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetVideoRequest $request, $id)
    {
        return $this->repository->edit($id);

    }

    /** 更新视频*/
    public function update($id, UpdateVideoRequest $request)
    {

        $newName = $request->name;

        return $this->repository->updateVideo($id, $newName);

    }

    /**
     * 创建视频
     *  本地服务器 合并视频
     */
    public function store(UploadVideoRequest $request)
    {
        $data = [
            'name'   => $request->videoName,
            'fileId' => $request->fileId,
        ];

        return $this->repository->store($data);

    }


    /**
     * 删除视频
     * @param $id
     * @param DelVideoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelVideoRequest $request, $id)
    {

        return $this->repository->deleteVideo($id);

    }

    /**
     *视频解密播放 url
     *
     */
    public function getKeyUrl()
    {
        $edk = request('edk');
        $fileId = request('fileId');
        $keySource = request('keySource');

        return $this->repository->getKeyUrl($edk, $fileId, $keySource);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVideoSignature(GetVideoSignatureRequest $request)
    {
        return $this->repository->get_video_signature();
    }


}
