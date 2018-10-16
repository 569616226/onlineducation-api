<?php

namespace Tests\Feature\Mobile\Lesson;

use App\Models\GuestLesson;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CollectLessonControllerTest extends BaseCase
{

    public function test_collect_a_lesson_with_guest_is_collect_()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/' . $this->lesson->id . '/collect');

        $response
            ->assertStatus(200)
            ->assertJson(['status' => true, 'message' => '操作成功']);

        $guest_lesson = GuestLesson::where('lesson_id', $this->lesson->id)
            ->where('guest_id', $this->guest->id)
            ->first();

        $this->assertEquals(0, $guest_lesson->is_collect);
    }

    public function test_collect_a_lesson_with_guest_not_collect_()
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
                'is_like'      => 1,
                'is_collect'   => 0,
                'is_pay'       => 1,
                'add_date'     => now(),
                'collect_date' => now(),
            ]
        ]);
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/' . $lesson->id . '/collect');

        $response
            ->assertStatus(200)
            ->assertJson(['status' => true, 'message' => '操作成功']);

        $guest_lesson = GuestLesson::where('lesson_id', $lesson->id)
            ->where('guest_id', $this->guest->id)
            ->first();

        $this->assertEquals(1, $guest_lesson->is_collect);
    }

    public function test_collect_a_lesson_with_guest_not_any_relation_()
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

        $response = $this->json('GET', 'api/item/lesson/' . $lesson->id . '/collect');

        $response
            ->assertStatus(200)
            ->assertJson(['status' => true, 'message' => '操作成功']);

        $guest_lesson = GuestLesson::where('lesson_id', $lesson->id)
            ->where('guest_id', $this->guest->id)
            ->first();

        $this->assertEquals(1, $guest_lesson->is_collect);
        $this->assertNotEmpty(1, $guest_lesson->collect_date);
    }


}
