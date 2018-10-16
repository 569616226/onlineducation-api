<?php

namespace Tests\Feature\Discusse;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllDiscussesControllerTest extends BaseCase
{

    public function test_get_all_discusses_success_()
    {
        $this->user->givePermissionTo('discusse');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/discusse/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'discusse_counts',
                        'discusses' => [
                            '*' => [
                                'avatar',
                                'content',
                                'created_at',
                                'guest',
                                'id',
                                'is_better',
                                'is_vip',
                                'lesson',
                                'teacher_msg'
                            ],
                        ],
                        'created_at'
                    ],
                ]
            ]);
    }

    public function test_get_all_discusses_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/discusse/lists');

        $response->assertStatus(403);
    }

}
