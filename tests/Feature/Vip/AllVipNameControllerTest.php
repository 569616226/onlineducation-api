<?php

namespace Tests\Feature\Vip;

use App\Models\Vip;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class VipControllerTest extends BaseCase
{

    public function test_get_vip_names_success_()
    {
        $this->user->givePermissionTo('vip');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/names');

        $response->assertStatus(200)->assertJsonCount(4);
    }


    public function test_get_vip_names_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/names');

        $response->assertStatus(403);
    }

}
