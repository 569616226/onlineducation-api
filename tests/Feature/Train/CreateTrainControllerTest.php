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
class CreateTrainControllerTest extends BaseCase
{


    public function test_create_a_train_success_()
    {
        $nav = factory(Nav::class)->create();
        $genre_ids = array_flatten(Genre::get(['id'])->toArray());

        $train_counts = Train::all()->count();

        $data = [
            'name'              => $this->faker->name,
            'title'             => $this->faker->name,
            'pic'               => $this->faker->imageUrl(400, 400),
            'start_at'          => '2018-09-17 07:52:43',
            'end_at'            => '2018-09-17 07:52:43',
            'address'           => $this->faker->address,
            'discrible'         => $this->faker->name,
            'nav_id'            => $nav->id,
            'collect_guest_ids' => [],
            'geren_ids'         => $genre_ids,
        ];

        $this->user->givePermissionTo('create_train');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/train/', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $this->assertCount($train_counts+1, Train::all());
    }

    public function test_create_a_train_403_fail_()
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

        $response = $this->json('POST', '/api/admin/train/', $data);

        $response->assertStatus(403);
    }


}
