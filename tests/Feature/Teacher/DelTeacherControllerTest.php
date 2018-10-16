<?php

namespace Tests\Feature\Teacher;

use App\Models\Teacher;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelTeacherControllerTest extends BaseCase
{


    public function test_success_delete_a_teacher_()
    {

        $teacher = factory(Teacher::class)->create();
        $teacher_counts = Teacher::all()->count();
        $this->user->givePermissionTo('del_teacher');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/teacher/' . $teacher->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $this->assertCount($teacher_counts-1,Teacher::all());


    }

    public function test_fail_delete_a_teacher_()
    {

        $teacher_counts = Teacher::all()->count();

        $this->user->givePermissionTo('del_teacher');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/teacher/' . $this->teacher->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除有课程关联的讲师']);

        $this->assertCount($teacher_counts,Teacher::all());

    }

    public function test_delete_a_teacher_403_fial_()
    {


        $teacher = factory(Teacher::class)->create();

        $teacher_counts = Teacher::all()->count();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/teacher/' . $teacher->id . '/delete');

        $response->assertStatus(403);

        $this->assertCount($teacher_counts,Teacher::all());
    }

}
