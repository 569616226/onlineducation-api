<?php


namespace Tests\Feature\Educational;

use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class EditEducationControllerTest extends BaseCase
{

    public function test_get_a_eduction_data_success_()
    {

        $this->user->givePermissionTo('update_educational');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/education/' . $this->educational->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'id'         => $this->educational->id,
                'name'       => $this->educational->name,
                'content'    => $this->educational->content,
                'created_at' => $this->educational->created_at->toDateTimeString()
            ]);
    }

    public function test_get_a_eduction_data_403_fial_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/education/' . $this->educational->id);

        $response->assertStatus(403);
    }


}

