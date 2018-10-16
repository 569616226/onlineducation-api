<?php

namespace Tests\Feature\Mobile\Genre;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GenreControllerTest extends BaseCase
{

    public function test_visit_mobile_genres_lists_page()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET','/api/item/genre/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id','name'
                ]
            ]);
    }
}
