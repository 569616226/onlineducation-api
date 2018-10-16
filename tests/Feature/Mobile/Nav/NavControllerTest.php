<?php

namespace Tests\Feature\Mobile\Nav;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class NavControllerTest extends BaseCase
{
    

    public function test_visit_mobile_navs_lists_page()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET','/api/item/nav/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(6)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'type'
                ]
            ]);
    }
}
