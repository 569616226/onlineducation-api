<?php

namespace Tests\Feature\Mobile\Order;

use App\Models\Guest;
use App\Models\Order;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class OrderControllerTest extends BaseCase
{

    public function test_create_a_lesson_order()
    {
        //关联到微信无法直接测试 用为用的 overtrue/laravel-wechat": "^3.0" ，没有微信支付测试相关设置

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/order/' . $this->lesson->id . '/lesson');

        $response->assertStatus(200)
            ->assertJson([
                'status'  => true,
                'config' => [
                    'appId' => config('wechat.app_id'),
                ]
            ])->assertJsonStructure([
                'status' ,
                'config' => [
                    'appId',
                    'nonceStr',
                    'package',
                    'signType',
                ]
            ]);
    }


    public function test_create_a_vip_order_with_guest_is_vip()
    {

        factory(Order::class)->create([
            'guest_id'      => $this->guest->id,
            'name'          => $this->faker->name,
            'order_type_id' => $this->vip->id,
            'type'          => 2,
            'status'        => 1
        ]);
        //关联到微信无法直接测试 用为用的 overtrue/laravel-wechat": "^3.0" ，没有微信支付测试相关设置

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/order/' . $this->vip->id . '/vip');

//        dump($response->getContent());

        $response->assertStatus(200)
            ->assertJson([
            'status'  => true,
            'config' => [
                'appId' => config('wechat.app_id'),
            ]
        ])->assertJsonStructure([
            'status' ,
            'config' => [
                'appId',
                'nonceStr',
                'package',
                'signType',
            ]
        ]);
    }

    public function test_create_a_vip_order_with_guest_is_not_vip()
    {
        //关联到微信无法直接测试 用为用的 overtrue/laravel-wechat": "^3.0" ，没有微信支付测试相关设置
        $guest = factory(Guest::class)->create([
            'nickname' => 'guest',
            'openid'   => 'oDMF40TjhXnYMy0e5RLPX3ZU-kzw',
            'vip_id'   => null
        ]);


        PassportMultiauth::actingAs($guest);

        $response = $this->json('GET', 'api/item/order/' . $this->vip->id . '/vip');

        $response->assertStatus(200)
            ->assertJson([
            'status'  => true,
            'config' => [
                'appId' => config('wechat.app_id'),
            ]
        ])->assertJsonStructure([
                'status' ,
                'config' => [
                    'appId',
                    'nonceStr',
                    'package',
                    'signType',
                ]
            ]);

    }

}
