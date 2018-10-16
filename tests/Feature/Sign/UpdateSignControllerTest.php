<?php

namespace Tests\Feature\Sign;

use App\Models\Sign;
use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateSignControllerTest extends BaseCase
{

    public function test_update_a_sign_success_()
    {
        $data = [
            'name' => $this->faker->name,
            'tel' => $this->faker->phoneNumber,
            'company' => $this->faker->company,
            'offer' => $this->faker->name,
            'referee' => $this->faker->name,
        ];

        $this->user->givePermissionTo('update_sign');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/sign/' . $this->sign->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_sign = Sign::find($this->sign->id);

        $this->assertEquals($data['name'],$update_sign->name);
        $this->assertEquals($data['tel'],$update_sign->tel);
        $this->assertEquals($data['company'],$update_sign->company);
        $this->assertEquals($data['offer'],$update_sign->offer);
        $this->assertEquals($data['referee'],$update_sign->referee);
    }

    public function test_update_a_sign_403_fail_()
    {
        $data = [
            'referee' => $this->faker->name,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/sign/' . $this->sign->id . '/update', $data);

        $response->assertStatus(403);

        $update_sign = Sign::find($this->sign->id);

        $this->assertEquals( $this->sign->referee,$update_sign->referee);

    }


}
