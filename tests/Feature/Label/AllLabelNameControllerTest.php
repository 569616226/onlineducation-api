<?php

namespace Tests\Feature\Label;

use App\Models\Label;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class AllLabelNameControllerTest extends BaseCase
{

    public function test_get_all_label_names_data_success_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', 'api/admin/label/names');

        $response->assertStatus(200)->assertJsonCount(2);
    }

}
