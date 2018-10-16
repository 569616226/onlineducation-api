<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\UserCollection;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class UserRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;
    protected $user_names;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'users';
        $this->user_names = User::names($this->cache_name);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $users = Cache::get('user_list');
            if (!$users) {
                $users = User::with(['roles'])->whereNotIn('id', [auth_user()->id])->latest()->get();
                Cache::tags($this->cache_name)->put('user_list', $users, 21600);
            }

            return response()->json(new UserCollection($users));

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function names()
    {
        try {

            return response()->json($this->user_names);

        } catch (\Exception $e) {

            report($e);

        }
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUser(array $data, $role_id)
    {
        try {

            DB::beginTransaction();

            if (array_key_exists('password', $data) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = User::storeCache($data, $this->cache_name);

            if ($user) {

                if (auth_user()->isSuperAdmin()) {

                    $role = Role::find($role_id);
                    $user->assignRole($role->name);

                }

                DB::commit();
                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                DB::rollback();
                return response()->json(['status' => false, 'message' => '操作失败']);
            }


        } catch (\Exception $e) {

            DB::rollback();
            report($e);

        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {

            $user = User::getCache($id, $this->cache_name);
            $user_names = array_values(array_diff($this->user_names, [$user->name]));

            return response()->json([
                'user'       => new \App\Http\Resources\User($user),
                'user_names' => $user_names
            ]);

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param array $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(array $data, $id, $role_id)
    {
        try {

            DB::beginTransaction();

            $user = User::updateCache($id, $data, $this->cache_name);

            if ($user) {

                if (auth_user()->isSuperAdmin()) {
                    $role = Role::find($role_id);
                    $user->syncRoles($role);
                }

                DB::commit();
                return response()->json(['status' => true, 'message' => '操作成功']);

            } else {

                DB::rollback();
                return response()->json(['status' => false, 'message' => '操作失败']);
            }


        } catch (\Exception $e) {

            DB::rollback();
            report($e);
        }

    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {

            DB::beginTransaction();

            $user = User::getCache($id, $this->cache_name, ['roles']);
            $roleNames = $user->getRoleNames();

            foreach ($roleNames as $roleName) {
                $user->removeRole($roleName);
            }

            $is_del = User::deleteCache($id, $this->cache_name);

            if ($is_del) {
                DB::commit();
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                DB::rollback();
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $e) {
            DB::rollback();
            report($e);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function frozen($id)
    {
        try {
            $user = User::getCache($id, $this->cache_name, ['roles']);

            if (!$user->frozen) {

                $is_frozen = $user->update(['frozen' => 1]);

                if ($is_frozen) {

                    return response()->json(['status' => true, 'message' => '操作成功']);

                } else {

                    return response()->json(['status' => false, 'message' => '操作失败']);
                }

            } else {

                return response()->json(['status' => true, 'message' => '操作成功']);
            }

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function refrozen($id)
    {
        try {

            $user = User::getCache($id, $this->cache_name, ['roles']);

            if ($user->frozen) {

                $is_frozen = $user->update(['frozen' => 0]);

                if ($is_frozen) {

                    return response()->json(['status' => true, 'message' => '操作成功']);

                } else {

                    return response()->json(['status' => false, 'message' => '操作失败']);
                }

            } else {

                return response()->json(['status' => true, 'message' => '操作成功']);
            }

        } catch (\Exception $e) {

            report($e);
        }

    }


}