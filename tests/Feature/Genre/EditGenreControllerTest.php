<?php

namespace Tests\Feature\Genre;

use App\Models\Genre;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class EditGenreControllerTest extends BaseCase
{

    public function test_edit_genre_success_()
    {

        $this->user->givePermissionTo('update_genre');

        $genre_names = Genre::where('id','<>',$this->genre->id)->get()->pluck('name')->toArray();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/genre/' . $this->genre->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'genre'       => [
                    'id'         => $this->genre->id,
                    'name'       => $this->genre->name,
                    'created_at' => $this->genre->created_at->toDateTimeString()
                ],
                'genre_names' => $genre_names
            ]);
    }

    public function test_edit_genre_403_fail_()
    {

        $genre_names = Genre::where('id','<>',$this->genre->id)->get()->pluck('name')->toArray();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/genre/' . $this->genre->id);

        $response->assertStatus(403);
    }


}
