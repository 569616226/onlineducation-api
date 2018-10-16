<?php

namespace Tests\Feature\Nav;

use App\Models\Nav;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllNavNameControllerTest extends BaseCase
{


    public function test_get_all_nav_names_data_success_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/names');

        $response
            ->assertStatus(200)
            ->assertJsonCount(10);
    }


}
