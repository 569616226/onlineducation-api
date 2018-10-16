<?php

namespace Tests\Feature\Section;


use App\Models\Section;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateSectionControllerTest extends BaseCase
{


    public function test_update_a_section_success_()
    {
        $data = [
            'name'      => '第一节',
        ];

        $this->user->givePermissionTo('update_section');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/section/' . $this->section->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_section = Section::find($this->section->id);

        $this->assertEquals($data['name'],$update_section->name);
    }

    public function test_update_a_section_403_fail_()
    {
        $data = [
            'name'      => '第一节',
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/section/' . $this->section->id . '/update', $data);

        $response->assertStatus(403);

        $update_section = Section::find($this->section->id);

        $this->assertEquals( $this->section->name,$update_section->name);
    }


}
