<?php

namespace Tests\Feature\Setting;

use App\Models\Setting;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class SetIndexTypeControllerTest extends BaseCase
{

    public function test_set_index_type_1_success_()
    {
        $this->user->givePermissionTo('set_index_type');
        $data = [
            'index_type'     => 1,
            'index_count'    => 4,
            'top_lesson_ids' => [$this->lesson->id],
            'top_train_ids'  => [$this->train->id],
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/setting/' . $this->setting->id . '/set_index_type', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($data['index_type'],$update_setting->index_type);
        $this->assertEquals($data['index_count'],$update_setting->index_count);
        $this->assertEquals($data['top_lesson_ids'],$update_setting->top_lesson_ids);
        $this->assertEquals($data['top_train_ids'],$update_setting->top_train_ids);
    }

    public function test_set_index_type_2_success_()
    {
        $this->user->givePermissionTo('set_index_type');
        $data = [
            'index_type'     => 2,
            'index_count'    => 4,
            'top_lesson_ids' => [$this->lesson->id],
            'top_train_ids'  => [$this->train->id],
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/setting/' . $this->setting->id . '/set_index_type', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($data['index_type'],$update_setting->index_type);
        $this->assertEquals($data['index_count'],$update_setting->index_count);
        $this->assertEquals($data['top_lesson_ids'],$update_setting->top_lesson_ids);
        $this->assertEquals($data['top_train_ids'],$update_setting->top_train_ids);
    }


    public function test_set_index_type_403_fail_()
    {
        $data = [
            'index_type'     => 1,
            'index_count'    => 4,
            'top_lesson_ids' => [$this->lesson->id],
            'top_train_ids'  => [$this->train->id],
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', 'api/admin/setting/' . $this->setting->id . '/set_index_type', $data);

        $response->assertStatus(403);

        $update_setting = Setting::find($this->setting->id);

        $this->assertEquals($this->setting->index_type,$update_setting->index_type);
        $this->assertEquals($this->setting->index_count,$update_setting->index_count);
        $this->assertEquals($this->setting->top_lesson_ids,$update_setting->top_lesson_ids);
        $this->assertEquals($this->setting->top_train_ids,$update_setting->top_train_ids);
    }


}
