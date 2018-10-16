<?php

namespace Tests\Feature\Setting;

use App\Models\Setting;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SetSignStartTimeControllerTest extends BaseCase
{

    public function test_set_sign_start_time_success_()
    {
        $data = [
            'sign_start_time' => 30,
        ];

        $this->user->givePermissionTo('set_sign_start_time');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST','api/admin/setting/'.$this->setting->id.'/set_sign_start_time',$data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($data['sign_start_time'],$update_setting->sign_start_time);
    }

    public function test_set_sign_start_time_403_fail_()
    {
        $data = [
            'sign_start_time' => 30,

        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST','api/admin/setting/'.$this->setting->id.'/set_sign_start_time',$data);

        $response->assertStatus(403);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($this->setting->sign_start_time,$update_setting->sign_start_time);
    }
}
