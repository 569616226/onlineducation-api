<?php

namespace Tests\Feature\Label;

use App\Models\Label;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateLabelControllerTest extends BaseCase
{

    public function test_store_a_label_success_()
    {

        $counts = Label::all()->count();

        $data = [
            'name' =>$this->faker->name
        ];
        $this->user->givePermissionTo('create_label');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/label/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_create_lebals = Label::all();
        $this->assertCount($counts+1,$after_create_lebals);

    }

    public function test_store_a_label_403_fail_()
    {

        $data = [
            'name' =>$this->faker->name
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/label/', $data);

        $response->assertStatus(403);

    }


}
