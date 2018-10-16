<?php

namespace Tests\Feature\Educational;

use App\Models\Educational;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateEducationControllerTest extends BaseCase
{

    public function test_create_a_education_success_()
    {
        $education_counts = Educational::all()->count();

        $this->user->givePermissionTo('create_educational');
        $data = [
            'name'    => $this->faker->name,
            'content' => $this->faker->name
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/education/create', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_store_education_counts = Educational::all()->count();
        $this->assertEquals($education_counts+1,$after_store_education_counts);

    }

    public function test_create_a_education_403_fail_()
    {

        $data = [
            'name'    => $this->faker->name,
            'content' => $this->faker->name
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/education/create', $data);

        $response->assertStatus(403);
    }


}

