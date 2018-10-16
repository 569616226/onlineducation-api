<?php

namespace Tests\Feature\Guest;


use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllGuestControllerTest extends BaseCase
{

    public function test_get_all_guests_data_success_()
    {

        $this->user->givePermissionTo('guest');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/guest/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'nickname',
                    'phone',
                    'picture',
                    'gender',
                    'labels',
                    'label_ids',
                    'name',
                    'company',
                    'role',
                    'offer',
                    'referee',
                    'position',
                    'created_at',
                ]
            ]);
    }

    public function test_get_all_guests_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/guest/lists');

        $response->assertStatus(403);
    }


}
