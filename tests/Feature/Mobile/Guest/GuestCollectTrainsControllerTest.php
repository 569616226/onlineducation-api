<?php

namespace Tests\Feature\Mobile\Guest;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GuestCollectTrainsControllerTest extends BaseCase
{

    public function test_guest_collect_trains_data_()
    {

        $this->train->update(['collect_guest_ids' => [$this->guest->id]]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/guest/my_collect_train');

        $data = \GuzzleHttp\json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(1, count($data['data']));

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
