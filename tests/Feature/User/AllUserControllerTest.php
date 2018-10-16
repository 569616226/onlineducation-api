<?php

namespace Tests\Feature\User;

use App\Models\User;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllUserControllerTest extends BaseCase
{

    public function test_get_all_user_data_success_()
    {
        $this->user->givePermissionTo('user');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'nickname',
                    'frozen',
                    'gender',
                    'role',
                    'created_at',
                ]
            ]);
    }

    public function test_get_all_user_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/lists');

        $response
            ->assertStatus(403);
    }

}
