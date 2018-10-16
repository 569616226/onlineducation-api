<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/7/29
 * Time: 15:28
 */

namespace App\Repositories;

use App\Http\Resources\Guest as GuestResources;
use App\Http\Resources\GuestCollection;
use App\Models\Guest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Class DiscusseRepository
 * @package App\Repositories
 */
class GuestRepository extends Repository
{

    /**
     * @var string
     */
    protected $cache_name;

    /**
     * DiscusseRepository constructor.
     * @param $cache_name
     */
    public function __construct()
    {
        $this->cache_name = 'guests';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLists()
    {
        try {

            $guests = Guest::recent($this->cache_name, ['labels']);

            return response()->json(new GuestCollection($guests));

        } catch (\Exception $e) {

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

            $guest = new GuestResources(Guest::getCache($id, $this->cache_name, ['labels', 'discusses', 'vip']));
            return response()->json($guest);

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param array $data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function set_label($id, array $label_ids)
    {
        try {

            Guest::getCache($id, $this->cache_name, ['labels', 'discusses', 'vip'])->setLabel($label_ids);

            return response()->json(['status' => true, 'message' => '操作成功']);

        } catch (\Exception $e) {

            report($e);
        }

    }

    /**
     * @param $id
     * @param $data
     * @param $role_ids
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGuest($id, $data, $role_ids)
    {
        try {

            DB::beginTransaction();

            $guest = Guest::updateCache($id, $data,$this->cache_name);

            if ($guest) {

                if (auth_user()->isSuperAdmin()) {

                    $roles = Role::find($role_ids);
                    $guest->syncRoles($roles);

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

}