<?php

namespace Tests\Feature\Mobile\Guest;

use App\Models\Guest;
use App\Models\Sign;
use Illuminate\Support\Facades\Cache;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CheckSmsCodeControllerTest extends BaseCase
{

    public function test_check_the_sms_code_with_sign_data()
    {

        $tel = $this->faker->phoneNumber;

        factory(Sign::class)->create([
            'tel'      => $tel,
            'train_id' => $this->train->id,
        ]);

        $sms_code = 'sms_code_' . $tel;

        Cache::put($sms_code, '00000', 10);

        $data = [
            'sms_code' => Cache::get($sms_code),
            'phone'    => $tel
        ];

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('POST', 'api/item/guest/' . $this->guest->id . '/check_tel/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '绑定成功']);
    }


    public function check_the_sms_code_no_sign_data()
    {

        $data = [
            'phone' => $this->faker->phoneNumber
        ];

        $sms_code = 'sms_code_' . $data['phone'];

        Cache::put($sms_code, '00000', 10);

        $data['sms_code'] = Cache::get($sms_code);

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('POST', 'api/item/guest/' . $this->guest->id . '/check_tel/', $data);

        $response
            ->assertStatus(200)
            ->assertJson(['status' => true, 'message' => '绑定成功']);
    }


}
