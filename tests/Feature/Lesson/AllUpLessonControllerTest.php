<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllUpLessonControllerTest extends BaseCase
{


    public function test_get_all_up_lesson_data_success_()
    {

        $this->user->givePermissionTo('lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/up_lesson_list');

        $response
            ->assertStatus(200)
            ->assertJsonCount(6)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'title',
                    'type',
                    'status',
                    'pictrue',
                    'price',
                    'students',
                    'nav',
                    'genres',
                    'created_at',
                ]
            ]);
    }


    public function test_get_all_up_lesson_data_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/up_lesson_list');

        $response->assertStatus(403);
    }

}
