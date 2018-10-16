<?php

namespace Tests\Feature\Label;

use App\Models\Label;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class LabelControllerTest extends BaseCase
{

    public function test_update_a_label_success_()
    {
        $data = [
            'name' =>$this->faker->name
        ];

        $this->user->givePermissionTo('update_label');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/label/' . $this->label->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_update_label = Label::find($this->label->id);

        $this->assertEquals($data['name'],$after_update_label->name);
    }


    public function test_update_a_label_403_fail_()
    {
        $data = [
            'name' =>$this->faker->name
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/label/' . $this->label->id . '/update', $data);

        $response->assertStatus(403);
    }


}
