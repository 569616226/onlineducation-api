<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Sign;
use App\Repositories\SignRepository;
use Illuminate\Http\Request;

/**
 * Class SignController
 * @package App\Http\Controllers\Backend
 */
class SignController extends Controller
{

    /**
     * @var SignRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param SignRepository $repository
     */
    public function __construct(SignRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * 活动报名
     * @param Sign $nav
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $train_id)
    {

        $data = [
            'name'       => $request->name,
            'tel'        => $request->tel,
            'company'    => $request->company,
            'offer'      => $request->offer,
            'referee'    => $request->referee,
            'inser_type' => 1,
            'train_id'   => $train_id
        ];

        return $this->repository->store($data);

    }


    /**
     * @param $tel
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSignByTel()
    {
        $tel = request('tel');
        $train_id = request('train_id');

        return $this->repository->get_sign_by_tel($tel,$train_id);

    }


    /**
     * @param $id
     */
    public function guestSigned(Request $request, $id)
    {
        return $this->repository->guest_signed($id, $request->all());
    }


}
