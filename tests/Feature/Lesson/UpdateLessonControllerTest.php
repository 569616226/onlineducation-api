<?php

namespace Tests\Feature\Lesson;

use App\Http\Resources\SectionCollection;
use App\Models\Lesson;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class UpdateLessonControllerTest extends BaseCase
{

    public function test_update_a_lesson_success_()
    {
        $data = [
            'name'           => $this->faker->name,
            'title'          => $this->faker->name,
            'type'           => 1,
            'educational_id' => $this->educational->id,
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'pictrue'        => $this->faker->imageUrl(40,40),
            'price'          => 1.00,
            'section_ids'    => [$this->section->id],
            'is_frees'       => [$this->section->id],
            'genre_ids'      => [$this->genre->id],
            'learning'       => [123, 232, 4353],
            'for'            => '11-11-1',
            'describle'      => $this->faker->name,
        ];
        $this->user->givePermissionTo('update_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/' . $this->lesson->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_update_lesson = Lesson::find($this->lesson->id);

        $this->assertEquals($data['name'],$after_update_lesson->name);
        $this->assertEquals($data['title'],$after_update_lesson->title);
        $this->assertEquals($data['type'],$after_update_lesson->type);
        $this->assertEquals($data['educational_id'],$after_update_lesson->educational_id);
        $this->assertEquals($data['nav_id'],$after_update_lesson->nav_id);
        $this->assertEquals($data['teacher_id'],$after_update_lesson->teacher_id);
        $this->assertEquals($data['pictrue'],$after_update_lesson->pictrue);
        $this->assertEquals($data['price'],$after_update_lesson->price);
        $this->assertEquals($data['section_ids'],$after_update_lesson->sections->pluck('id')->toArray());
        $this->assertEquals($data['is_frees'],$after_update_lesson->sections()->where('is_free',1)->get()->pluck('id')->toArray());
        $this->assertEquals($data['genre_ids'],$after_update_lesson->genres->pluck('id')->toArray());
        $this->assertEquals($data['learning'],$after_update_lesson->lesson_learning);
        $this->assertEquals($data['for'],$after_update_lesson->for);
        $this->assertEquals($data['describle'],$after_update_lesson->describle);

    }


    public function test_update_a_type_4_lesson_success_()
    {
        $data = [
            'name'       => $this->faker->name,
            'title'      => $this->faker->name,
            'nav_id'     => $this->nav->id,
            'type'       => 4,
            'pictrue'    => $this->faker->imageUrl(40,40),
            'genre_ids'  => [$this->genre->id],
        ];
        $this->user->givePermissionTo('update_lesson');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/' . $this->lesson_4->id . '/update', $data);

        $response->assertStatus(200)->assertJson(['status' => true, 'message' => '操作成功']);

        $after_update_lesson = Lesson::find($this->lesson_4->id);

        $this->assertEquals($data['name'],$after_update_lesson->name);
        $this->assertEquals($data['title'],$after_update_lesson->title);
        $this->assertEquals($data['type'],$after_update_lesson->type);
        $this->assertEquals($data['nav_id'],$after_update_lesson->nav_id);
        $this->assertEquals($data['genre_ids'],$after_update_lesson->genres->pluck('id')->toArray());

    }


    public function test_update_a_lesson_403_fail_()
    {
        $data = [
            'name'           => $this->faker->name,
            'title'          => $this->faker->name,
            'type'           => 1,
            'educational_id' => $this->educational->id,
            'nav_id'         => $this->nav->id,
            'teacher_id'     => $this->teacher->id,
            'status'         => 1,
            'pictrue'        => $this->faker->imageUrl(40,40),
            'price'          => 1.00,
            'section_ids'    => [$this->section->id],
            'is_frees'       => [$this->section->id],
            'genre_ids'      => [$this->genre->id],
            'learning'       => [123, 232, 4353],
            'for'            => '11-11-1',
            'describle'      => $this->faker->name,
        ];

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('POST', '/api/admin/lesson/' . $this->lesson->id . '/update', $data);


        $response->assertStatus(403);
    }

}
