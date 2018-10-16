<?php

namespace Tests\Feature\Mobile\Sign;

use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SignControllerTest extends BaseCase
{

    public function test_get_sign_by_tel_is_submit()
    {
        $data = [
            'tel'      => $this->sign->tel,
            'train_id' => $this->sign->train->id,
        ];
        PassportMultiauth::actingAs($this->guest);
        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/get_sign/', $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data'      => [
                    'name'    => $this->sign->name,
                    'referee' => $this->sign->referee,
                    'tel'     => $this->sign->tel,
                    'company' => $this->sign->company,
                    'offer'   => $this->sign->offer
                ],
                'is_submit' => true

            ]);
    }

    public function test_get_no_login_sign_by_tel_is_submit()
    {
        $data = [
            'tel'      => $this->sign->tel,
            'train_id' => $this->sign->train->id,
        ];
        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/get_sign/', $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data'      => [
                    'name'    => $this->sign->name,
                    'referee' => $this->sign->referee,
                    'tel'     => $this->sign->tel,
                    'company' => $this->sign->company,
                    'offer'   => $this->sign->offer
                ],
                'is_submit' => true

            ]);
    }


    public function test_get_sign_by_tel_not_submit()
    {
        $train = factory(Train::class)->create([
            'status'            => 0,
            'collect_guest_ids' => [],
            'start_at'          => now()->addMinutes(30),
            'end_at'            => now()->addDay(),
            'nav_id'            => $this->train_nav->id
        ]);

        $data = [

            'tel'      => $this->sign->tel,
            'train_id' => $train->id,
        ];
        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/get_sign/', $data);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data'      => [
                    'name'    => $this->sign->name,
                    'referee' => $this->sign->referee,
                    'tel'     => $this->sign->tel,
                    'company' => $this->sign->company,
                    'offer'   => $this->sign->offer
                ],
                'is_submit' => false

            ]);
    }


    public function test_get_sign_by_tel_no_data()
    {
        $data = [
            'tel' => 123,
        ];
        $response = $this
            ->actingAs($this->guest, 'mobile_api')
            ->json('POST', '/api/item/train/get_sign/', $data);

        $response->assertStatus(200)
            ->assertJson([
                'data'  => null,
                'is_submit' => false
            ]);
    }



}
