<?php

namespace Tests\Feature\Nav;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllNavControllerTest extends BaseCase
{

    public function test_get_all_navs_data_success_()
    {
        $this->user->givePermissionTo('nav');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(10)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'pictrue',
                    'type',
                    'ordered',
                    'order_type',
                    'is_hide',
                    'created_at',
                ]
            ]);
    }

    public function test_get_all_navs_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/lists');

        $response->assertStatus(403);
    }

}
