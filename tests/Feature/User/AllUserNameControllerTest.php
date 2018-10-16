<?php

namespace Tests\Feature\User;

use App\Models\User;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllUserNameControllerTest extends BaseCase
{

    public function test_get_all_user_names_data_success()
    {
        $this->user->givePermissionTo('user');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/names');

        $response->assertStatus(200)->assertJsonCount(5);
    }

    public function test_get_all_user_names_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/names');

        $response->assertStatus(403);
    }


}
