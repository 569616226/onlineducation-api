<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SetLessonOutPlayTimeControllerTest extends BaseCase
{

    public function test_set_out_play_times_success_()
    {
        $data = [
            'out_play_times' => 5000,
        ];

        $this->user->givePermissionTo('set_out_play_times');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/' . $this->lesson->id . '/set_out_play_times', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_set_out_play_times = Lesson::find($this->lesson->id);

        $this->assertEquals($data['out_play_times'],$after_set_out_play_times->out_play_times);
    }

    public function test_set_out_play_times_403_fail_()
    {
        $data = [
            'out_play_times' => 5000,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/' . $this->lesson->id . '/set_out_play_times', $data);

        $response->assertStatus(403);

        $after_set_out_play_times = Lesson::find($this->lesson->id);

        $this->assertEquals($this->lesson->out_play_times,$after_set_out_play_times->out_play_times);

    }



}
