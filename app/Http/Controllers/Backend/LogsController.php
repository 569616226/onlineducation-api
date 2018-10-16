<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Repositories\LogsRepository;


/**
 * Class LogsController
 * @package App\Http\Controllers\Backend
 */
class LogsController extends Controller
{

    /**
     * @var LogsRepository
     */
    private $repository;

    /**
     * LessonController constructor.
     * @param LogsRepository $repository
     */
    public function __construct(LogsRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists()
    {

       return $this->repository->getLists();


    }
}
