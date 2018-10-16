<?php

namespace Tests\Feature\Section;


use App\Models\Section;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateSectionControllerTest extends BaseCase
{

    public function test_store_a_section_success_()
    {
        $section_counts = $this->lesson->sections->count();

        $data = [
            'name' => '第一节',
            'is_free' => 0,
            'video_id' => $this->video->id,
        ];

        $this->user->givePermissionTo('create_section');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST','/api/admin/lesson/'.$this->lesson->id.'/section',$data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_sections = Section::where('lesson_id',$this->lesson->id)->get();

        $this->assertCount($section_counts+1,$update_sections);
    }

    public function test_store_a_section_403_fail_()
    {
        $section_counts = $this->lesson->sections->count();

        $data = [
            'name' => '第一节',
            'is_free' => 0,
            'video_id' => $this->video->id,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST','/api/admin/lesson/'.$this->lesson->id.'/section',$data);

        $response->assertStatus(403);

        $update_sections = Section::where('lesson_id',$this->lesson->id)->get();

        $this->assertCount($section_counts, $update_sections);
    }

}
