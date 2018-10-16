<?php

namespace Tests\Feature\Video;

use App\Models\Video;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UploadUpdateDelVideoControllerTest extends BaseCase
{

    public function test_upload_a_video_success_()
    {

        $video_counts = Video::all()->count();

        $data = [
            'name'     => $this->faker->name,
            'fileId'   => $this->faker->unique()->uuid,
            'videoUrl' => $this->faker->url
        ];

        $this->user->givePermissionTo('upload_video');

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/admin/video/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_upload_videos = Video::all();

        $this->assertCount($video_counts+1,$after_upload_videos);

    }


    public function test_update_a_video_update_success()
    {

        $this->user->givePermissionTo('update_video');


        $data = [
            'name'     => $this->faker->name,
        ];

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/admin/video/' . $this->video->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_video = Video::find($this->video->id);

        $this->assertEquals($data['name'],$update_video->name);
    }


    public function test_success_delete_a_video_fail()
    {
        $video_counts  =  Video::all()->count();

        $this->user->givePermissionTo('del_video');

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/admin/video/' . $this->video->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => false, 'message' => '不能删除使用中的视频']);

        $after_upload_videos = Video::all();

        $this->assertCount($video_counts,$after_upload_videos);
    }

    public function test_success_delete_a_video()
    {

        $this->user->givePermissionTo('del_video');

        $video = factory(Video::class)->create();

        $video_counts  =  Video::all()->count();

        $response = $this->actingAs($this->user, 'api')

            ->json('GET', '/api/admin/video/' . $video->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_upload_videos = Video::all();

        $this->assertCount($video_counts-1,$after_upload_videos);
    }


    public function test_update_a_video_update_403_fail_()
    {

        $data = [
            'name'     => '222323'
        ];

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/admin/video/' . $this->video->id . '/update', $data);

        $response->assertStatus(403);
    }


    public function test_upload_a_video_403_fail_()
    {

        $data = [
            'name'     => $this->faker->name,
            'fileId'   => $this->faker->unique()->uuid,
            'videoUrl' => $this->faker->url
        ];

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/admin/video/', $data);

        $response->assertStatus(403);
    }


    public function test_success_delete_a_video_403_fail_()
    {

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/admin/video/' . $this->video->id . '/delete');

        $response->assertStatus(403);
    }


}
