<?php

namespace Tests\Feature\Role;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllRoleControllerTest extends BaseCase
{

    public function test_get_all_role_success_()
    {
        $this->user->givePermissionTo('role');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(5)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'display_name',
                    'created_at'
                ]
            ]);
    }


    public function test_get_all_role_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/lists');

        $response->assertStatus(403);
    }


}
