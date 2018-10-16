<?php

namespace Tests\Feature\Video;

use App\Models\Video;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetVideoControllerTest extends BaseCase
{
    public function test_get_video_data_success_()
    {

        $video_names = Video::where('id','<>',$this->video->id)->get()->pluck('name')->toArray();

        $this->user->givePermissionTo('update_video');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/video/' . $this->video->id);

        $response->assertStatus(200)
            ->assertJson([
                'video'       => [
                    'id'         => $this->video->id,
                    'name'       => $this->video->name,
                    'status'     => $this->video->video_status,
                    'url'        => $this->video->video_url,
                    'size'       => $this->video->video_size,
                    'duration'   => $this->video->video_duration,
                    'created_at' => $this->video->created_at->toDateTimeString(),
                ],
                'video_names' => $video_names

            ]);
    }

    public function test_get_video_data_403_fail_()
    {


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/video/' . $this->video->id);

        $response->assertStatus(403);
    }

}
