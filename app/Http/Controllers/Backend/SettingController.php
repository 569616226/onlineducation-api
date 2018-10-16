<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\EditSignStartTimeRequest;
use App\Http\Requests\Setting\GetSendVipTimeRequest;
use App\Http\Requests\Setting\GetIndexTypeRequest;
use App\Http\Requests\Setting\GetWechatSubRequest;
use App\Http\Requests\Setting\SetIndexTypeRequest;
use App\Http\Requests\Setting\SetSendVipTimeRequest;
use App\Http\Requests\Setting\SetSignStartTimeRequest;
use App\Http\Requests\Setting\SetWechatSubRequest;
use App\Repositories\SettingRepository;
use Illuminate\Http\Request;

/**
 * Class SettingController
 * @package App\Http\Controllers\Backend
 */
class SettingController extends Controller
{

    /**
     * @var SettingRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param SettingRepository $repository
     */
    public function __construct(SettingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @param GetIndexTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndexType(GetIndexTypeRequest $request, $id)
    {

        return $this->repository->get_index_type($id);

    }

    /**
     * @param $id
     * @param SetIndexTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setIndexType($id, SetIndexTypeRequest $request)
    {

        $data = $request->all();

        return $this->repository->update($data,$id);

    }

    /**
     * @param $id
     * @param GetSendVipTimeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVipSendTime($id, GetSendVipTimeRequest $request)
    {
        return $this->repository->get_vip_send_time($id);
    }

    /**
     * @param $id
     * @param SetSendVipTimeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setVipSendTime($id, SetSendVipTimeRequest $request)
    {

        $data = $request->all();

        return $this->repository->update($data,$id);

    }

    /**
     * @param $id
     * @param GetWechatSubRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWechatSub($id, GetWechatSubRequest $request)
    {
        return $this->repository->get_wechat_sub($id);
    }
    /**
     * @param $id
     * @param SetWechatSubRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setWechatSub($id, SetWechatSubRequest $request)
    {

        $wechat_sub_htmlentities = htmlentities(addslashes($request->wechat_sub));
        $data = [
            'wechat_sub' =>strip_tags(str_replace('<div>', "\n", $wechat_sub_htmlentities), "<a>")
        ];

        return $this->repository->update($data,$id);

    }


    /**
     * @param $id
     * @param EditSignStartTimeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signStartTime(EditSignStartTimeRequest $request, $id)
    {
        return $this->repository->signStartTime($id);

    }


    /**
     * @param $id
     * @param SetSignStartTimeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setSignStartTime($id, SetSignStartTimeRequest $request)
    {

        $datas = [
            'sign_start_time' => $request->sign_start_time,
        ];

        return $this->repository->update($datas,$id);

    }

}
