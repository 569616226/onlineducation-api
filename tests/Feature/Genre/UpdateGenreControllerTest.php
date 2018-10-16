<?php

namespace Tests\Feature\Genre;

use App\Models\Genre;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateGenreControllerTest extends BaseCase
{

    public function test_update_a_genre_success_()
    {
        $this->user->givePermissionTo('update_genre');

        $data = [
            'name' => $this->faker->name,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/genre/' . $this->genre->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_genre = Genre::find($this->genre->id);

        $this->assertEquals($data['name'],$update_genre->name);

    }

    public function test_update_a_genre_403_fail_()
    {

        $data = [
            'name' => $this->faker->name,
        ];


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/genre/' . $this->genre->id . '/update', $data);

        $response->assertStatus(403);

    }

}
