<?php

namespace Tests\Feature\Sign;

use App\Models\Sign;
use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetSignControllerTest extends BaseCase
{

    public function test_get_a_sign_data_success_()
    {

        $sign_status_array = config('other.sign_status_array');
        $sign_inset_type_array = config('other.sign_inset_type_array');

        PassportMultiauth::actingAs($this->user);
        $this->user->givePermissionTo('update_sign');
        $response = $this->json('GET', '/api/admin/sign/' . $this->sign->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'sign' => [
                    'id'         => $this->sign->id,
                    'name'       => $this->sign->name,
                    'inser_type' => $sign_inset_type_array[$this->sign->inser_type],
                    'referee'    => $this->sign->referee,
                    'status'     => $sign_status_array[$this->sign->status],
                    'tel'        => $this->sign->tel,
                    'company'    => $this->sign->company,
                    'created_at' => $this->sign->created_at->toDateTimeString(),
                    'offer'      => $this->sign->offer
                ]
            ]);
    }

    public function test_get_a_sign_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/sign/' . $this->sign->id);

        $response->assertStatus(403);
    }

}
