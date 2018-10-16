<?php

namespace Tests\Feature\Permission;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllPermissionControllerTest extends BaseCase
{

    public function test_get_all_permiision_data_success_()
    {

        $this->user->givePermissionTo('permission');
        PassportMultiauth::actingAs($this->user);
        $response = $this->json('GET', '/api/admin/permission/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                'permissions' => [
                    '*' => [
                        'id',
                        'name',
                        'display_name',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ]);
    }

    public function test_get_all_permiision_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);
        $response = $this->json('GET', '/api/admin/permission/lists');

        $response->assertStatus(403);
    }


}
