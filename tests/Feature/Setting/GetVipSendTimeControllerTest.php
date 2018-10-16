<?php

namespace Tests\Feature\Setting;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetVipSendTimeControllerTest extends BaseCase
{

    public function test_get_vip_send_time_success_()
    {
        $this->user->givePermissionTo('set_vip_send_time');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','api/admin/setting/'.$this->setting->id.'/get_vip_send_time');

        $response->assertStatus(200)->assertJson([
            'vip_send_seting' => $this->setting->vip_send_seting
        ]);
    }


    public function test_get_vip_send_time_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','api/admin/setting/'.$this->setting->id.'/get_vip_send_time');

        $response->assertStatus(403);
    }



}
