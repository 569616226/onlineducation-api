<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class LessonControllerTest extends BaseCase
{

    public function test_success_delete_a_lesson_success_()
    {
        $this->user->givePermissionTo('del_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->down_lesson->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $del_lessons = Lesson::onlyTrashed()->whereId( $this->down_lesson->id)->get();

        $this->assertCount(1,$del_lessons);
    }


    public function test_fail_delete_a_lesson_is_nav_success_()
    {

        $this->user->givePermissionTo('del_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->lesson->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除推荐课程']);

        $del_lessons = Lesson::onlyTrashed()->whereId( $this->lesson->id)->get();

        $this->assertCount(0,$del_lessons);
    }


    public function test_fail_delete_a_lesson_is_up_success_()
    {
        $lesson = factory(Lesson::class)->create([
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,

            'status' => 3
        ]);
        $this->user->givePermissionTo('del_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $lesson->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除上架中课程']);

        $del_lessons = Lesson::onlyTrashed()->whereId( $lesson->id)->get();

        $this->assertCount(0,$del_lessons);
    }

    public function test_success_delete_a_lesson_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->down_lesson->id . '/delete');

        $response->assertStatus(403);

        $del_lessons = Lesson::onlyTrashed()->whereId( $this->down_lesson->id)->get();

        $this->assertCount(0,$del_lessons);
    }

}
