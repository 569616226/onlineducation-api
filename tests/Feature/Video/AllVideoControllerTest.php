<?php

namespace Tests\Feature\Video;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllVideoControllerTest extends BaseCase
{

    public function test_get_all_video_data_success_()
    {
        $this->user->givePermissionTo('video');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/video/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'status', 'size', 'created_at', 'url', 'duration'
                    ]
                ]
            ]);
    }

    public function test_get_all_video_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/video/lists');

        $response->assertStatus(403);
    }


}
