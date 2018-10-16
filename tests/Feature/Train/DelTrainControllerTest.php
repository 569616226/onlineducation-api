<?php

namespace Tests\Feature\Train;

use App\Models\Genre;
use App\Models\Nav;
use App\Models\Train;
use Illuminate\Http\UploadedFile;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

/**
 * Class TrainControllerTest
 * @package Tests\Feature
 */
class DelTrainControllerTest extends BaseCase
{

    public function test_success_delete_a_train()
    {

        $train_counts =  Train::all()->count();

        $this->user->givePermissionTo('del_train');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/train/' . $this->train->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $this->assertCount($train_counts-1, Train::all());
    }


    public function test_delete_a_train_with_stauts_1()
    {

        $train_status_1 = factory(Train::class)->create([
            'status' => 1,
            'nav_id' => $this->nav->id,
        ]);

        $train_counts =  Train::all()->count();

        $this->user->givePermissionTo('del_train');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/train/' . $train_status_1->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除进行中的活动']);

        $this->assertCount($train_counts, Train::all());
    }


    public function test_delete_a_train_with_stauts_2()
    {

        $train_status_2 = factory(Train::class)->create([
            'status' => 2,
            'nav_id' => $this->nav->id,
        ]);

        $train_counts =  Train::all()->count();

        $this->user->givePermissionTo('del_train');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/train/' . $train_status_2->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除已经结束的活动']);

        $this->assertCount($train_counts, Train::all());
    }

    public function test_delete_a_train_403_fail_()
    {

        $train_counts =  Train::all()->count();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/train/' . $this->train->id . '/delete');

        $response->assertStatus(403);

        $this->assertCount($train_counts, Train::all());

    }

}
