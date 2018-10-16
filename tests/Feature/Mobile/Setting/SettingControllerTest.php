<?php

namespace Tests\Feature\Mobile\Setting;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SettingControllerTest extends BaseCase
{
    /**
     * @test
     */
    public function visit_mobile_setting_page()
    {

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/setting/' . $this->setting->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'id'              => $this->setting->id,
                'index_type'      => $this->setting->index_type,
                'index_count'     => $this->setting->index_count,
                'vip_send_seting' => $this->setting->vip_send_seting,
                'top_lesson_ids'  => $this->setting->top_lesson_ids,
                'top_train_ids'   => $this->setting->top_train_ids,
                'wechat_sub'      => $this->setting->wechat_sub,
            ]);


    }
}
