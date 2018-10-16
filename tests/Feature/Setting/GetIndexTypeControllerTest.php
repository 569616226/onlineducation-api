<?php

namespace Tests\Feature\Setting;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetIndexTypeControllerTest extends BaseCase
{

    public function test_get_index_type_success_()
    {
        $this->user->givePermissionTo('set_index_type');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/setting/' . $this->setting->id . '/get_index_type');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'index_type',
                'index_count',
                'top_lessons',
                'lessons',
                'top_trains',
                'trains',
            ]);
    }


    public function test_get_index_type_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/setting/' . $this->setting->id . '/get_index_type');

        $response->assertStatus(403);
    }


}
