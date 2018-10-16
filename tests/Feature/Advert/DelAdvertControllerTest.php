<?php

namespace Tests\Feature\Advert;

use App\Models\Advert;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelAdvertControllerTest extends BaseCase
{

    public function test_delete_advert_success_()
    {
        $this->user->givePermissionTo('del_advert');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/advert/' . $this->advert->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $is_del = Advert::onlyTrashed()->whereId($this->advert->id)->get()->isEmpty();
        $this->assertFalse($is_del);
    }

    public function test_delete_advert_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/advert/' . $this->advert->id . '/delete');

        $response->assertStatus(403);
    }

}
