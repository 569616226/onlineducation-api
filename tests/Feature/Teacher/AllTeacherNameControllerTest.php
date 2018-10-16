<?php

namespace Tests\Feature\Teacher;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllTeacherNameControllerTest extends BaseCase
{

    public function test_get_all_teacher_names_data_success_()
    {

        $this->user->givePermissionTo('teacher');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/teacher/names');

        $response->assertStatus(200)->assertJsonCount(2);
    }


}
