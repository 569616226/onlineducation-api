<?php

namespace Tests\Feature\Role;

use Spatie\Permission\Models\Role;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class StoreRoleControllerTest extends BaseCase
{


    public function test_update_a_role_success_()
    {

        $role_counts = Role::all()->count();

        $data = [
            'name'           => 'admin',
            'display_name'   => '超级管理员',
            'permission_ids' => [$this->permission->id]
        ];

        $this->user->givePermissionTo('create_role');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/role/store', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_create_roles = Role::all();

        $this->assertCount($role_counts+1, $after_create_roles);
    }



    public function test_update_a_role_403_()
    {
        $role_counts = Role::all()->count();

        $data = [
            'display_name'   => '超级管理员',
            'permission_ids' => [$this->permission->id]
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/role/store', $data);

        $response->assertStatus(403);

        $after_create_roles = Role::all();

        $this->assertCount($role_counts, $after_create_roles);
    }


}
