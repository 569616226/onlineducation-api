<?php

namespace Tests\Feature\Teacher;

use App\Models\Teacher;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateTeacherControllerTest extends BaseCase
{

    public function test_update_a_teacher_success_()
    {
        $data = [
            'name'      => $this->faker->name,
            'office'    => $this->faker->name,
            'avatar'    => $this->faker->imageUrl(40,40),
            'describle' => $this->faker->name,
        ];

        $this->user->givePermissionTo('update_teacher');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/teacher/' . $this->teacher->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_teacher = Teacher::find( $this->teacher->id);

        $this->assertEquals($data['name'],$update_teacher->name);
        $this->assertEquals($data['office'],$update_teacher->office);
        $this->assertEquals($data['avatar'],$update_teacher->avatar);
        $this->assertEquals($data['describle'],$update_teacher->describle);
    }

    public function test_update_a_teacher_403_fail_()
    {
        $data = [
            'name'      => $this->faker->name,
            'office'    => $this->faker->name,
            'avatar'    => $this->faker->imageUrl(40,40),
            'describle' => $this->faker->name,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/teacher/' . $this->teacher->id . '/update', $data);

        $response->assertStatus(403);

        $update_teacher = Teacher::find( $this->teacher->id);

        $this->assertEquals($this->teacher->name ,$update_teacher->name);
        $this->assertEquals($this->teacher->office ,$update_teacher->office);
        $this->assertEquals($this->teacher->avatar ,$update_teacher->avatar);
        $this->assertEquals($this->teacher->describle ,$update_teacher->describle);
    }

}
