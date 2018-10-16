<?php

namespace Tests\Feature\Video;

use App\Models\Video;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllVideoNameControllerTest extends BaseCase
{

    public function test_get_all_video_names_success_()
    {
        $this->user->givePermissionTo('video');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/video/names');

        $response->assertStatus(200)->assertJsonCount(7);
    }

    public function test_get_all_video_names_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/video/names');

        $response->assertStatus(403);
    }


}
