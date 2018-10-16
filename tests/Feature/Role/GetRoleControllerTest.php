<?php

namespace Tests\Feature\Role;

use Spatie\Permission\Models\Role;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetRoleControllerTest extends BaseCase
{

    public function test_get_role_data_success_()
    {
        $this->user->givePermissionTo('update_role');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/' . $this->role->id . '/edit');

        $response
            ->assertStatus(200)
            ->assertJson([
                'role' => [
                    'id'           => $this->role->id,
                    'name'         => $this->role->name,
                    'display_name' => $this->role->display_name,
                    'created_at'   => $this->role->created_at->toDateTimeString()
                ]
            ]);
    }

    public function test_get_role_data_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/' . $this->role->id . '/edit');

        $response->assertStatus(403);
    }


}
