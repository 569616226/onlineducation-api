<?php

namespace Tests\Feature\Mobile\Guest;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SendSmsCodeControllerTest extends BaseCase
{

    public function test_send_a_sms_code_to_wechat_phone()
    {
        $this->markTestSkipped('跳过发送验证码测试');

        $data = [
            'phone' => 13412081338
        ];

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('POST', 'api/item/guest/send_sms/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '发送成功']);
    }

    public function test_send_a_sms_code_to_used_phone_()
    {

        $data = [
            'phone' => $this->guest->phone
        ];

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('POST', 'api/item/guest/send_sms/', $data);

        $response->assertStatus(200)->assertJson(['status' => false, 'message' => '此号码已被使用']);
    }



}
