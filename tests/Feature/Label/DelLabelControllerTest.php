<?php

namespace Tests\Feature\Label;

use App\Models\Label;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelLabelControllerTest extends BaseCase
{

    public function test_success_delete_a_label()
    {
        $this->user->givePermissionTo('del_label');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/label/' . $this->label->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $del_labels = Label::onlyTrashed()->whereId($this->label->id)->get();

        $this->assertCount(1,$del_labels);
    }


    public function test_fail_delete_a_label()
    {
        $label = factory(Label::class)->create();
        $label->guests()->sync([$this->guest->id]);
        $this->user->givePermissionTo('del_label');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/label/' . $label->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除有学员的标签']);

        $del_labels = Label::onlyTrashed()->whereId($label->id)->get();

        $this->assertCount(0,$del_labels);

    }


    public function test_delete_a_label_403_fail()
    {
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/label/' . $this->label->id . '/delete');

        $response->assertStatus(403);

        $del_labels = Label::onlyTrashed()->whereId($this->label->id)->get();

        $this->assertCount(0,$del_labels);

    }
}
