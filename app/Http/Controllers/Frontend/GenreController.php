<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Resources\GenreCollection;
use App\Models\Genre;
use App\Repositories\GenreRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

/**
 * Class GenreController
 * @package App\Http\Controllers\Frontend
 */
class GenreController extends Controller
{

    /**
     * @var GenreRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param GenreRepository $repository
     */
    public function __construct(GenreRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists()
    {

        return $this->repository->get_mobile_list();

    }
}
