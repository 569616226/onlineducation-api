<?php

namespace Tests\Feature;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class  AllSystemMessageControllerTest extends BaseCase
{

    public function test_get_all_system_messages_data_success_()
    {
        $this->user->givePermissionTo('system_message');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/message/sys_list');

//        $data = \GuzzleHttp\json_decode($response->getContent(),true);

//        dump($data);

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'type',
                    'content',
                    'user',
                    'created_at'
                ]
            ]);
    }

    public function test_get_all_system_messages_data_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/message/sys_list');

        $response->assertStatus(403);
    }

}
