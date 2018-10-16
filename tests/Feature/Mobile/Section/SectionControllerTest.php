<?php

namespace Tests\Feature\Mobile\Section;

use App\Models\Section;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SectionControllerTest extends BaseCase
{

    public function test_visit_a_section_data()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', '/api/item/lesson/section/' . $this->section->id);

        $data = \GuzzleHttp\json_decode($response->getContent(), true);

        $this->assertEquals(7, count($data['sections']));

        $section_play_times = Section::find($this->section->id)->play_times;

        $this->assertEquals($this->section->play_times + 1, $section_play_times);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'sections' => [
                    'id',
                    'name',
                    'lesson_name',
                    'is_free',
                    'play_times',
                    'video' => [
                        'id',
                        'name',
                        'status',
                        'url',
                        'size',
                        'duration',
                        'created_at'
                    ],
                    'created_at',
                ]
            ]);
    }

}
