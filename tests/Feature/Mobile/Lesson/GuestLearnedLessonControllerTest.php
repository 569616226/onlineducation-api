<?php

namespace Tests\Feature\Mobile\Lesson;


use App\Models\GuestLesson;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GuestLearnedLessonControllerTest extends BaseCase
{

   
    public function test_visit_guest_learned_lessons_paginate_data()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/learned_lessons');

        $data = json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(1,count($data['data']));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'     => [
                    '*' => [
                        'id',
                        'name',
                        'title',
                        'type',
                        'pictrue',
                        'play_times',
                        'duration',
                        'sections',
                    ]
                ], 'links' => [
                    'first', 'last', 'next', 'prev'
                ]
                , 'meta'   => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }


}
