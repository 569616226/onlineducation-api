<?php

namespace Tests\Feature\Permission;

use SMartins\PassportMultiauth\PassportMultiauth;
use Spatie\Permission\Models\Permission;
use Tests\BaseCase;

class UpdatePermissionControllerTest extends BaseCase
{


    public function test_update_a_permision_success_()
    {
        $data = [
            'display_name' => $this->faker->name
        ];
        $this->user->givePermissionTo('update_permission');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/permission/' . $this->permission->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_perm = Permission::findById($this->permission->id);

        $this->assertEquals($data['display_name'],$update_perm->display_name);
    }


    public function test_update_a_permision_403_fail_()
    {
        $data = [
            'display_name' => $this->faker->name
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/permission/' . $this->permission->id . '/update', $data);

        $response->assertStatus(403);

        $update_perm = Permission::findById($this->permission->id);

        $this->assertNotEquals($data['display_name'],$update_perm->display_name);
    }


}
