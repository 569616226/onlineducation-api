<?php

namespace Tests\Feature\Teacher;

use App\Models\Teacher;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetTeacherControllerTest extends BaseCase
{

    public function test_get_a_teacher_data_success()
    {
        $teacher_names = Teacher::where('id','<>',$this->teacher->id)->get()->pluck('name')->toArray();
        $this->user->givePermissionTo('update_teacher');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/teacher/' . $this->teacher->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'teacher'       => [
                    'id'         => $this->teacher->id,
                    'name'       => $this->teacher->name,
                    'office'     => $this->teacher->office,
                    'avatar'     => $this->teacher->avatar,
                    'created_at' => $this->teacher->created_at->toDateTimeString()
                ],
                'teacher_names' => $teacher_names
            ]);
    }


    public function test_get_a_teacher_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/teacher/' . $this->teacher->id);

        $response->assertStatus(403);
    }


}
