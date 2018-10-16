<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\User\AllUserNameRequest;
use App\Http\Requests\User\AllUserRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DelUserRequest;
use App\Http\Requests\User\FrozenUserRequest;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\RefrozenUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class UsersController
 * @package App\Http\Controllers\Backend
 */
class UsersController extends Controller
{

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * EducationController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param AllUserRequest $request
     * @return mixed
     */
    public function lists(AllUserRequest $request)
    {
        return  $this->repository->getLists();

    }


    /**
     * \@param AllUserNameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function names(AllUserNameRequest $request)
	{
	    return $this->repository->names();

	}


    /**
     * @param $id
     * @param GetUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(GetUserRequest $request, $id )
    {

        return $this->repository->edit($id);

    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request )
    {

        $data = array_except($request->all(),['role_id']);

        return $this->repository->storeUser( $data,$request->role_id);

    }


    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(User $user, UpdateUserRequest $request )
    {
        $data = array_except($request->all(),['role_id','password']);
        $role_id = $request->role_id;
        $password = $request->password;

        if ($password) {
            $data['password'] = Hash::make($password);
        }

        return $this->repository->updateUser($data,$user->id,$role_id);

    }


    /**
     * @param $id
     * @param DelUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DelUserRequest $request, $id)
    {

        return $this->repository->delete($id);

    }


    /**
     * @param $id
     * @param FrozenUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function frozen(FrozenUserRequest $request, $id)
    {

        return $this->repository->frozen($id);

    }


    /**
     * @param $id
     * @param RefrozenUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refrozen(RefrozenUserRequest $request, $id)
    {
        return $this->repository->refrozen($id);

    }

}
