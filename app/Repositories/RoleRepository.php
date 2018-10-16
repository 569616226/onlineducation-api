<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 18:52
 */

namespace App\Repositories;

use App\Http\Resources\PermissionCollection;
use App\Http\Resources\RoleCollection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleRepository
 * @package App\Repositories
 */
class RoleRepository
{

    /**
     * @var string
     */
    protected $cache_name;
    protected $permissions;

    /**
     * RoleRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'roles';
        $this->permissions = Permission::getPermissions();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {

        try {
            return response()->json(['permissions' => $this->permissions]);

        } catch (\Exception $exception) {

            report($exception);

        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        try {

            $role = new \App\Http\Resources\Role(Role::findById($id));
            return response()->json([
                'role'  => $role,
                'perms' => new PermissionCollection($this->permissions)
            ]);

        } catch (\Exception $exception) {

            report($exception);

        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList()
    {

        try {

            $roles = Role::all();

            return response()->json(new RoleCollection($roles));

        } catch (\Exception $exception) {

            report($exception);

        }

    }

    /**
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(array $data)
    {

        try {

            DB::beginTransaction();

            $role = Role::create(array_except($data,'permission_ids'));
            $permission_ids = $data['permission_ids'];

            if (!empty($permission_ids)) {
                $filter_perms = $this->permissions->filter(function($permission) use($permission_ids){
                    return in_array($permission->id,$permission_ids) ;
                })->all();
                $role->givePermissionTo($filter_perms);
            }

            if ($role) {

                DB::commit();
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {

                DB::rollback();
                return response()->json(['status' => false, 'message' => '操作失败']);
            }
        } catch (\Exception $exception) {

            DB::rollback();
            report($exception);

        }

    }

    /**
     * @param $id
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRole($id, array $data)
    {
        try {

            DB::beginTransaction();

            $role = Role::findById($id);
            $update_role = $role->update(array_except($data,'permission_ids'));

            if ($update_role) {

                $permissionIds = $data['permission_ids'];

                if (!empty($permissionIds)) {
                    $filter_perms = $this->permissions->filter(function($permission) use($permissionIds){
                        return in_array($permission->id,$permissionIds) ;
                    })->all();
                    $role->givePermissionTo($filter_perms);
                }

                DB::commit();
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                DB::rollback();
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $exception) {
            DB::rollback();
            report($exception);
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

            $role = Role::findById($id);

            if (!$role->users->isEmpty()) {
                DB::rollback();
                return response()->json(['status' => false, 'message' => '不能删除有账号的角色'], 201);
            } else {

                $role->users()->detach();

                $is_del = Role::destroy($id);

                if ($is_del) {

                    DB::commit();
                    return response()->json(['status' => true, 'message' => '操作成功']);
                } else {

                    DB::rollback();
                    return response()->json(['status' => false, 'message' => '操作失败']);
                }
            }

        } catch (\Exception $exception) {

            DB::rollback();
            report($exception);

        }

    }


}