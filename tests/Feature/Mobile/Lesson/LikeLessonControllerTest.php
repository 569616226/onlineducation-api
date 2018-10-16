<?php

namespace Tests\Feature\Mobile\Lesson;


use App\Models\GuestLesson;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class LikeLessonControllerTest extends BaseCase
{

    public function test_like_a_lesson_with_guest()
    {

        $lesson = factory(Lesson::class)->create([
            'type'       => 4,
            'nav_id'     => $this->nav->id,
            'out_like'   => 0,
            'teacher_id' => $this->teacher->id,
            'status'     => 3,
            'deleted_at' => null,
        ]);

        /*模拟观看*/
        $this->guest->lessons()->attach([
            $lesson->id => [
                'sections'     => [$this->section->id],
                'last_section' => $this->section->id,
                'is_like'      => 0,
                'is_collect'   => 0,
                'is_pay'       => 1,
                'add_date'     => now(),
                'collect_date' => now(),
            ]
        ]);
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/' . $lesson->id . '/like');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $guest_lesson = GuestLesson::where('lesson_id', $lesson->id)
            ->where('guest_id', $this->guest->id)
            ->first();

        $this->assertEquals(1, $guest_lesson->is_like);

    }

    public function test_like_a_lesson_with_guest_has_no_relation()
    {

        $lesson = factory(Lesson::class)->create([
            'type'       => 4,
            'nav_id'     => $this->nav->id,
            'out_like'   => 0,
            'teacher_id' => $this->teacher->id,
            'status'     => 3,
            'deleted_at' => null,
        ]);


        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/' . $lesson->id . '/like');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $guest_lesson = GuestLesson::where('lesson_id', $lesson->id)
            ->where('guest_id', $this->guest->id)
            ->first();

        $this->assertEquals(1, $guest_lesson->is_like);

    }


}
