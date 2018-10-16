<?php

namespace Tests\Feature\Vip;

use App\Models\Vip;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpVipControllerTest extends BaseCase
{


    public function test_up_a_vip()
    {

        $this->user->givePermissionTo('up_vip');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $this->vip->id . '/up');

         $response->assertStatus(200)
             ->assertJson(['status' => true, 'message' => '操作成功']);

         $vip = Vip::getCache($this->vip->id,'vips');
         $this->assertEquals(1,$vip->status);
    }

    public function test_up_a_vip_403_fail()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $this->vip->id . '/up');

         $response->assertStatus(403);
    }


}
