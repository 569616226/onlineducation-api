<?php

namespace Tests\Feature\Setting;

use App\Models\Setting;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SetVipSendTimeControllerTest extends BaseCase
{

    public function test_set_vip_send_time_success_()
    {
        $this->user->givePermissionTo('set_vip_send_time');
        $data = [

            'vip_send_seting' => 7,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/setting/' . $this->setting->id . '/set_vip_send_time', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($data['vip_send_seting'], $update_setting->vip_send_seting);
    }


    public function test_set_vip_send_time_403_fail_()
    {
        $data = [

            'vip_send_seting' => 7,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/setting/' . $this->setting->id . '/set_vip_send_time', $data);

        $response->assertStatus(403);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($this->setting->vip_send_seting, $update_setting->vip_send_seting);
    }


}
