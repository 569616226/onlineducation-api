<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetLessonControllerTest extends BaseCase
{

    public function test_get_a_lesson_data_success_()
    {
        $lesson_names = Lesson::where('id','<>',$this->lesson->id)->get()->pluck('name')->toArray();
        $this->user->givePermissionTo('update_lesson');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->lesson->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'lesson'       => [
                    'id'               => $this->lesson->id,
                    'name'             => $this->lesson->name,
                    'title'            => $this->lesson->title,
                    'type'             => $this->lesson->lesson_type,
                    'pictrue'          => $this->lesson->pictrue,
                    'students'         => $this->lesson->students->count(),
                    'price'            => $this->lesson->lesson_price,
                    'total_prices'     => $this->lesson->lesson_total_prices,
                    'likes'            => $this->lesson->lesson_likes,
                    'out_likes'        => $this->lesson->out_like,
                    'collects'         => $this->lesson->collect_guests->count(),
                    'discusses'        => $this->lesson->discusses->count(),
                    'better_discusses' => $this->lesson->lesson_better_discusses,
                    'for'              => $this->lesson->for,
                    'describle'        => $this->lesson->describle,
                    'learning'         => $this->lesson->lesson_learning,
                    'play_times'       => $this->lesson->lesson_play_times,
                    'out_play_times'   => $this->lesson->out_play_times,
                    'created_at'       => $this->lesson->created_at->toDateTimeString(),
                ],
                'lesson_names' => $lesson_names

            ]);
    }


    public function test_get_a_lesson_data_403_fail_()
    {


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/lesson/' . $this->lesson->id);

        $response->assertStatus(403);
    }


}
