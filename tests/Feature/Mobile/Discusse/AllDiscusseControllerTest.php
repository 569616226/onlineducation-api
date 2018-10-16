<?php

namespace Tests\Feature\Mobile\Discusse;

use App\Models\Discusse;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllDiscusseControllerTest extends BaseCase
{


    public function test_mobile_lesson_discusses_list_data()
    {
        factory(Discusse::class, 2)->create([
            'guest_id'  => $this->guest->id,
            'pid'       => 0,
            'is_better' => 1,
            'lesson_id' => $this->lesson->id,
            'content'   => $this->faker->name
        ]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/discusse/' . $this->lesson->id . '/lesson_discusses');

        $data = \GuzzleHttp\json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(3, count($data['data']));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                      'id',
                      'avatar',
                      'is_better',
                      'is_better',
                      'is_vip',
                      'content',
                      'lesson',
                      'guest',
                      'teacher_msg',
                      'created_at',
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
