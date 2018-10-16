<?php

namespace Tests\Feature\Advert;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllAdvertControllerTest extends BaseCase
{

    public function test_get_all_adverts_success_()
    {
        $this->user->givePermissionTo('advert');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/advert/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id', 'name', 'path', 'type', 'url', 'order', 'created_at'
                ]
            ]);
    }

    public function test_get_all_adverts_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/advert/lists');

        $response->assertStatus(403);
    }

}
