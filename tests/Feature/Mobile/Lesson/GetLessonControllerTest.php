<?php

namespace Tests\Feature\Mobile\Lesson;


use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetLessonControllerTest extends BaseCase
{
    public function test_visit_lesson_datas_page()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', '/api/item/lesson/' . $this->lesson->id . '/edit');

        $data = \GuzzleHttp\json_decode($response->getContent(),true);

        $this->assertEquals(1,count($data['sections']));
        $this->assertEquals(1,count($data['sections']));

        $first_section = $this->lesson->sections()->first();
        $response
            ->assertStatus(200)
            ->assertJson([
                'id'          => $this->lesson->id,
                'name'        => $this->lesson->name,
                'title'       => $this->lesson->title,
                'type'        => $this->lesson->lesson_type,
                'pictrue'     => $this->lesson->pictrue,
                'price'       => $this->lesson->lesson_price,
                'like'        => $this->lesson->out_like > 0 ? $this->lesson->out_like : $this->lesson->lesson_likes,
                'is_like'     => $this->lesson->lesson_is_like,
                'is_collect'  => $this->lesson->lesson_is_collect,
                'is_show'     => $this->lesson->lesson_is_show,
                'play_times'  => $this->lesson->lesson_play_times,
                'for'         => $this->lesson->for,
                'learning'    => $this->lesson->lesson_learning,
                'describle'   => $this->lesson->describle,
                'educational' => [
                    'id'         => $this->lesson->educational->id,
                    'name'       => $this->lesson->educational->name,
                    'content'    => $this->lesson->educational->educational_content,
                    'created_at' => $this->lesson->educational->created_at->toDateTimeString(),
                ],
                'teacher'     => [
                    'name'      => $this->lesson->teacher->name,
                    'avatar'    => $this->lesson->teacher->avatar,
                    'office'    => $this->lesson->teacher->office,
                    'describle' => $this->lesson->teacher->describle,
                ],
                'sections'    => [
                    0 => [
                        'id'                    =>  $first_section->id,
                        'name'                  =>  $first_section->name,
                        'lesson_name'           =>  $first_section->lesson->name,
                        'is_free'               =>  $first_section->section_is_free,
                        'is_learned'            =>  $first_section->section_is_learned,
                        'is_last_learned'       =>  $first_section->section_is_last_learned,
                        'duration'              =>  $first_section->section_duration,
                    ]
                ]
            ]);
    }

    public function test_visit_down_lesson_datas_page()
    {
        $response = $this->actingAs($this->guest, 'mobile_api')
            ->json('GET', '/api/item/lesson/' . $this->down_lesson->id . '/edit');

        $response
            ->assertStatus(201)
            ->assertJson(['status' => false, 'msg' => '该课程已经下架']);
    }


    public function test_visit_del_lesson_datas_page()
    {
        $response = $this->actingAs($this->guest, 'mobile_api')
            ->json('GET', '/api/item/lesson/' . $this->del_lesson->id . '/edit');

        $response
            ->assertStatus(201)
            ->assertJson(['status' => false, 'msg' => '该课程资源已被删除']);
    }

}
