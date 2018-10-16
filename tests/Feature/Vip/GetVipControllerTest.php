<?php

namespace Tests\Feature\Vip;

use App\Models\Vip;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetVipControllerTest extends BaseCase
{

    public function test_visit_vip_datas_page_success_()
    {
        $vip_names = Vip::where('id', '<>', $this->vip->id)->get()->pluck('name')->toArray();
        $vip_status_array = config('other.vip_status_array');
        $this->user->givePermissionTo('update_vip');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $this->vip->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'vip'       => [
                    'id'         => $this->vip->id,
                    'name'       => $this->vip->name,
                    'status'     => $vip_status_array[$this->vip->status],
                    'expiration' => $this->vip->expiration,
                    'price'      => $this->vip->price,
                    'count'      => $this->vip->count,
                    'describle'  => $this->vip->describle,
                    'up'         => $this->vip->start ? $this->vip->start->toDateTimeString() : null,
                    'down'       => $this->vip->end ? $this->vip->end->toDateTimeString() : null,
                    'created_at' => $this->vip->created_at->toDateTimeString()
                ],
                'vip_names' => $vip_names
            ]);
    }

    public function test_visit_vip_datas_page_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/vip/' . $this->vip->id);

        $response->assertStatus(403);
    }


}
