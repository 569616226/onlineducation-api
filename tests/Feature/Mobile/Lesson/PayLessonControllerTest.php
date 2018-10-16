<?php

namespace Tests\Feature\Mobile\Lesson;

use App\Models\GuestLesson;
use App\Models\Lesson;
use App\Models\Order;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class PayLessonControllerTest extends BaseCase
{

    public function test_visit_guest_pay_orders_paginate_data()
    {
        factory(Order::class)->create([
            'guest_id'      => $this->guest->id,
            'name'          => $this->faker->name,
            'order_type_id' => $this->lesson->id,
            'type'          => 1,
            'status'        => 1
        ]);

        factory(Order::class)->create([
            'guest_id'      => $this->guest->id,
            'name'          => $this->faker->name,
            'order_type_id' => $this->lesson->id,
            'type'          => 2,
            'status'        => 1
        ]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/pay_orders');

        $data = json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(2,count($data['data']));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'     => [
                    '*' => [
                        'id',
                        "name",
                        "order_no",
                        "status",
                        "price",
                        "order_type_id",
                        "pictrue",
                        "title",
                        "created_at",
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
