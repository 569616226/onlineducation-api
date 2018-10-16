<?php

namespace Tests\Feature\Mobile\Message;

use App\Models\Message;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class MessageControllerTest extends BaseCase
{

    /**
     * @test
     */
    public function visit_mobile_messages_lists_page()
    {

        $message = factory(Message::class)->create([
            'user_id' => null,
            'title'   => $this->faker->name
        ]);

        $this->guest->messages()->attach($message->id);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/message/lists');

        $data = json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(1, count($data['data']));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links'  => [
                    'first', 'last', 'next', 'prev'
                ]
                , 'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }
}
