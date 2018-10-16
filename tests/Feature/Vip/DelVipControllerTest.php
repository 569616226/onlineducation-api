<?php

namespace Tests\Feature\Vip;

use App\Models\Vip;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelVipControllerTest extends BaseCase
{

    public function test_success_delete_a_vip()
    {
        $vip = factory(Vip::class)->create();

        $vip_counts = Vip::all()->count();

        $this->user->givePermissionTo('del_vip');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $vip->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $this->assertCount($vip_counts-1,Vip::all());
    }


    public function test_fail_delete_a_vip()
    {
        $vip_counts = Vip::all()->count();

        $this->user->givePermissionTo('del_vip');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $this->vip->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除有学员的vip']);

        $this->assertCount($vip_counts,Vip::all());
    }

    public function test_delete_a_vip_403_fail_()
    {
        $vip = factory(Vip::class)->create();

        $vip_counts = Vip::all()->count();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $vip->id . '/delete');

        $response->assertStatus(403);

        $this->assertCount($vip_counts,Vip::all());
    }
}
