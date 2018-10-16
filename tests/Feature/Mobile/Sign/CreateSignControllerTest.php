<?php

namespace Tests\Feature\Mobile\Sign;

use App\Models\Sign;
use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateSignControllerTest extends BaseCase
{
    
    public function test_store_a_sign_is_not_stop()
    {

        $data = [

            'name'    => $this->faker->name,
            'tel'     => $this->faker->phoneNumber,
            'company' => $this->faker->company,
            'offer'   => $this->faker->name,
            'referee' => $this->faker->name,
        ];

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('POST', '/api/item/train/' . $this->train->id . '/sign/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $signs = Sign::where('train_id',$this->train->id)
            ->whereTel($data['tel'])
            ->whereName($data['name'])
            ->whereCompany($data['company'])
            ->whereOffer($data['offer'])
            ->whereReferee($data['referee'])
            ->get();

        $this->assertCount(1,$signs);
    }


    public function test_store_a_sign_is_stop()
    {
        $data = [

            'name'    => $this->faker->name,
            'tel'     => $this->faker->phoneNumber,
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

        $response = $this->json('POST', '/api/item/train/' . $train->id . '/sign/', $data);

        $response->assertStatus(200)->assertJson(['status' => false, 'message' => '此活动已经结束']);

        $signs = Sign::where('train_id',$this->train->id)
            ->whereTel($data['tel'])
            ->whereName($data['name'])
            ->whereCompany($data['company'])
            ->whereOffer($data['offer'])
            ->whereReferee($data['referee'])
            ->get();

        $this->assertCount(0,$signs);
    }

    public function test_store_a_sign_more_times()
    {

        $train = factory(Train::class)->create([
            'status'            => 1,
            'collect_guest_ids' => [],
            'start_at'          => now()->addMinutes(30),
            'end_at'            => now()->addDay(),
            'nav_id'            => $this->train_nav->id
        ]);

        $sign_data = [
            'status'     => 0,
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

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('POST', '/api/item/train/' . $train->id . '/sign/', $data);

        $response->assertStatus(200)->assertJson(['status' => false, 'message' => '您已经报名此活动，无需重复报名']);

        $signs = Sign::where('train_id',$train->id)
            ->whereTel($data['tel'])
            ->get();

        $this->assertCount(1,$signs);
    }



}
