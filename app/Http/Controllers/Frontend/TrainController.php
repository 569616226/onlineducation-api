<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Train;
use App\Repositories\TrainRepository;
use QrCode;

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
     * 显示数据
     * @param Train $nav
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {

        return $this->repository->get_mobile_edit($id);

    }

    /**
     *
     * @param $nav_id
     */
    public function navTrains($nav_id)
    {

        return $this->repository->nav_trains($nav_id);
    }


    /**
     * @param $nav_id
     * @param $genre_id
     */
    public function genreTrains($nav_id, $genre_id)
    {

        return $this->repository->genre_trains($nav_id, $genre_id);

    }

    /**
     * @param $train_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function collect($train_id)
    {
        return $this->repository->collect_train($train_id);
    }

    /**
     * @param $train_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function uncollect($train_id)
    {
        return $this->repository->uncollect_train($train_id);
    }
}
