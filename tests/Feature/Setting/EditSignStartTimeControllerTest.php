<?php

namespace Tests\Feature\Setting;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class EditSignStartTimeControllerTest extends BaseCase
{


    public function test_edit_sign_start_time_success_()
    {
        $this->user->givePermissionTo('set_sign_start_time');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','api/admin/setting/'.$this->setting->id.'/sign_start_time');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->setting->id,
                'sign_start_time' =>$this->setting->sign_start_time
            ]);
    }

    public function test_edit_sign_start_time_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','api/admin/setting/'.$this->setting->id.'/sign_start_time');

        $response->assertStatus(403);
    }

}
