<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Genre\AllGenreNameRequest;
use App\Http\Requests\Genre\AllGenreRequest;
use App\Http\Requests\Genre\CreateGenreRequest;
use App\Http\Requests\Genre\DelGenreRequest;
use App\Http\Requests\Genre\EditGenreRequest;
use App\Http\Requests\Genre\UpdateGenreRequest;
use App\Repositories\GenreRepository;
use Illuminate\Http\Request;

/**
 * Class GenreController
 * @package App\Http\Controllers\Backend
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
     *
     * @param AllGenreRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllGenreRequest $request)
    {

        return $this->repository->getLists();

    }


    /**
     * @param AllGenreNameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function names(AllGenreNameRequest $request)
    {

		return $this->repository->names();
    }


    /**
     * @param CreateGenreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateGenreRequest $request )
    {

        return $this->repository->store($request->all());
    }


    /**
     * @param $id
     * @param EditGenreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditGenreRequest $request, $id )
    {

        return $this->repository->edit($id);

    }


    /**
     * @param $id
     * @param UpdateGenreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateGenreRequest $request )
    {
        return $this->repository->update($request->all(),$id);

    }

    /**
     * @param $id
     * @param DelGenreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelGenreRequest $request, $id)
    {

        return $this->repository->delete($id);
    }

}
