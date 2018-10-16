<?php

namespace Tests\Feature\Section;


use App\Models\Section;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelSectionControllerTest extends BaseCase
{

    public function test_delete_a_section_success_()
    {

        $section_counts = $this->lesson->sections->count();

        $this->user->givePermissionTo('del_section');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','/api/admin/lesson/section/'.$this->section->id.'/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_sections = Section::where('lesson_id',$this->lesson->id)->get();

        $this->assertCount($section_counts-1, $update_sections);
    }

    public function test_delete_a_section_403_fail_()
    {
        $section_counts = $this->lesson->sections->count();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','/api/admin/lesson/section/'.$this->section->id.'/delete');

        $response->assertStatus(403);

        $update_sections = Section::where('lesson_id',$this->lesson->id)->get();

        $this->assertCount($section_counts, $update_sections);
    }

}
