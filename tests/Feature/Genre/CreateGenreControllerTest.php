<?php

namespace Tests\Feature\Genre;

use App\Models\Genre;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateGenreControllerTest extends BaseCase
{

    public function test_create_a_genre_success_()
    {

        $counts = Genre::all()->count();

        $this->user->givePermissionTo('create_genre');

        $data = [
            'name' => $this->faker->name,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/genre/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_creates = Genre::all();

        $this->assertCount($counts+1,$after_creates);

    }


    public function test_create_a_genre_402_fail_()
    {

        $data = [
            'name' => $this->faker->name,
        ];


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/genre/', $data);

        $response->assertStatus(403);

    }
}
