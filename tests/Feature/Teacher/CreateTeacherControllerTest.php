<?php

namespace Tests\Feature\Teacher;

use App\Models\Teacher;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateTeacherControllerTest extends BaseCase
{
    
    public function test_create_a_teacher_success_()
    {
        $teacher_counts = Teacher::all()->count();

        $data = [
            'name'      => $this->faker->name,
            'office'    => $this->faker->name,
            'avatar'    => $this->faker->imageUrl(40,30),
            'describle' => $this->faker->name,
        ];

        $this->user->givePermissionTo('create_teacher');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/teacher/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_create_teachers = Teacher::all();

        $this->assertCount($teacher_counts+1,$after_create_teachers);
    }

    public function test_create_a_teacher_403_fail_()
    {
        $teacher_counts = Teacher::all()->count();

        $data = [
            'name'      => $this->faker->name,
            'office'    => $this->faker->name,
            'avatar'    => $this->faker->imageUrl(40,30),
            'describle' => $this->faker->name,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/teacher/', $data);

        $response->assertStatus(403);


        $after_create_teachers = Teacher::all();

        $this->assertCount($teacher_counts,$after_create_teachers);
    }


}
