<?php

namespace Tests\Feature;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SendMessageControllerTest extends BaseCase
{

    public function test_send_some_messages_success_()
    {

        $data = [
            'title'    => $this->faker->name,
            'content'  => $this->faker->name,
            'label_id' => $this->label->id,
        ];
        $this->user->givePermissionTo('send_message');
        $this->label->guests()->attach($this->guest->id);

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/message/send_messages', $data);

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'message' => '发送成功' . $this->label->guests->count() . '条消息']);
    }

    public function test_send_system_messages_success_()
    {

        $data = [
            'title'    => $this->faker->name,
            'content'  => $this->faker->name,
            'label_id' => $this->label->id,
            'url'  => $this->faker->imageUrl(),
            'picture'  => $this->faker->imageUrl(),
        ];
        $this->user->givePermissionTo('send_message');
        $this->label->guests()->attach($this->guest->id);

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/message/send_messages', $data);

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'message' => '发送成功' . $this->label->guests->count() . '条消息']);
    }

    public function test_send_some_messages_403_fail_()
    {

        $data = [
            'title'    => '发送一条测试消息',
            'content'  => '发送一条测试消息',
            'label_id' => $this->label->id,
        ];

        $this->label->guests()->attach($this->guest->id);

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/message/send_messages', $data);

        $response->assertStatus(403);
    }

}
