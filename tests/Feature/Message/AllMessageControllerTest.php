<?php

namespace Tests\Feature;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllMessageControllerTest extends BaseCase
{

    public function test_get_messages_data_success_()
    {
        $this->user->givePermissionTo('message');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/message/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'type',
                    'content',
                    'user',
                    'label',
                    'created_at'
                ]
            ]);
    }

    public function test_get_messages_data_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/message/lists');

        $response->assertStatus(403);
    }

}
