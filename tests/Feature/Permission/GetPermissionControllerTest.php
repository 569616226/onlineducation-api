<?php

namespace Tests\Feature\Permission;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetPermissionControllerTest extends BaseCase
{


    public function test_get_permission_data_success_()
    {

        $this->user->givePermissionTo('update_permission');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/permission/' . $this->permission->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'permission' => [
                    'id'           => $this->permission->id,
                    'name'         => $this->permission->name,
                    'display_name' => $this->permission->display_name,
                    'created_at'   => $this->permission->created_at->toDateTimeString(),
                    'updated_at'   => $this->permission->updated_at->toDateTimeString(),
                ]
            ]);
    }

    public function test_get_permission_data_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/permission/' . $this->permission->id);

        $response->assertStatus(403);
    }


}
