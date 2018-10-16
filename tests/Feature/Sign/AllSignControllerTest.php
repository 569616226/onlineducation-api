<?php

namespace Tests\Feature\Sign;

use App\Models\Sign;
use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllSignControllerTest extends BaseCase
{

    public function test_get_all_sign_data_success_()
    {
        $this->user->givePermissionTo('sign');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/sign/'.$this->train->id.'/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'inser_type',
                    'referee',
                    'status',
                    'tel',
                    'company',
                    'offer',
                    'created_at',
                ]
            ]);
    }

    public function test_get_all_sign_data_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/sign/' . $this->train->id . '/lists');

        $response->assertStatus(403);
    }


}
