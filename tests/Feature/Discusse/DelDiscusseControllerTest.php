<?php

namespace Tests\Feature\Discusse;

use App\Models\Discusse;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelDiscusseControllerTest extends BaseCase
{

    public function test_delelte_a_discusse_success_()
    {

        $this->user->givePermissionTo('del_discusse');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/discusse/' . $this->discusse->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $is_del = Discusse::onlyTrashed()->whereId($this->discusse->id)->get()->isEmpty();
        $this->assertFalse($is_del);

    }

    public function test_delelte_a_discusse_403_fail_()
    {


        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/discusse/' . $this->discusse->id . '/delete');

        $response->assertStatus(403);

    }

}
