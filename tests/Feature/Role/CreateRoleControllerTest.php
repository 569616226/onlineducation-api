<?php

namespace Tests\Feature\Role;

use Spatie\Permission\Models\Role;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateRoleControllerTest extends BaseCase
{

    public function test_create_a_role_success_()
    {

        $this->user->givePermissionTo('create_role');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/create');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'permissions' => [
                    '*' => [
                        'id', 'name', 'display_name', 'created_at', 'updated_at', 'deleted_at',
                    ]
                ]
            ]);

    }

    public function test_create_a_role_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/create');

        $response->assertStatus(403);

    }


}
