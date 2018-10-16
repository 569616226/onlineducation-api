<?php

namespace Tests\Feature\Advert;

use App\Models\Advert;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class StoreAdvertControllerTest extends BaseCase
{

    public function test_update_advert_out_link_success_()
    {

        $advert_counts = Advert::all()->count();
        $data = [
            'name' => $this->faker->name,
            'path' => $this->faker->name,
            'order' => 3,
            'type' => 0,
            'url' => $this->faker->url,
        ];

        $this->user->givePermissionTo('create_advert');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/advert/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $advert_counts_after_store = Advert::all()->count();

        $this->assertEquals($advert_counts+1,$advert_counts_after_store);

    }

    public function test_update_advert_lesson_type_success_()
    {

        $advert_counts = Advert::all()->count();
        $data = [
            'name' => $this->faker->name,
            'path' => $this->faker->name,
            'order' => 3,
            'type' => 1,
            'url' => $this->lesson->id,
        ];
        $this->user->givePermissionTo('create_advert');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/advert/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $advert_counts_after_store = Advert::all()->count();

        $this->assertEquals($advert_counts+1,$advert_counts_after_store);

    }

    public function test_update_advert_403_fail_()
    {

        $data = [
            'name' => $this->faker->name,
            'path' => $this->faker->name,
            'order' => 3,
            'type' => 0,
            'url' => $this->faker->url,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/advert/', $data);

        $response->assertStatus(403);
    }

}
