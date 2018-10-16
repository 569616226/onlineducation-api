<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SetLessonOutLikeControllerTest extends BaseCase
{

    public function test_set_out_likes_success_()
    {
        $data = [
            'out_like' => 5000,
        ];
        $this->user->givePermissionTo('set_out_likes');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/' . $this->lesson->id . '/set_out_like', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_set_out_likes = Lesson::find($this->lesson->id);

        $this->assertEquals($data['out_like'],$after_set_out_likes->out_like);
    }

    public function test_set_out_likes_403_fail_()
    {
        $data = [
            'out_like' => 5000,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/' . $this->lesson->id . '/set_out_like', $data);

        $response->assertStatus(403);

        $after_set_out_likes = Lesson::find($this->lesson->id);

        $this->assertEquals($this->lesson->out_like,$after_set_out_likes->out_like);
    }

}
