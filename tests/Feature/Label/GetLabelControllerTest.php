<?php

namespace Tests\Feature\Label;

use App\Models\Label;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetLabelControllerTest extends BaseCase
{

    public function test_get_label_data_success_()
    {
        $this->user->givePermissionTo('update_label');
        $label_names = Label::where('id','<>',$this->label->id)->get()->pluck('name')->toArray();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/label/' . $this->label->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'label'       => [
                    'id'         => $this->label->id,
                    'name'       => $this->label->name,
                    'created_at' => $this->label->created_at->toDateTimeString()
                ],
                'label_names' => $label_names

            ]);
    }

    public function test_get_label_data_403_fail_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/label/' . $this->label->id);

        $response->assertStatus(403);
    }

}
