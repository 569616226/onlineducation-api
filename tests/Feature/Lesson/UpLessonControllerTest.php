<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpLessonControllerTest extends BaseCase
{

    public function test_up_a_lesson_success_()
    {
        $this->user->givePermissionTo('up_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->lesson->id . '/up');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_up_lesson = Lesson::find($this->lesson->id);

        $this->assertEquals(3,$after_up_lesson->status);
    }

    public function test_up_a_no_section_lesson_()
    {

        $lesson = factory(Lesson::class)->create([
            'type'           => 1,
            'nav_id'         => $this->nav->id,
            'out_like'       => 0,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 1,
            'price'          => 1.00,
            'created_at'     => now(),
        ]);

        $this->user->givePermissionTo('up_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $lesson->id . '/up');

        $response->assertStatus(200)->assertJson(['status' => false, 'message' => '不能上架没有章节的课程']);

        $after_up_lesson = Lesson::find($lesson->id);

        $this->assertNotEquals(3,$after_up_lesson->status);
    }

    public function test_up_a_lesson_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->lesson->id . '/up');

        $response->assertStatus(403);

        $after_up_lesson = Lesson::find($this->lesson->id);

        $this->assertEquals($this->lesson->status,$after_up_lesson->status);
    }



}
