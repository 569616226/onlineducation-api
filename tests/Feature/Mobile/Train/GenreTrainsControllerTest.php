<?php

namespace Tests\Feature\Mobile\Train;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GenreTrainsControllerTest extends BaseCase
{

    public function test_visit_mobile_genre_trains_paginate_data()
    {

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/train/' . $this->train_nav->id . '/nav/' . $this->genre->id . '/genre_trains');

        $data = \GuzzleHttp\json_decode($response->getContent(),true);

//        dump($data);

        $this->assertEquals(1,count($data['data']));
        $this->assertFalse(in_array(2,array_pluck($data['data'],'status')));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'   => [
                    '*' => [
                        'id',
                        'name',
                        'pic',
                        'title',
                        'status',
                        'start_at',
                        'end_at',
                        'address',
                        'discrible',
                        'is_collect',
                        'qr_code',
                        'creator'
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
