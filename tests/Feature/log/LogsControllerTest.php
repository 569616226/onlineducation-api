<?php

namespace Tests\Feature\Log;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class LogsControllerTest extends BaseCase
{

    public function test_get_all_logs_data_success_()
    {
        $this->user->givePermissionTo('log');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/log/lists');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user',
                        'revisionable_type',
                        'key',
                        'content',
                        'created_at'
                    ]
                ]
            ]);
    }

    public function test_get_all_logs_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/log/lists');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user',
                        'revisionable_type',
                        'key',
                        'content',
                        'created_at'
                    ]
                ]
            ]);
    }
}
