<?php

namespace Tests\Feature\Mobile\Lesson;


use App\Models\GuestLesson;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SearchLessonControllerTest extends BaseCase
{


    public function test_search_lessons_with_words_from_lesson_and_train_name()
    {
        factory(Lesson::class)->create([
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 3,
            'type'           => 1,
        ]);
        factory(Lesson::class)->create([
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 3,
            'type'           => 3,
        ]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/search?word=&page=1&pagesize=8');
        $response
            ->assertStatus(200)
            ->assertJsonCount(8)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'nav_id',
                    'status'
                ]
            ]);

    }
}
