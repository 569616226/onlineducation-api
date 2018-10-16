<?php

namespace Tests\Feature\Discusse;

use App\Models\Discusse;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class StoreDiscusseControllerTest extends BaseCase
{

    public function test_create_discusse_success_()
    {

        $discusse_counts = Discusse::all()->count();

        $this->user->givePermissionTo('create_discusse');

        PassportMultiauth::actingAs($this->user);

        $data = [
            'content' => $this->faker->name
        ];

        $response = $this->json('POST', 'api/admin/discusse/' . $this->discusse->id, $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '回复成功']);

        $discusse_after_store_counts = Discusse::all()->count();
        $this->assertEquals($discusse_after_store_counts,$discusse_counts+1);

    }

    public function test_create_discusse_403_fial_()
    {

        $data = [
            'content' => $this->faker->name
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/discusse/' . $this->discusse->id, $data);

        $response->assertStatus(403);

    }


}
