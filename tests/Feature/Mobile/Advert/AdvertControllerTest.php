<?php

namespace Tests\Feature\Mobile\Advert;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AdvertControllerTest extends BaseCase
{
    /**
     * @test
     */
    public function visit_mobile_adverts_lists_page()
    {

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET','api/item/advert/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'path',
                    'type',
                    'url',
                    'order',
                    'created_at'
                ]
            ]);
    }
}
