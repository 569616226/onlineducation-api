<?php

namespace Tests\Feature\Setting;

use App\Models\Setting;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SetWechatControllerTest extends BaseCase
{

    public function test_set_wechat_sub_success_()
    {
        $this->user->givePermissionTo('set_wechat_sub');
        $data = [
            'wechat_sub' => '微信关注回复',
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST','api/admin/setting/'.$this->setting->id.'/set_wechat_sub',$data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($data['wechat_sub'], $update_setting->wechat_sub);
    }


    public function test_set_wechat_sub_403_fail_()
    {
        $data = [
            'wechat_sub' => '微信关注回复',
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST','api/admin/setting/'.$this->setting->id.'/set_wechat_sub',$data);

        $response->assertStatus(403);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($this->setting->wechat_sub, $update_setting->wechat_sub);
    }



}
