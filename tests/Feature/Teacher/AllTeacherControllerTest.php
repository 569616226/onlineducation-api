<?php

namespace Tests\Feature\Teacher;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllTeacherControllerTest extends BaseCase
{
    public function test_get_all_teacher_data_success_()
    {

        PassportMultiauth::actingAs($this->user);

        $this->user->givePermissionTo('teacher');

        $response = $this->json('GET', 'api/admin/teacher/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'office',
                    'avatar',
                    'created_at'
                ]
            ]);
    }

    public function test_get_all_teacher_data_403_fial_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/teacher/lists');

        $response->assertStatus(403);
    }


}
