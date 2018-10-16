<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\AllGuestRequest;
use App\Http\Requests\Guest\GetGuestRequest;
use App\Http\Requests\Guest\SetLabelToGuestRequest;
use App\Http\Requests\Guest\UpdateGuestRequest;
use App\Models\Guest;
use App\Repositories\GuestRepository;
use Illuminate\Http\Request;

/**
 * Class GuestController
 * @package App\Http\Controllers\Backend
 */
class GuestController extends Controller
{

    /**
     * @var GuestRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param GuestRepository $repository
     */
    public function __construct(GuestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @param AllGuestRequest $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function lists(AllGuestRequest $request)
    {

        return $this->repository->getLists();


    }


    /**
     * @param $id
     * @param GetGuestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetGuestRequest $request, $id)
    {

        return $this->repository->edit($id);

    }


    /**
     * @param Guest $guest
     * @param SetLabelToGuestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLabel($id, SetLabelToGuestRequest $request)
    {
        $label_ids = $request->label_ids;

        return $this->repository->set_label($id, $label_ids);

    }

    /**
     * @param $id
     * @param UpdateGuestRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateGuestRequest $request)
    {
        $role_ids = $request->role_ids;
        $data = array_except($request->all(),['role_ids']);

        return $this->repository->updateGuest($id, $data,$role_ids);
    }

}
