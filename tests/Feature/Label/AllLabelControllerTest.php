<?php

namespace Tests\Feature\Label;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllLabelControllerTest extends BaseCase
{
    public function test_get_all_label_data_success_()
    {
        $this->user->givePermissionTo('label');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/label/lists');

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'created_at'
                ]
            ]);
    }

    public function test_get_all_label_data_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/label/lists');

        $response->assertStatus(403);
    }
}
