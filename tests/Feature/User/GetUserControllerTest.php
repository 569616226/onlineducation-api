<?php

namespace Tests\Feature\User;

use App\Models\User;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetUserControllerTest extends BaseCase
{

    public function test_get_user_data_success_()
    {

        $user_names = User::where('id', '<>', $this->user->id)->get()->pluck('name')->toArray();

        $user_gender_array = config('other.user_gender_array');
        $this->user->givePermissionTo('update_user');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/' . $this->user->id . '/edit');

        $response
            ->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'nickname' => $this->user->nickname,
                    'frozen' => $this->user->frozen,
                    'role' => [
                        'id' => $this->role->id,
                        'display_name' => $this->role->name,
                    ],
                    'gender' => $user_gender_array[$this->user->gender],
                    'created_at' => $this->user->created_at->toDateTimeString(),
                ],
                'user_names' => $user_names
            ]);
    }


    public function test_get_user_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/user/' . $this->user->id . '/edit');

        $response->assertStatus(403);
    }

}
