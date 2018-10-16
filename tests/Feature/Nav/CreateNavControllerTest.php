<?php

namespace Tests\Feature\Nav;

use App\Models\Nav;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateNavControllerTest extends BaseCase
{

    public function test_create_a_nav_success_()
    {
        $nav_counts = Nav::all()->count();

        $data = [
            'name'       => $this->faker->name,
            'order_type' => 1,
            'type'       => 1,
            'pictrue'    => $this->faker->imageUrl(),
            'lesson_ids' => [$this->lesson->id],
            'is_hide'    => 0,
        ];

        $this->user->givePermissionTo('create_nav');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/nav/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_create_navs = Nav::all();

        $this->assertCount($nav_counts+1,$after_create_navs);
    }


    public function test_create_a_nav_403_fail_()
    {

        $nav_counts = Nav::all()->count();
        $data = [
            'name'       => $this->faker->name,
            'order_type' => 1,
            'type'       => 1,
            'pictrue'    => $this->faker->imageUrl(),
            'lesson_ids' => [$this->lesson->id],
            'is_hide'    => 0,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/nav/', $data);

        $response->assertStatus(403);

        $after_create_navs = Nav::all();

        $this->assertCount($nav_counts,$after_create_navs);
    }


}
