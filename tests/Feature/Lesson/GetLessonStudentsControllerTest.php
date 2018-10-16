<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetLessonStudentsControllerTest extends BaseCase
{

    public function test_get_students_success_()
    {

        $this->guest->lessons()
            ->updateExistingPivot(
                $this->lesson->id,
                [
                    'sections'     => [$this->section->id],
                    'last_section' => $this->section->id
                ]
            );

        $this->user->givePermissionTo('lesson_student');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->lesson->id . '/student/lists');

        $data = \GuzzleHttp\json_decode($response->getContent(),true);
//
//        dump($data);

        $this->assertEquals(1,count($data['data']));

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nickname',
                        'phone',
                        'picture',
                        'gender',
                        'learned_per',
                        'add_date'
                    ]
                ]
            ]);

    }


    public function test_get_students_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->lesson->id . '/student/lists');

        $response->assertStatus(403);
    }


}
