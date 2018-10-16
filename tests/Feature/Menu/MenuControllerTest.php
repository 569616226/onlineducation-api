<?php

namespace Tests\Feature;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class MenuControllerTest extends BaseCase
{

    public function test_return_all_menus_data_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET','/api/admin/menu/lists');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'menus' => [
                    '*' => [
                        'id',
                        'name',
                        'parent_id',
                        'icon',
                        'url',
                        'description',
                        'is_nav',
                        'order',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
        ;
    }
}
