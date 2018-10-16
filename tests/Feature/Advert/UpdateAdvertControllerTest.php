<?php

namespace Tests\Feature\Advert;

use App\Models\Advert;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateAdvertControllerTest extends BaseCase
{

    public function test_update_advert_success_()
    {
        $data = [
            'name' => $this->faker->name,
            'path' => $this->faker->name,
            'order' => 3,
            'type' => 0,
            'url' => $this->faker->url,
        ];

        $this->user->givePermissionTo('update_advert');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/advert/' . $this->advert->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $advert_update = Advert::find($this->advert->id);

        $this->assertEquals($advert_update->name, $data['name']);
        $this->assertEquals($advert_update->type, $data['type']);
        $this->assertEquals($advert_update->order, $data['order']);
        $this->assertEquals($advert_update->path, $data['path']);
        $this->assertEquals($advert_update->url, $data['url']);
    }

    public function test_update_lesson_advert_success_()
    {
        $data = [
            'name' => $this->faker->name,
            'path' => $this->faker->name,
            'order' => 3,
            'type' => 1,
            'url' => $this->lesson->id,
        ];
        $this->user->givePermissionTo('update_advert');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/advert/' . $this->advert->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $advert_update = Advert::find($this->advert->id);

        $this->assertEquals($advert_update->name, $data['name']);
        $this->assertEquals($advert_update->type, $data['type']);
        $this->assertEquals($advert_update->order, $data['order']);
        $this->assertEquals($advert_update->path, $data['path']);
        $this->assertEquals($advert_update->url, env('MOBILE_URL') . '#/details/'.$data['url']);
    }

    public function test_update_train_type_lesson_advert_success_()
    {
        $data = [
            'name' => $this->faker->name,
            'path' => $this->faker->name,
            'order' => 3,
            'type' => 1,
            'url' => $this->lesson_4->id,
        ];
        $this->user->givePermissionTo('update_advert');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/advert/' . $this->advert->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $advert_update = Advert::find($this->advert->id);

        $this->assertEquals($advert_update->name, $data['name']);
        $this->assertEquals($advert_update->type, $data['type']);
        $this->assertEquals($advert_update->order, $data['order']);
        $this->assertEquals($advert_update->path, $data['path']);
        $this->assertEquals($advert_update->url, env('MOBILE_URL') . '#/InterviewDetail/'.$data['url']);
    }

    public function test_update_advert_403_fail_()
    {
        $data = [
            'name' => $this->faker->name,
            'path' => $this->faker->name,
            'order' => 3,
            'type' => 1,
            'url' => $this->faker->url,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/advert/' . $this->advert->id
            . '/update', $data);

        $response->assertStatus(403);
    }

}
