<?php

namespace Tests\Feature\Vip;

use App\Models\Vip;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DownVipControllerTest extends BaseCase
{

    public function test_down_a_vip_success_()
    {
        $this->user->givePermissionTo('down_vip');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $this->vip->id . '/down');

         $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $vip = Vip::getCache($this->vip->id,'vips');
        $this->assertEquals(3,$vip->status);
    }

    public function test_down_a_vip_403_fail()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $this->vip->id . '/down');

        $response->assertStatus(403);
    }


}
