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
class GetTrainControllerTest extends BaseCase
{


    public function test_get_a_train_data_success_()
    {

        $train_status_array = config('other.train_status_array');
        $this->user->givePermissionTo('update_train');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/train/' . $this->train->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'train' => [
                    'id'         => $this->train->id,
                    'name'       => $this->train->name,
                    'pic'        => $this->train->pic,
                    'title'      => $this->train->title,
                    'status'     => $train_status_array[$this->train->status],
                    'start_at'   => $this->train->start_at->toDateTimeString(),
                    'end_at'     => $this->train->end_at->toDateTimeString(),
                    'address'    => $this->train->address,
                    'discrible'  => $this->train->discrible,
                    'qr_code'    => $this->train->qr_code,
                    'nav' => $this->train->nav->id,
                    'genres' => $this->train->genres->pluck('id')->toArray(),
                    'creator'    => $this->train->creator,
                    'created_at' => $this->train->created_at->toDateTimeString(),
                ]
            ]);
    }

    public function test_get_a_train_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/train/' . $this->train->id);

        $response->assertStatus(403);
    }

}
