<?php

namespace Tests\Feature\Genre;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllGenreControllerTest extends BaseCase
{

    public function test_get_all_genres_data_success_()
    {
        $this->user->givePermissionTo('genre');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/genre/lists');

        $response
            ->assertJsonCount(2)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'created_at'
                ]
            ]);
    }

    public function test_get_all_genres_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/genre/lists');

        $response->assertStatus(403);
    }


}
