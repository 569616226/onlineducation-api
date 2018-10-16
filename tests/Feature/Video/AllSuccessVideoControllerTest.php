<?php

namespace Tests\Feature\Video;

use App\Models\Video;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllSuccessVideoControllerTest extends BaseCase
{

    public function test_get_all_success_video_data_success_()
    {

        $this->user->givePermissionTo('video');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/video/success_lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'status',
                        'size',
                        'url',
                        'duration',
                        'created_at',
                    ]
                ]
            ]);
    }

    public function test_get_all_success_video_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/video/success_lists');

        $response->assertStatus(403);
    }


}
