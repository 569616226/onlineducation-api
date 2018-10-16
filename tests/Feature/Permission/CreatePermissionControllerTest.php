<?php

namespace Tests\Feature\Permission;

use SMartins\PassportMultiauth\PassportMultiauth;
use Spatie\Permission\Models\Permission;
use Tests\BaseCase;

class CreatePermissionControllerTest extends BaseCase
{

    public function test_create_a_permission_success_()
    {
        $perm_counts = Permission::all()->count();

        $data = [
            'name'         => 'admin.create_role',
            'display_name' => '创建角色'
        ];
        $this->user->givePermissionTo('create_permission');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/permission/create', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_create_perms = Permission::all();

        $this->assertCount($perm_counts+1,$after_create_perms);
    }

    public function test_create_a_permission_403_fail_()
    {
        $perm_counts = Permission::all()->count();

        $data = [
            'name'         => 'admin.create_role',
            'display_name' => '创建角色'
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/permission/create', $data);


        $response->assertStatus(403);

        $after_create_perms = Permission::all();

        $this->assertCount($perm_counts,$after_create_perms);
    }

}
