<?php

namespace Tests\Feature\Genre;

use App\Models\Genre;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelGenreControllerTest extends BaseCase
{

    public function test_delete_a_genre_no_lesson_success_()
    {
        $this->user->givePermissionTo('del_genre');
        $genre_no_lesson = factory(Genre::class)->create();
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/genre/' . $genre_no_lesson->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $del_genres = Genre::onlyTrashed()->whereId($genre_no_lesson->id)->get();

        $this->assertCount(1,$del_genres);

    }

    public function test_delete_a_genre_has_lesson_()
    {
        $this->user->givePermissionTo('del_genre');
        $this->genre->lessons()->sync([$this->lesson->id]);
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/genre/' . $this->genre->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除有课程的标签']);

        $del_genres = Genre::onlyTrashed()->whereId($this->genre->id)->get();

        $this->assertCount(0,$del_genres);
    }

    public function test_delete_a_genre_403_fail_()
    {

        $genre_no_lesson = factory(Genre::class)->create();
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/genre/' . $genre_no_lesson->id . '/delete');

        $response->assertStatus(403);

        $del_genres = Genre::onlyTrashed()->whereId($genre_no_lesson->id)->get();

        $this->assertCount(0,$del_genres);

    }

}
