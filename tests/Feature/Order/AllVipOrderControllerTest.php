<?php

namespace Tests\Feature\Order;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllVipOrderControllerTest extends BaseCase
{

    public function test_get_all_vip_order_data_success_()
    {
        $this->user->givePermissionTo('order');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','api/admin/order/vip_order_list');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'status',
                    'order_no',
                    'type',
                    'price',
                    'mouth',
                    'guest',
                    'pay_type',
                    'pay_date',
                    'start',
                    'end',
                    'order_type_id',
                    'created_at',
                ]
            ]);
    }

    public function test_get_all_vip_order_data_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','api/admin/order/vip_order_list');

        $response->assertStatus(403);
    }

}
