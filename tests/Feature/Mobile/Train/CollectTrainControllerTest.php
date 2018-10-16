<?php

namespace Tests\Feature\Mobile\Train;

use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class TrainControllerTest extends BaseCase
{

    public function test_guest_collect_a_train()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/train/' . $this->train->id . '/collect');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '收藏成功']);

        $update_train = Train::find($this->train->id);

        $this->assertTrue(in_array($this->guest->id,$update_train->collect_guest_ids ?? []));

    }

    public function test_guest_uncollect_a_train()
    {
        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('GET', 'api/item/train/' . $this->train->id . '/uncollect');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '取消成功']);

        $update_train = Train::find($this->train->id);

        $this->assertFalse(in_array($this->guest->id,$update_train->collect_guest_ids ?? []));
    }

}
