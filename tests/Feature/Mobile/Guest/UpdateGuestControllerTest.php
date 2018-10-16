<?php

namespace Tests\Feature\Mobile\Guest;

use App\Models\Guest;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateGuestControllerTest extends BaseCase
{

    public function test_update_guest_date_()
    {

        $data = [
            'name'    => $this->faker->name,
            'company' => $this->faker->name,
            'offer'   => $this->faker->name,
            'referee' => $this->faker->name,
            'phone'   => $this->faker->phoneNumber
        ];

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('POST', 'api/item/guest/' . $this->guest->id . '/update', $data);

        $response
            ->assertStatus(200)
            ->assertJson(['status' => true, 'message' => '操作成功']);

        $update_guest = Guest::getCache($this->guest->id, 'guests');

        $this->assertEquals($data['name'], $update_guest->name);
        $this->assertEquals($data['company'], $update_guest->company);
        $this->assertEquals($data['offer'], $update_guest->offer);
        $this->assertEquals($data['referee'], $update_guest->referee);
        $this->assertEquals($data['phone'], $update_guest->phone);
    }


}
