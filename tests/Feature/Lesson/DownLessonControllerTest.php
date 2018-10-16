<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DownLessonControllerTest extends BaseCase
{

    public function test_success_down_a_lesson_success_()
    {
        $lesson = factory(Lesson::class)->create([
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 3,
            'price'          => 0.00,
            'deleted_at'     => null,
        ]);
        $this->user->givePermissionTo('down_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $lesson->id . '/down');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_down_lesson = Lesson::find($lesson->id);

        $this->assertEquals(2,$after_down_lesson->status);
    }


    public function test_fail_down_a_lesson_success_()
    {
        $lesson = factory(Lesson::class)->create([
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
        ]);

        $this->nav->update([
            'nav_lesson_ids' => [$lesson->id]
        ]);
        $this->user->givePermissionTo('down_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $lesson->id . '/down');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能下架推荐课程']);

        $after_down_lesson = Lesson::find($lesson->id);

        $this->assertEquals($lesson->status,$after_down_lesson->status);
    }


    public function test_success_down_a_lesson_403_fial_()
    {
        $lesson = factory(Lesson::class)->create([
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 3,
            'price'          => 0.00,
            'deleted_at'     => null,
        ]);

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $lesson->id . '/down');

        $response->assertStatus(403);

        $after_down_lesson = Lesson::find($lesson->id);

        $this->assertEquals($lesson->status,$after_down_lesson->status);

    }



}
