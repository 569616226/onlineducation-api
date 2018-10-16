<?php

namespace Tests\Feature\Lesson;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllLessonControllerTest extends BaseCase
{

    public function test_get_all_lesson_data_success()
    {
        $this->user->givePermissionTo('lesson');
        PassportMultiauth::actingAs($this->user);
        $response = $this->json('GET', '/api/admin/lesson/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                'nav'     => [
                    '*' => [
                        'id', 'name', 'pictrue', 'created_at',
                    ]

                ],
                'lessons' => [
                    '*' => [
                        'id', 'name', 'title', 'type', 'nav', 'status', 'pictrue', 'students', 'price', 'created_at',
                    ]
                ]
            ]);
    }

    public function test_get_all_lesson_data_403_fail()
    {
        PassportMultiauth::actingAs($this->user);
        $response = $this->json('GET', '/api/admin/lesson/lists');

        $response->assertStatus(403);
    }


}
