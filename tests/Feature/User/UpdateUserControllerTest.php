<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use SMartins\PassportMultiauth\PassportMultiauth;
use Spatie\Permission\Models\Role;
use Tests\BaseCase;

class UpdateUserControllerTest extends BaseCase
{

    public function test_update_a_user_success_with_not_admin()
    {

        $role = factory(Role::class)->create();

        $data = [
            'nickname' => $this->faker->name,
            'frozen'   => 0,
            'gender'   => 1,
            'password' => '',
            'role_id'  => [$role->id]
        ];

        $this->user->givePermissionTo('update_user');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/user/' . $this->user->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_user = User::find($this->user->id);

        $this->assertEquals($data['nickname'], $update_user->nickname);
        $this->assertEquals($data['frozen'], $update_user->frozen);
        $this->assertEquals($data['gender'], $update_user->gender);
        $this->assertEquals($this->user->password, $update_user->password);
        $this->assertNotEquals($data['role_id'], $update_user->roles->pluck('id')->toArray());
    }

    public function test_update_a_user_success_with_admin()
    {

        $role = factory(Role::class)->create();

        $data = [
            'nickname' => $this->faker->name,
            'frozen'   => 0,
            'gender'   => 1,
            'password' => $this->faker->name,
            'role_id'  => [$role->id]
        ];

        $user = User::whereName('admin')->first();

        $user->givePermissionTo('update_user');

        PassportMultiauth::actingAs($user);

        $response = $this->json('POST', '/api/admin/user/' . $this->user->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_user = User::find($this->user->id);

        $this->assertEquals($data['nickname'], $update_user->nickname);
        $this->assertEquals($data['frozen'], $update_user->frozen);
        $this->assertEquals($data['gender'], $update_user->gender);
        $this->assertNotEquals($this->user->password, $update_user->password);
        $this->assertEquals($data['role_id'], $update_user->roles->pluck('id')->toArray());
    }


    public function test_update_a_user_403_fail_()
    {

        $data = [
            'nickname'  => $this->faker->name,
            'frozen'    => 0,
            'gender'    => 1,
            'password'  => $this->faker->name,
            'role_name' => [$this->role->name]
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/user/' . $this->user->id . '/update', $data);

        $response->assertStatus(403);

        $update_user = User::find($this->user->id);

        $this->assertEquals($this->user->nickname, $update_user->nickname);
        $this->assertEquals($this->user->frozen, $update_user->frozen);
        $this->assertEquals($this->user->gender, $update_user->gender);
        $this->assertEquals($this->user->password, $update_user->password);
        $this->assertEquals($this->user->roles->pluck('id')->toArray(), $update_user->roles->pluck('id')->toArray());
    }


}
