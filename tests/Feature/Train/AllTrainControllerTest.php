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
class AllTrainControllerTest extends BaseCase
{

    public function test_get_all_train_data_success()
    {

        $this->user->givePermissionTo('train');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/train/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'pic',
                    'title',
                    'status',
                    'start_at',
                    'end_at',
                    'address',
                    'discrible',
                    'qr_code',
                    'creator'
                ]
            ]);
    }

    public function test_get_all_train_data_403_fail()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/train/lists');

        $response->assertStatus(403);
    }


}
