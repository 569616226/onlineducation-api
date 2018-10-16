<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Educational\AllEducationalRequest;
use App\Http\Requests\Educational\CreateEducationalRequest;
use App\Http\Requests\Educational\DelEducationalRequest;
use App\Http\Requests\Educational\EditEducationalRequest;
use App\Http\Requests\Educational\UpdateEducationalRequest;
use App\Repositories\EducationRepository;
use Illuminate\Http\Request;


/**
 * Class EducationController
 * @package App\Http\Controllers\Backend
 */
class EducationController extends Controller
{

    /**
     * @var EducationRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param EducationRepository $repository
     */
    public function __construct(EducationRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param AllEducationalRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllEducationalRequest $request)
    {
       return $this->repository->getLists();

    }


    /**
     * @param CreateEducationalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateEducationalRequest $request)
    {
        $name = $request->name;
        $content = htmlentities(addslashes($request->get('content')));

        $data = [
            'name'    => $name,
            'content' => $content,
        ];

        return $this->repository->store($data);


    }


    /**
     * @param EditEducationalRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditEducationalRequest $request, $id)
    {

        return $this->repository->edit($id);
    }


    /**
     * @param $id
     * @param UpdateEducationalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateEducationalRequest $request)
    {
        $name = $request->name;
        $content = htmlentities(addslashes($request->get('content')));

        $data = [
            'name'    => $name,
            'content' => $content,
        ];

        return $this->repository->update($data,$id);

    }


    /**
     * @param DelEducationalRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelEducationalRequest $request, $id)
    {

        return $this->repository->delete($id);


    }
}
