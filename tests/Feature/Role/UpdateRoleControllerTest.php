<?php

namespace Tests\Feature\Role;

use Spatie\Permission\Models\Role;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class RoleControllerTest extends BaseCase
{


    public function test_update_a_role_success_()
    {

        $data = [
            'display_name'   => '超级管理员',
            'permission_ids' => [$this->permission->id]
        ];
        $this->user->givePermissionTo('update_role');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/role/' . $this->role->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_role = Role::findById($this->role->id);

        $this->assertEquals($data['display_name'],$update_role->display_name);
        $this->assertEquals($data['permission_ids'],$update_role->permissions()->get()->pluck('id')->toArray());
    }



    public function test_update_a_role_403_()
    {

        $data = [
            'display_name'   => '超级管理员',
            'permission_ids' => [$this->permission->id]
        ];


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/role/' . $this->role->id . '/update', $data);

        $response->assertStatus(403);

        $update_role = Role::findById($this->role->id);

        $this->assertEquals($this->role->display_name,$update_role->display_name);
        $this->assertEquals($this->role->permissions()->get()->pluck('id')->toArray(),$update_role->permissions()->get()->pluck('id')->toArray());
    }


}
