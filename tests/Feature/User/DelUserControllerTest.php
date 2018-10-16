<?php

namespace Tests\Feature\User;

use App\Models\User;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelUserControllerTest extends BaseCase
{


    public function test_del_a_user_success_()
    {
        $user_counts = User::all()->count();

        $this->user->givePermissionTo('del_user');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/' . $this->user->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $this->assertCount($user_counts-1,User::all());


    }

    public function test_del_a_user_403_fail_()
    {
        $user_counts = User::all()->count();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/' . $this->user->id . '/delete');

        $response->assertStatus(403);

        $this->assertCount($user_counts,User::all());
    }


}
