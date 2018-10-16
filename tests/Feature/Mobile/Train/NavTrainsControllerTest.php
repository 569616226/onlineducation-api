<?php

namespace Tests\Feature\Mobile\Train;

use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class NavTrainsControllerTest extends BaseCase
{

    public function test_visit_mobile_nav_trains_paginate_data()
    {

        factory(Train::class)->create([
            'status'            => 2,
            'collect_guest_ids' => [],
            'start_at'          => now(),
            'end_at'            => now()->addDay(),
            'nav_id'            => $this->train_nav->id
        ]);
        factory(Train::class)->create([
            'status'            => 1,
            'collect_guest_ids' => [],
            'start_at'          => now(),
            'end_at'            => now()->addDay(),
            'nav_id'            => $this->train_nav->id
        ]);
        factory(Train::class)->create([
            'status'            => 0,
            'collect_guest_ids' => [],
            'start_at'          => now(),
            'end_at'            => now()->addDay(),
            'nav_id'            => $this->train_nav->id
        ]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/train/' . $this->train_nav->id . '/nav_trains');

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
