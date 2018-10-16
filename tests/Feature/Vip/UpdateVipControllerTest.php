<?php

namespace Tests\Feature\Vip;

use App\Models\Vip;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateVipControllerTest extends BaseCase
{


    public function test_update_a_vip_success()
    {
        $data = [
            'name'       => $this->faker->name,
            'expiration' => 1,
            'price'      => 1.00,
            'describle'  => $this->faker->name,
        ];

        $this->user->givePermissionTo('update_vip');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/vip/' . $this->vip->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_vip = Vip::find($this->vip->id);

        $this->assertEquals($data['name'], $update_vip->name);
        $this->assertEquals($data['expiration'], $update_vip->expiration);
        $this->assertEquals($data['price'], $update_vip->price);
        $this->assertEquals($data['describle'], $update_vip->describle);
    }

    public function test_update_a_vip_403_fail()
    {
        $data = [
            'name'       => $this->faker->name,
            'expiration' => 1,
            'price'      => 1.00,
            'describle'  => $this->faker->name,

        ];


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/vip/' . $this->vip->id . '/update', $data);

        $response->assertStatus(403);
    }


}
