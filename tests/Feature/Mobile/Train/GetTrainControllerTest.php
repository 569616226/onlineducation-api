<?php

namespace Tests\Feature\Mobile\Train;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetTrainControllerTest extends BaseCase
{

    public function test_visit_mobile_a_train_page()
    {
        
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', '/api/item/train/' . $this->train->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'train' => [
                    'id'         => $this->train->id,
                    'name'       => $this->train->name,
                    'pic'        => $this->train->pic,
                    'title'      => $this->train->title,
                    'status'     => $this->train->train_status,
                    'start_at'   => $this->train->start_at->toDateTimeString(),
                    'end_at'     => $this->train->end_at->toDateTimeString(),
                    'address'    => $this->train->address,
                    'discrible'  => $this->train->discrible,
                    'qr_code'    => $this->train->qr_code,
                    'is_collect' => $this->train->train_collect,
                    'creator'    => $this->train->creator,
                    'created_at' => $this->train->created_at->toDateTimeString(),
                ]
            ]);
    }


    public function test_visit_no_login_mobile_a_train_page()
    {

        $response = $this->json('GET', '/api/item/train/' . $this->train->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'train' => [
                    'id'         => $this->train->id,
                    'name'       => $this->train->name,
                    'pic'        => $this->train->pic,
                    'title'      => $this->train->title,
                    'status'     => $this->train->train_status,
                    'start_at'   => $this->train->start_at->toDateTimeString(),
                    'end_at'     => $this->train->end_at->toDateTimeString(),
                    'address'    => $this->train->address,
                    'discrible'  => $this->train->discrible,
                    'qr_code'    => $this->train->qr_code,
                    'is_collect' => $this->train->train_collect,
                    'creator'    => $this->train->creator,
                    'created_at' => $this->train->created_at->toDateTimeString(),
                ]
            ]);
    }


}
