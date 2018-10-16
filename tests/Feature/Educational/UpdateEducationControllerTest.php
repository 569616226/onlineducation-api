<?php

namespace Tests\Feature\Educational;

use App\Models\Educational;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateEducationControllerTest extends BaseCase
{

    public function test_update_a_education_success_()
    {
        $this->user->givePermissionTo('update_educational');
        $data = [
            'name'    => $this->faker->name,
            'content' => $this->faker->name
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/education/' . $this->educational->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $education_update = Educational::find($this->educational->id);

        $this->assertEquals($education_update->name, $data['name']);
        $this->assertEquals($education_update->content, $data['content']);

    }

    public function test_update_a_education_403_fail_()
    {

        $data = [
            'name'    => $this->faker->name,
            'content' => $this->faker->name
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/education/' . $this->educational->id . '/update', $data);

        $response->assertStatus(403);
    }


}

