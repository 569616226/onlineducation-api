<?php

namespace Tests\Feature\Sign;

use App\Models\Sign;
use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelSignControllerTest extends BaseCase
{

    public function test_success_delete_a_sign()
    {

        $train = factory(Train::class)->create([
            'start_at' => now()->addMinutes(30),
            'end_at'   => now()->addDay(),
            'nav_id'   => $this->nav->id
        ]);

        $sign = factory(Sign::class)->create([
            'status'     => 0,
            'inser_type' => 0,
            'train_id'   => $train->id,
        ]);

        $sign_counts = Sign::all()->count();

        $this->user->givePermissionTo('del_sign');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/sign/' . $sign->id . '/delete');

//        dump(\GuzzleHttp\json_decode($response->getContent()));

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $this->assertCount($sign_counts-1,Sign::all());
    }


    public function test_delete_a_sign_403_fail()
    {

        $sign_counts = Sign::all()->count();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/sign/' . $this->sign->id . '/delete');

        $response->assertStatus(403);

        $this->assertCount($sign_counts,Sign::all());
    }


    public function test_delete_a_sign_with_inser_type_1()
    {
        $sign = factory(Sign::class)->create([
            'status'     => 1,
            'inser_type' => 1,
            'train_id'   => $this->train->id,
        ]);

        $sign_counts = Sign::all()->count();

        $this->user->givePermissionTo('del_sign');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/sign/' . $sign->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除报名用户']);

        $this->assertCount($sign_counts,Sign::all());
    }


    public function test_delete_a_sign_with_status_1()
    {
        $sign = factory(Sign::class)->create([
            'status'     => 1,
            'inser_type' => 0,
            'train_id'   => $this->train->id,
        ]);

        $sign_counts = Sign::all()->count();

        $this->user->givePermissionTo('del_sign');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/sign/' . $sign->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除已签到的用户']);

        $this->assertCount($sign_counts,Sign::all());
    }


    public function test_delete_a_sign_over_sign_time()
    {

        $train = factory(Train::class)->create([
            'start_at' => now()->addMinutes(1),
            'end_at'   => now()->addDay(),
            'nav_id'   => $this->nav->id
        ]);

        $sign = factory(Sign::class)->create([
            'status'     => 0,
            'inser_type' => 0,
            'train_id'   => $train->id,
        ]);

        $sign_counts = Sign::all()->count();

        $this->user->givePermissionTo('del_sign');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/sign/' . $sign->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '签到时间内不能删除用户']);

        $this->assertCount($sign_counts,Sign::all());
    }


}
