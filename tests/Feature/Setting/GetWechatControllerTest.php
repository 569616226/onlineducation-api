<?php

namespace Tests\Feature\Setting;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetWechatControllerTest extends BaseCase
{

    public function test_get_wechat_sub_success_()
    {
        $this->user->givePermissionTo('set_wechat_sub');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','api/admin/setting/'.$this->setting->id.'/get_wechat_sub');

        $response->assertStatus(200)->assertJsonStructure(['wechat_sub']);
    }


    public function test_get_wechat_sub_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','api/admin/setting/'.$this->setting->id.'/get_wechat_sub');

        $response->assertStatus(403);
    }



}
