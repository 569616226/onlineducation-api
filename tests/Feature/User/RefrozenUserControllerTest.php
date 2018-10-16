<?php

namespace Tests\Feature\User;

use App\Models\User;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class RefrozenUserControllerTest extends BaseCase
{

    public function test_refrozen_a_user_success_()
    {
        $this->user->givePermissionTo('refrozen_user');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/' . $this->user->id . '/refrozen');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_user = User::find( $this->user->id );

        $this->assertEquals(0,$update_user->frozen);
    }

    public function test_refrozen_a_user_403_fail_()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/' . $this->user->id . '/refrozen');

        $response->assertStatus(403);

        $update_user = User::find( $this->user->id );

        $this->assertEquals($this->user->frozen,$update_user->frozen);
    }

}
