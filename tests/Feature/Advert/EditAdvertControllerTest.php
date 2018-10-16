<?php

namespace Tests\Feature\Advert;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class EditAdvertControllerTest extends BaseCase
{

    public function test_get_edit_adverts_success_()
    {
        $this->user->givePermissionTo('update_advert');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/advert/' . $this->advert->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->advert->id,
                'name' => $this->advert->name,
                'path' => $this->advert->path,
                'url' => $this->advert->url,
                'type' => $this->advert->type,
                'order' => $this->advert->order,
                'created_at' => $this->advert->created_at->toDateTimeString()
            ]);
    }

    public function test_get_edit_adverts_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/advert/' . $this->advert->id);

        $response->assertStatus(403);
    }

}
