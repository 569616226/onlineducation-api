<?php

namespace Tests\Feature\Nav;

use App\Models\Nav;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateNavControllerTest extends BaseCase
{

    public function test_update_a_lesson_nav_success_()
    {
        $data = [
            'name'           => $this->faker->name,
            'order_type'     => 1,
            'type'           => 1,
            'pictrue'        => $this->faker->imageUrl(),
            'nav_lesson_ids' => [$this->lesson->id],
            'is_hide'        => 0,
        ];

        $this->user->givePermissionTo('update_nav');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/nav/' . $this->nav->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_nav = Nav::find($this->nav->id);

        $this->assertEquals($data['name'], $update_nav->name);
        $this->assertEquals($data['order_type'], $update_nav->order_type);
        $this->assertEquals($data['type'], $update_nav->type);
        $this->assertEquals($data['pictrue'], $update_nav->pictrue);
        $this->assertEquals($data['nav_lesson_ids'], $update_nav->nav_lesson_ids);
        $this->assertEquals($data['is_hide'], $update_nav->is_hide);
    }

    public function test_update_a_train_nav_success_()
    {
        $data = [
            'name'           => $this->faker->name,
            'order_type'     => 1,
            'type'           => 1,
            'pictrue'        => $this->faker->imageUrl(),
            'nav_train_ids' => [$this->train->id],
            'is_hide'        => 0,
        ];

        $this->user->givePermissionTo('update_nav');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/nav/' . $this->nav->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_nav = Nav::find($this->nav->id);

        $this->assertEquals($data['name'], $update_nav->name);
        $this->assertEquals($data['order_type'], $update_nav->order_type);
        $this->assertEquals($data['type'], $update_nav->type);
        $this->assertEquals($data['pictrue'], $update_nav->pictrue);
        $this->assertEquals($data['nav_train_ids'], $update_nav->nav_train_ids);
        $this->assertEquals($data['is_hide'], $update_nav->is_hide);
    }

    public function test_update_a_nav_403_fail_()
    {
        $data = [
            'name'       => $this->faker->name,
            'order_type' => 1,
            'type'       => 1,
            'pictrue'    => $this->faker->imageUrl(),
            'lesson_ids' => [$this->lesson->id],
            'is_hide'    => 0,
        ];


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/nav/' . $this->nav->id . '/update', $data);

        $response->assertStatus(403);
    }


}
