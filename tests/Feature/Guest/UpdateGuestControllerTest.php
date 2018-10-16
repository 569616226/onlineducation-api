<?php

namespace Tests\Feature\Guest;


use App\Models\Guest;
use SMartins\PassportMultiauth\PassportMultiauth;
use Spatie\Permission\Models\Role;
use Tests\BaseCase;

class UpdateGuestControllerTest extends BaseCase
{

    public function test_update_a_guest_success_()
    {
        $role = factory(Role::class)->create();
        $data = [
            'name' => $this->faker->name,
            'phone'    => $this->faker->phoneNumber,
            'referee'  => $this->faker->name,
            'company'  => $this->faker->name,
            'offer'    => $this->faker->name,
            'role_ids' => [$role->id],
        ];

        $this->user->givePermissionTo('update_guest');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/guest/' . $this->guest->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_guest = Guest::find($this->guest->id);

        $this->assertFalse($update_guest->hasRole($role->name));
        $this->assertEquals($data['name'], $update_guest->name);
        $this->assertEquals($data['phone'], $update_guest->phone);
        $this->assertEquals($data['referee'], $update_guest->referee);
        $this->assertEquals($data['company'], $update_guest->company);
        $this->assertEquals($data['offer'], $update_guest->offer);
    }


    public function test_super_admin_update_a_guest_success_()
    {
        $role = factory(Role::class)->create();
        $data = [
            'name' => $this->faker->name,
            'phone'    => $this->faker->phoneNumber,
            'referee'  => $this->faker->name,
            'company'  => $this->faker->name,
            'offer'    => $this->faker->name,
            'role_ids' => [$role->id],

        ];
        $this->user->givePermissionTo('update_guest');
        $this->user->assignRole('super_admin');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/guest/' . $this->guest->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_guest = Guest::find($this->guest->id);


        $this->assertTrue($update_guest->hasRole($role->name));
        $this->assertEquals($data['name'], $update_guest->name);
        $this->assertEquals($data['phone'], $update_guest->phone);
        $this->assertEquals($data['referee'], $update_guest->referee);
        $this->assertEquals($data['company'], $update_guest->company);
        $this->assertEquals($data['offer'], $update_guest->offer);
    }

    public function test_update_a_guest_403_fail_()
    {
        $data = [
            'name'     => $this->faker->name,
            'phone'    => $this->faker->phoneNumber,
            'referee'  => $this->faker->name,
            'company'  => $this->faker->name,
            'offer'    => $this->faker->name,
            'role_ids' => [$this->role->id],

        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/guest/' . $this->guest->id . '/update', $data);

        $response->assertStatus(403);
    }


}
