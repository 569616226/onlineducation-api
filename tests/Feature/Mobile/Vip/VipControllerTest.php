<?php

namespace Tests\Feature\Mobile\Vip;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class VipControllerTest extends BaseCase
{

    public function test_visit_mobile_vips_lists_page()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET','api/item/vip/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'expiration',
                    'price',
                    'count',
                    'describle',
                    'up',
                    'down',
                    'created_at'
                ]
            ]);
    }
}
