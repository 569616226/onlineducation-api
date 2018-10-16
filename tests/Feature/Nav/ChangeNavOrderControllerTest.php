<?php

namespace Tests\Feature\Nav;

use App\Models\Nav;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class ChangeNavOrderControllerTest extends BaseCase
{

    public function test_change_nav_order_success_()
    {
        $nav = factory(Nav::class)->create([
            'ordered' => 3
        ]);

        $data = [
            'nav_datas' => [
                [
                    'id'      => $this->nav->id,
                    'ordered' => 2,
                ], [
                    'id'      => $nav->id,
                    'ordered' => 1,
                ],
            ]

        ];

        $this->user->givePermissionTo('change_nav_order');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/nav/change_nav_order', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_this_nav = Nav::find($this->nav->id);
        $this->assertEquals($data['nav_datas'][0]['ordered'],$update_this_nav->ordered);

        $update_nav = Nav::find($nav->id);
        $this->assertEquals($data['nav_datas'][1]['ordered'],$update_nav->ordered);
    }

    public function test_change_nav_order_403_fail_()
    {
        $nav = factory(Nav::class)->create([
            'ordered' => 3
        ]);
        $data = [
            'nav_datas' => [
                [
                    'id'      => $this->nav->id,
                    'ordered' => 2,
                ], [
                    'id'      => $nav->id,
                    'ordered' => 1,
                ],
            ]

        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/nav/change_nav_order', $data);

        $response->assertStatus(403);

        $update_this_nav = Nav::find($nav->id);
        $this->assertEquals(3,$update_this_nav->ordered);
    }

}
