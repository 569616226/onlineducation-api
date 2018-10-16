<?php

namespace Tests\Feature\Vip;

use App\Models\Vip;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateVipControllerTest extends BaseCase
{


    public function test_create_a_vip_success_()
    {
        $vip_counts = Vip::all()->count();

        $data = [
            'name'       => $this->faker->name,
            'status'     => 1,
            'expiration' => now()->timestamp,
            'price'      => 1.00,
            'count'      => 1,
            'describle'  => $this->faker->name,
            'up'         => now()->timestamp,
            'down'       => now()->addDays(3)->timestamp,
        ];

        $this->user->givePermissionTo('create_vip');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/vip/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $this->assertCount($vip_counts+1,Vip::all());
    }


    public function test_create_a_vip_403_fail_()
    {
        $data = [
            'name'       => $this->faker->name,
            'status'     => 1,
            'expiration' => now()->timestamp,
            'price'      => 1.00,
            'count'      => 1,
            'describle'  => $this->faker->name,
            'up'         => now()->timestamp,
            'down'       => now()->addDays(3)->timestamp,
        ];


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/vip/', $data);


        $response->assertStatus(403);
    }


}
