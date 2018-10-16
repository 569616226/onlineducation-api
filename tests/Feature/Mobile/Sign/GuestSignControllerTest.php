<?php

namespace Tests\Feature\Mobile\Sign;

use App\Models\Sign;
use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GuestSignControllerTest extends BaseCase
{

    public function test_guest_signed_has_data()
    {

        $data = [
            'name'    => $this->faker->name,
            'tel'     => $this->sign->tel,
            'company' => $this->faker->company,
            'offer'   => $this->faker->name,
            'referee' => $this->faker->name,
        ];

        PassportMultiauth::actingAs($this->guest);

        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/' . $this->train->id . '/guest_signed', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $sign = Sign::whereTel($data['tel'])->first();
        $this->assertEquals(1, $sign->status);
        $this->assertEquals($this->sign->inser_type, $sign->inser_type);

    }
    public function test_no_login_guest_signed_has_data()
    {

        $data = [
            'name'    => $this->faker->name,
            'tel'     => $this->sign->tel,
            'company' => $this->faker->company,
            'offer'   => $this->faker->name,
            'referee' => $this->faker->name,
        ];

        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/' . $this->train->id . '/guest_signed', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $sign = Sign::whereTel($data['tel'])->first();
        $this->assertEquals(1, $sign->status);
        $this->assertEquals($this->sign->inser_type, $sign->inser_type);

    }

    public function test_guest_signed_no_data()
    {

        $data = [
            'name'    => $this->faker->name,
            'tel'     => $this->faker->phoneNumber,
            'company' => $this->faker->company,
            'offer'   => $this->faker->name,
            'referee' => $this->faker->name,
        ];

        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/' . $this->train->id . '/guest_signed', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $sign = Sign::whereTel($data['tel'])->first();
        $this->assertEquals(1, $sign->status);
        $this->assertEquals(2, $sign->inser_type);

    }

    public function test_guest_signed_with_a_stop_train()
    {

        $data = [
            'name'    => $this->faker->name,
            'tel'     => $this->sign->tel,
            'company' => $this->faker->company,
            'offer'   => $this->faker->name,
            'referee' => $this->faker->name,
        ];

        $train = factory(Train::class)->create([
            'status'            => 2,
            'collect_guest_ids' => [],
            'start_at'          => now()->addMinutes(30),
            'end_at'            => now()->addDay(),
            'nav_id'            => $this->train_nav->id
        ]);

        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/' . $train->id . '/guest_signed', $data);

        $response->assertStatus(200)->assertJson(['status' => false, 'message' => '此活动已经结束']);

        $sign = Sign::where('train_id',$train->id)->whereTel($data['tel'])->first();
        $this->assertNotEquals(1, optional($sign)->status);

    }

    public function test_guest_signed_more_times()
    {

        $train = factory(Train::class)->create([
            'status'            => 1,
            'collect_guest_ids' => [],
            'start_at'          => now(),
            'end_at'            => now()->addDay(),
            'nav_id'            => $this->train_nav->id
        ]);

        $sign_data = [
            'status'     => 1,
            'tel'        => $this->faker->phoneNumber,
            'inser_type' => 0,
            'train_id'   => $train->id
        ];

        factory(Sign::class)->create($sign_data);

        $data = [
            'name'    => $this->faker->name,
            'tel'     => $sign_data['tel'],
            'company' => $this->faker->company,
            'offer'   => $this->faker->name,
            'referee' => $this->faker->name,
        ];


        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/' . $train->id . '/guest_signed', $data);

        $response->assertStatus(200)->assertJson(['status' => false, 'message' => '您已经签到此活动，无需重复签到']);

        $sign = Sign::whereTel($sign_data['tel'])->first();

        $this->assertEquals(1, $sign->status);

    }


}
