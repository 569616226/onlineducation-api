<?php

namespace Tests\Feature\Mobile\Discusse;

use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class StoreDiscusseControllerTest extends BaseCase
{

    public function test_mobile_store_success_a_discusse()
    {
        $lesson_discusse_counts = $this->lesson->discusses->count();

        $data = [
            'content' => '使用再加上我的自定义词汇'
        ];

        PassportMultiauth::actingAs($this->guest);

        $response = $this->json('POST', 'api/item/discusse/' . $this->lesson->id, $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_store_lesson_discusses = Lesson::getCache($this->lesson->id,'lessons',['discusses'])->discusses;

        $this->assertCount($lesson_discusse_counts + 1, $after_store_lesson_discusses);

    }
}
