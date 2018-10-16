<?php

namespace Tests\Feature\Mobile\Lesson;


use App\Models\GuestLesson;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GenreLessonControllerTest extends BaseCase
{

    public function test_visit_mobile_genre_lessons_paginate_data()
    {
        $this->genre->lessons()->attach([$this->lesson->id]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/' . $this->nav->id . '/nav/' . $this->genre->id . '/genre_lessons');

        $data = json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(1,count($data['data']));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'   => [
                    '*' => [
                        'id',
                        'name',
                        'title',
                        'type',
                        'pictrue',
                        'price',
                        'like',
                        'is_like',
                        'is_collect',
                        'is_show',
                        'play_times',
                        'for',
                        'learning',
                        'describle',
                        'educational',
                        'teacher',
                        'sections'
                    ]
                ],
                'links'  => [
                    'first', 'last', 'next', 'prev'
                ]
                , 'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }

}
