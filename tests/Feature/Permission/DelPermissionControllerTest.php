<?php

namespace Tests\Feature\Permission;

use SMartins\PassportMultiauth\PassportMultiauth;
use Spatie\Permission\Models\Permission;
use Tests\BaseCase;

class DelPermissionControllerTest extends BaseCase
{

    public function test_del_a_permission_success_()
    {
        $perm_counts = Permission::all()->count();


        $this->user->givePermissionTo('del_permission');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/permission/' . $this->permission->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_create_perms = Permission::all();

        $this->assertCount($perm_counts-1,$after_create_perms);
    }

    public function test_del_a_permission_403_fail_()
    {
        $perm_counts = Permission::all()->count();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/permission/' . $this->permission->id . '/delete');

        $response->assertStatus(403);

        $after_create_perms = Permission::all();

        $this->assertCount($perm_counts,$after_create_perms);
    }
}
