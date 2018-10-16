<?php

namespace Tests\Feature\Nav;

use App\Models\Nav;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelNavControllerTest extends BaseCase
{

    public function test_success_delete_a_nav_()
    {
        $nav_no_lesson = factory(Nav::class)->create();

        $this->user->givePermissionTo('del_nav');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/' . $nav_no_lesson->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $nav_del_ids = Nav::onlyTrashed()->get()->pluck('id')->toArray();

        $this->assertTrue(in_array($nav_no_lesson->id,$nav_del_ids));
    }


    public function test_fail_delete_a_nav_()
    {
        $this->user->givePermissionTo('del_nav');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/' . $this->nav->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除有课程的栏目']);

        $nav_del_ids = Nav::onlyTrashed()->get()->pluck('id')->toArray();

        $this->assertFalse(in_array($this->nav->id,$nav_del_ids));
    }



    public function test_delete_a_nav_403_fail_()
    {
        $nav_no_lesson = factory(Nav::class)->create();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/' . $nav_no_lesson->id . '/delete');

        $response->assertStatus(403);

        $nav_del_ids = Nav::onlyTrashed()->get()->pluck('id')->toArray();

        $this->assertFalse(in_array($nav_no_lesson->id,$nav_del_ids));
    }


}
