<?php

namespace Tests\Feature\Discusse;

use App\Models\Discusse;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SetBetterDiscusseControllerTest extends BaseCase
{

    public function test_set_a_discusse_better_success_()
    {

        $this->user->givePermissionTo('set_discusse_better');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/discusse/' . $this->discusse->id . '/better');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_discusse = Discusse::find($this->discusse->id);
        $this->assertEquals($update_discusse->is_better,1);

    }

    public function test_set_a_discusse_better_403_fial_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/discusse/' . $this->discusse->id . '/better');

        $response->assertStatus(403);

    }

}
