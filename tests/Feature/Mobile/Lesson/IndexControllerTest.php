<?php

namespace Tests\Feature\Mobile\Lesson;


use App\Models\GuestLesson;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class IndexControllerTest extends BaseCase
{

    public function test_visit_mobile_index_paginate_data()
    {

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/index');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'last_lesson_trains',
                'nav',
                'lesson_train_names'
            ]);

        $data = (\GuzzleHttp\json_decode($response->getContent(), true));

//        dump($data);

        $this->assertEquals(4, count($data['last_lesson_trains']));
        $this->assertEquals(6, count($data['nav']));
        $this->assertEquals(1, count($data['nav'][2]['lessons']));
        $this->assertEquals(1, count($data['nav'][0]['trains']));
        $this->assertEquals(9, count($data['lesson_train_names'] ?? []));
    }

}
