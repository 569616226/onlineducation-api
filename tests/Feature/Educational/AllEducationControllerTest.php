<?php

namespace Tests\Feature\Educational;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllEducationControllerTest extends BaseCase
{

    public function test_get_all_eductions_success_()
    {

        $this->user->givePermissionTo('educational');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/education/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id', 'name', 'content', 'created_at'
                ]
            ]);
    }

    public function test_get_all_eductions_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/education/lists');

        $response->assertStatus(403);
    }

}

