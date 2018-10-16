<?php

namespace Tests\Feature\Mobile\Lesson;


use App\Models\Lesson;
use App\Models\Nav;
use App\Models\Section;
use App\Models\Video;
use App\Models\VideoUrl;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class NavLessonControllerTest extends BaseCase
{

    public function test_visit_mobile_nav_type_1_lessons_paginate_data()
    {
        $lesson_4 = factory(Lesson::class)->create([
            'type'           => 4,
            'nav_id'         => $this->nav->id,
            'out_like'       => 0,
            'teacher_id'     => $this->teacher->id,
            'status'         => 3,
            'out_play_times' => 100,
            'created_at'     => now()->addDay(),
            'updated_at'     => now()->addDays(3),
        ]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/' . $this->nav->id . '/nav_lessons');

        $data = json_decode($response->getContent(), true);

//        dump($data['data']);

        $this->assertEquals(3, count($data['data']));
        $this->assertEquals($lesson_4->id, $data['data'][0]['id']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'    => [
                    '*' => [
                        'id',
                        'name',
                        'title',
                        'type',
                        'pictrue',
                        'price',
                        'like',
                        'is_like',
                        'is_collect',
                        'is_show',
                        'play_times',
                        'for',
                        'learning',
                        'describle',
                        'educational',
                        'teacher',
                        'sections'
                    ]

                ]
                , 'links' => [
                    'first', 'last', 'next', 'prev'
                ]
                , 'meta'  => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }

    public function test_visit_mobile_nav_type_2_lessons_paginate_data()
    {
        $nav = factory(Nav::class)->create([
            'order_type' => 2,
            'type'       => 0,
            'ordered'    => 1,
            'is_hide'    => 0,
        ]);

        factory(Lesson::class)->create([
            'type'           => 1,
            'nav_id'         => $nav->id,
            'out_like'       => 0,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 3,
            'price'          => 0.00,
            'out_play_times' => 300,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $lesson = factory(Lesson::class)->create([
            'type'           => 4,
            'nav_id'         => $nav->id,
            'out_like'       => 0,
            'teacher_id'     => $this->teacher->id,
            'status'         => 3,
            'out_play_times' => 500,
            'created_at'     => now(),
            'updated_at'     => now()->addDays(3),
        ]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/' . $nav->id . '/nav_lessons');

        $data = json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(2, count($data['data']));
        $this->assertEquals($lesson->id, $data['data'][0]['id']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'    => [
                    '*' => [
                        'id',
                        'name',
                        'title',
                        'type',
                        'pictrue',
                        'price',
                        'like',
                        'is_like',
                        'is_collect',
                        'is_show',
                        'play_times',
                        'for',
                        'learning',
                        'describle',
                        'educational',
                        'teacher',
                        'sections'
                    ]

                ]
                , 'links' => [
                    'first', 'last', 'next', 'prev'
                ]
                , 'meta'  => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }

    public function test_visit_mobile_nav_type_2_lessons_no_out_play_times_paginate_data()
    {

        $this->markTestSkipped( '当没有设置广告播放数据的时候，排序不会按照播放次数排序');
        $nav = factory(Nav::class)->create([
            'order_type' => 2,
            'type'       => 0,
            'ordered'    => 1,
            'is_hide'    => 0,
        ]);

        $lesson_4 = factory(Lesson::class)->create([
            'type'           => 1,
            'nav_id'         => $nav->id,
            'out_like'       => 0,
            'teacher_id'     => $this->teacher->id,
            'educational_id' => $this->educational->id,
            'status'         => 3,
            'price'          => 0.00,
            'out_play_times' => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $lesson = factory(Lesson::class)->create([
            'type'           => 4,
            'nav_id'         => $nav->id,
            'out_like'       => 0,
            'teacher_id'     => $this->teacher->id,
            'status'         => 3,
            'out_play_times' => 0,
            'created_at'     => now(),
            'updated_at'     => now()->addDays(3),
        ]);


        $video = factory(Video::class)->create([
            'fileId'  => '4564972818813772957',
            'name'    => '小溪-480p.mp4',
            'status'  => 2,
            'edk_key' => 'CiBs3r3EbiqHr678aL4/anThjNcJwAhfUW4988xjNwAbWxCO08TAChiaoOvUBCokMWUwYTU0MTMtOGE3YS00OGRjLTg0MTYtYjQ0OGUzMDc4MWMx',
        ]);

        factory(VideoUrl::class)->create([
            'url'      => 'http://1255334727.vod2.myqcloud.com/c5939c06vodgzp1255334727/21cb8ffc4564972818813772957/vuizU3DKKioA.mp4',
            'size'     => '10.48M',
            'duration' => 2000,
            'video_id' => $video->id,
        ]);

        factory(Section::class)->create([
            'lesson_id' => $lesson->id,
            'video_id'  => $video->id,
            'play_times'  =>500,
        ]);

        $video_1 = factory(Video::class)->create([
            'fileId'  => '4564972818813772957',
            'name'    => '小溪-480p.mp4',
            'status'  => 2,
            'edk_key' => 'CiBs3r3EbiqHr678aL4/anThjNcJwAhfUW4988xjNwAbWxCO08TAChiaoOvUBCokMWUwYTU0MTMtOGE3YS00OGRjLTg0MTYtYjQ0OGUzMDc4MWMx',
        ]);

        factory(VideoUrl::class)->create([
            'url'      => 'http://1255334727.vod2.myqcloud.com/c5939c06vodgzp1255334727/21cb8ffc4564972818813772957/vuizU3DKKioA.mp4',
            'size'     => '10.48M',
            'duration' => 2000,
            'video_id' => $video_1->id,
        ]);


       factory(Section::class)->create([
            'lesson_id' => $lesson_4->id,
            'video_id'  => $video_1->id,
            'play_times'  =>100,
        ]);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/lesson/' . $nav->id . '/nav_lessons');

        $data = json_decode($response->getContent(), true);

//        dump($data);

        $this->assertEquals(2, count($data['data']));
        $this->assertEquals($lesson->id, $data['data'][0]['id']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'    => [
                    '*' => [
                        'id',
                        'name',
                        'title',
                        'type',
                        'pictrue',
                        'price',
                        'like',
                        'is_like',
                        'is_collect',
                        'is_show',
                        'play_times',
                        'for',
                        'learning',
                        'describle',
                        'educational',
                        'teacher',
                        'sections'
                    ]

                ]
                , 'links' => [
                    'first', 'last', 'next', 'prev'
                ]
                , 'meta'  => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]);
    }


}
