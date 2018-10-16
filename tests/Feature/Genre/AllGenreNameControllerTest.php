<?php

namespace Tests\Feature\Genre;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllGenreNameControllerTest extends BaseCase
{

    public function test_get_all_genre_names_data_success_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/genre/names');

        $response->assertStatus(200)->assertJsonCount(2);
    }

}
