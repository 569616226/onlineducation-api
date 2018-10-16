<?php


namespace Tests\Feature\Educational;

use App\Models\Educational;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class DelEducationControllerTest extends BaseCase
{
    
    public function test_success_del_a_education_()
    {
        $this->user->givePermissionTo('del_educational');

        $educational = factory(Educational::class)->create();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/education/' . $educational->id . '/delete');

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $is_del = Educational::onlyTrashed()->whereId($educational->id)->get()->isEmpty();
        $this->assertFalse($is_del);

    }

 
    public function test_fail_del_a_eduction_()
    {

        $this->user->givePermissionTo('del_educational');

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/education/' . $this->educational->id . '/delete');

        $response->assertStatus(201)->assertJson(['status' => false, 'message' => '不能删除有课程的教务模版']);

        $is_del = Educational::onlyTrashed()->whereId($this->educational->id)->get()->isEmpty();
        $this->assertTrue($is_del);
    }

    public function test_del_a_educational_402_fail()
    {
        $educational = factory(Educational::class)->create();

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/education/' . $educational->id . '/delete');

        $response->assertStatus(403);
    }

}

