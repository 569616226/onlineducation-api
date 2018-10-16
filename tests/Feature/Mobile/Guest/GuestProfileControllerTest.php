<?php

namespace Tests\Feature\Mobile\Guest;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GuestProfileControllerTest extends BaseCase
{
   
    public function test_visit_guest_profile_data_page()
    {

        $messages_count = $this->guest->messages()->whereType(0)->get()->count();//未读消息

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/guest/profile');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                'guest'          => [
                    'id'           => $this->guest->id,
                    'name'         => $this->guest->name,
                    'nickname'     => $this->guest->nickname,
                    'phone'        => $this->guest->phone,
                    'picture'      => $this->guest->picture,
                    'position'     => $this->guest->position,
                    'gender'       => $this->guest->guest_gender,
                    'referee'      => $this->guest->referee,
                    'company'      => $this->guest->company,
                    'offer'        => $this->guest->offer,
                    'role'         => $this->guest->roles->first()->display_name,
                    'is_expire'    => $this->guest->guest_is_expire,//是否过期
                    'vip_name'     => $this->guest->guest_vip_name,
                    'is_vip'       => $this->guest->guest_is_vip,
                    'vip_end_date' => $this->guest->guest_vip_end_date,
                    'created_at'   => $this->guest->created_at->toDateTimeString(),
                ],
                'messages_count' => $messages_count

            ]);
    }


}
