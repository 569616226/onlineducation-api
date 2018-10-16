<?php

namespace Tests\Feature\Mobile\Guest;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GuestCollectLessonsControllerTest extends BaseCase
{

    public function test_visit_guest_collect_lessons_paginate_data()
    {
        $this->guest->lessons()->sync([
            $this->lesson->id => [
                'is_like'      => false,
                'is_collect'   => true,
                'is_pay'       => false,
                'collect_date' => now(),
                'add_date'     => null,
                'sections'     => [],
                'last_section' => null,
            ]
        ]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/guest/collect_lessons');

        $data = \GuzzleHttp\json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(1, count($data['data']));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'     => [
                    '*' => [
                        'id',
                        'name',
                        'title',
                        'pictrue',
                        'price',
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
