<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Train\AllTrainRequest;
use App\Http\Requests\Train\CreateTrainRequest;
use App\Http\Requests\Train\DelTrainRequest;
use App\Http\Requests\Train\GetTrainRequest;
use App\Http\Requests\Train\UpdateTrainRequest;
use App\Models\Train;
use App\Repositories\TrainRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use QrCode;
use Tests\Feature\AllTrainControllerTest;

/**
 * Class TrainController
 * @package App\Http\Controllers\Backend
 */
class TrainController extends Controller
{

    /**
     * @var TrainRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param TrainRepository $repository
     */
    public function __construct(TrainRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param AllTrainRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllTrainRequest $request)
    {

        return $this->repository->getLists();

    }

    /**
     * 保存活动
     * @param CreateTrainRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateTrainRequest $request)
    {

        $data = [
            'name' => $request->name,
            'title' => $request->title,
            'pic' => $request->pic,
            'start_at' => Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at)->timestamp,
            'end_at' => Carbon::createFromFormat('Y-m-d H:i:s', $request->end_at)->timestamp,
            'address' => $request->address,
            'discrible' => htmlentities(addslashes($request->discrible)),
            'collect_guest_ids' => [],
            'nav_id' => $request->nav_id,
            'creator' => auth_user()->name,
        ];

        $genre_ids = $request->geren_ids;

        return $this->repository->storeTrain($data, $genre_ids);

    }

    /**
     * 显示数据
     * @param GetTrainRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetTrainRequest $request, $id)
    {

        return $this->repository->edit($id);

    }

    /**
     * 更新数据
     * @param $id
     * @param UpdateTrainRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateTrainRequest $request)
    {

        $data = [
            'name' => $request->name,
            'title' => $request->title,
            'pic' => $request->pic,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'address' => $request->address,
            'discrible' => $request->discrible,
            'nav_id' => $request->nav_id,
        ];

        $genre_ids = $request->geren_ids;

        return $this->repository->updateTrain($data, $id, $genre_ids);

    }

    /**
     * 删除数据
     * @param DelTrainRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DelTrainRequest $request, $id)
    {

        return $this->repository->delete($id);

    }

    /**
     * 下载二维码
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadQrcode($id)
    {
        return $this->repository->download_qrcode($id);
    }

    /**
     * 导入签到名单
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importTrains($id, Request $request)
    {
        $excelfile = $request->file('files');

        return $this->repository->import_trains($excelfile, $id);

    }


    /**
     * 下载签到名单模板
     *
     * @return mixed
     */
    public function downTrainTemplate()
    {
        $pathToFile = config('other.train_template_url');

        return $this->repository->download_template($pathToFile);


    }


}
