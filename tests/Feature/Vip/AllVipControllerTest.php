<?php

namespace Tests\Feature\Vip;

use App\Models\Vip;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllVipControllerTest extends BaseCase
{


    public function test_get_all_vips_success_()
    {
        $this->user->givePermissionTo('vip');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                '*' => [
                    'id', 'name', 'status', 'expiration', 'price', 'count', 'describle', 'up', 'down', 'created_at'
                ]
            ]);
    }


    public function test_get_all_vips_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/lists');

        $response
            ->assertStatus(403);
    }


}
