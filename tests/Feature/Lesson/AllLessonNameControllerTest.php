<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllLessonNameControllerTest extends BaseCase
{

    public function test_get_all_lesson_names_success()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/names');

        $response->assertStatus(200)->assertJsonCount(15);
    }


}
