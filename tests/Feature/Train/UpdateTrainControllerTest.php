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
class UpdateTrainControllerTest extends BaseCase
{

    public function test_update_a_train_success()
    {

        $nav = factory(Nav::class)->create();
        $genre_ids = array_flatten(Genre::get(['id'])->toArray());

        $data = [
            'name'      => $this->faker->name,
            'title'     => $this->faker->name,
            'pic'       => $this->faker->imageUrl(400, 400),
            'start_at'  => '2018-09-17 07:52:43',
            'end_at'    => '2018-09-17 07:52:43',
            'address'   => $this->faker->address,
            'discrible' => $this->faker->name,
            'nav_id'    => $nav->id,
            'geren_ids' => $genre_ids,
        ];
        $this->user->givePermissionTo('update_train');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/train/' . $this->train->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_tran = Train::find( $this->train->id);

        $this->assertEquals($data['name'],$update_tran->name);
        $this->assertEquals($data['title'],$update_tran->title);
        $this->assertEquals($data['pic'],$update_tran->pic);
        $this->assertEquals($data['start_at'],$update_tran->start_at->toDateTimeString());
        $this->assertEquals($data['end_at'],$update_tran->end_at->toDateTimeString());
        $this->assertEquals($data['address'],$update_tran->address);
        $this->assertEquals($data['discrible'],$update_tran->discrible);
        $this->assertEquals($data['nav_id'],$update_tran->nav_id);
        $this->assertEquals($data['geren_ids'],array_values(array_sort($update_tran->genres->pluck('id')->toArray())));
    }

    public function test_update_a_train_403_fail_()
    {

        $nav = factory(Nav::class)->create();
        $genre_ids = array_flatten(Genre::get(['id'])->toArray());

        $data = [
            'name'      => $this->faker->name,
            'title'     => $this->faker->name,
            'pic'       => $this->faker->imageUrl(400, 400),
            'start_at'  => '2018-09-17 07:52:43',
            'end_at'    => '2018-09-17 07:52:43',
            'address'   => $this->faker->address,
            'discrible' => $this->faker->name,
            'nav_id'    => $nav->id,
            'geren_ids' => $genre_ids,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/train/' . $this->train->id . '/update', $data);

        $response->assertStatus(403);

        $update_tran = Train::find( $this->train->id);

        $this->assertEquals($this->train->name,$update_tran->name);
        $this->assertEquals($this->train->title,$update_tran->title);
        $this->assertEquals($this->train->pic,$update_tran->pic);
        $this->assertEquals($this->train->start_at->toDateTimeString(),$update_tran->start_at->toDateTimeString());
        $this->assertEquals($this->train->end_at->toDateTimeString(),$update_tran->end_at->toDateTimeString());
        $this->assertEquals($this->train->address,$update_tran->address);
        $this->assertEquals($this->train->discrible,$update_tran->discrible);
        $this->assertEquals($this->train->nav_id,$update_tran->nav_id);
        $this->assertEquals($this->train->genres->pluck('id')->toArray(),$update_tran->genres->pluck('id')->toArray());
    }

}
