<?php

namespace Tests\Feature\User;

use App\Models\User;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class FrozenUserControllerTest extends BaseCase
{

    public function test_frozen_a_user_success_()
    {
        $this->user->givePermissionTo('frozen_user');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/' . $this->user->id . '/frozen');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_user = User::find( $this->user->id );

        $this->assertEquals(1,$update_user->frozen);
    }

    public function test_frozen_a_user_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/' . $this->user->id . '/frozen');

        $response->assertStatus(403);

        $update_user = User::find( $this->user->id );

        $this->assertEquals($this->user->frozen,$update_user->frozen);
    }


}
