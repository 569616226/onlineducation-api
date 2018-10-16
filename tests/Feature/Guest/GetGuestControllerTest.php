<?php

namespace Tests\Feature\Guest;


use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetGuestControllerTest extends BaseCase
{

    public function test_visit_guest_data_page_success_()
    {
        $this->user->givePermissionTo('update_guest');
        $labels = implode('、', array_pluck($this->guest->labels->toArray(), 'name'));
        $label_ids = array_pluck($this->guest->labels->toArray(), 'id');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/guest/' . $this->guest->id);

        $role = $this->guest->roles->first();
        $response
            ->assertStatus(200)
            ->assertJson([
                'id'         => $this->guest->id,
                'name'       => $this->guest->name,
                'nickname'   => $this->guest->nickname,
                'phone'      => $this->guest->phone,
                'picture'    => $this->guest->picture,
                'referee'    => $this->guest->referee,
                'company'    => $this->guest->company,
                'offer'      => $this->guest->offer,
                'position'   => $this->guest->position,
                'role'       => [
                    'id'   => $role->id,
                    'display_name' => $role->display_name,
                ],
                'gender'     =>  $this->guest->guest_gender,
                'labels'     => $labels,
                'label_ids'  => $label_ids,
                'created_at' => $this->guest->created_at->toDateTimeString(),
            ]);
    }

    public function test_visit_guest_data_page_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/guest/' . $this->guest->id);

        $response->assertStatus(403);
    }

}
