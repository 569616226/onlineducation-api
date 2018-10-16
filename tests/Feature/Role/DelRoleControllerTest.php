<?php
namespace Tests\Feature\Role;

use Spatie\Permission\Models\Role;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelRoleControllerTest extends BaseCase
{

    public function test_success_del_a_role_success_()
    {

        $this->user->givePermissionTo('del_role');
        $role = factory(Role::class)->create();
        $role_counts = Role::all()->count();
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/' . $role->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_create_roles = Role::all();

        $this->assertCount($role_counts-1, $after_create_roles);
    }


    public function test_del_a_role_201_fail_()
    {
        $role_counts = Role::all()->count();

        $this->user->givePermissionTo('del_role');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/' . $this->role->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除有账号的角色']);

        $after_create_roles = Role::all();

        $this->assertCount($role_counts, $after_create_roles);
    }

    public function test_del_a_role_403_fail_()
    {
        $role_counts = Role::all()->count();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/role/' . $this->role->id . '/delete');

        $response->assertStatus(403);

        $after_create_roles = Role::all();

        $this->assertCount($role_counts, $after_create_roles);
    }
}
