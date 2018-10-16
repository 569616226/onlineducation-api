<?php

namespace Tests\Feature\Guest;


use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SetLabelToGuestControllerTest extends BaseCase
{

    public function test_add_tow_labels_to_guest_success_()
    {
        $data = [
            'label_ids' => [
                $this->label->id,
            ]
        ];
        $this->user->givePermissionTo('add_label');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/guest/' . $this->guest->id . '/set_label', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $label_ids = $this->guest->labels->pluck('id')->toArray();
        $this->assertTrue(in_array($this->label->id, $label_ids));
    }

    public function test_add_tow_labels_to_guest_403_fail_()
    {
        $data = [
            'label_ids' => [
                $this->label->id,
            ]
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/guest/' . $this->guest->id . '/set_label', $data);

        $response->assertStatus(403);
    }

}
