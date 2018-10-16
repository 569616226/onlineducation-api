<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 18:52
 */

namespace App\Repositories;

use Spatie\Permission\Models\Permission;

/**
 * Class PermissionRepository
 * @package App\Repositories
 */
class PermissionRepository
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllPermissions()
    {
        try {
            $permissions = Permission::getPermissions();
            return response()->json(['permissions' => $permissions]);
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
            $permission = Permission::findById($id);
            return response()->json(['permission' => $permission]);
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

            $perm = Permission::create($data);

            if ($perm) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $exception) {

            report($exception);

        }

    }

    /**
     * @param $id
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, array $data)
    {
        try {
            $perm = Permission::findById($id);
            $update_perm = $perm->update($data);

            if ($update_perm) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $exception) {

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

            $is_del = Permission::destroy($id);

            if ($is_del) {
                return response()->json(['status' => true, 'message' => '操作成功']);
            } else {
                return response()->json(['status' => false, 'message' => '操作失败']);
            }

        } catch (\Exception $exception) {

            report($exception);

        }

    }

}