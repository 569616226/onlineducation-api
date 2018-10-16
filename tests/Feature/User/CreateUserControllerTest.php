<?php

namespace Tests\Feature\User;

use App\Models\User;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class CreateUserControllerTest extends BaseCase
{

    public function test_store_a_user_success_()
    {

        $user_counts = User::all()->count();

        $data = [
            'name'      => $this->faker->name,
            'nickname'  => $this->faker->firstName,
            'frozen'    => 0,
            'gender'    => 1,
            'password'  => 'admin',
            'role_id' => [$this->role->id]
        ];

        $this->user->givePermissionTo('create_user');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/user/store', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $this->assertCount($user_counts+1,User::all());
    }

    public function test_store_a_user_403_fail_()
    {
        $user_counts = User::all()->count();

        $data = [
            'name'      => $this->faker->name,
            'nickname'  => $this->faker->firstName,
            'frozen'    => 0,
            'gender'    => 1,
            'password'  => 'admin',
            'role_id' => [$this->role->id]
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/user/store', $data);

        $response->assertStatus(403);

        $this->assertCount($user_counts,User::all());
    }


}
