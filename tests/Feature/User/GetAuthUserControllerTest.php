<?php

namespace Tests\Feature\User;

use App\Models\User;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetAuthUserControllerTest extends BaseCase
{

    public function test_get_auth_user_data_success_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/me');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'created_at',
            ]);
    }


}
