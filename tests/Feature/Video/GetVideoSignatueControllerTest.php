<?php

namespace Tests\Feature\Video;

use App\Models\Video;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetVideoSignatueControllerTest extends BaseCase
{

    public function test_get_video_signatue_success_()
    {
        $this->user->givePermissionTo('upload_video');
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/admin/video/get_video_signature');

        $response->assertStatus(200);
    }


    public function test_get_video_signatue_403_fail_()
    {

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/admin/video/get_video_signature');

        $response->assertStatus(403);
    }

}
