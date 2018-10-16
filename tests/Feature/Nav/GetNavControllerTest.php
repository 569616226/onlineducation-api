<?php

namespace Tests\Feature\Nav;

use App\Models\Nav;
use App\Models\Train;
use SMartins\PassportMultiauth\PassportMultiauth;
use Tests\BaseCase;

class GetNavControllerTest extends BaseCase
{

    /*
     * @test
     * */
    public function test_get_a_nav_lesson_data_success_()
    {
        $nav_names = Nav::where('id', '<>', $this->nav->id)->get()->pluck('name')->toArray();
        $nav_lessons = $this->lesson->lessons;

        if ($nav_lessons) {
            $filter_lessons = $nav_lessons->filter(function ($item) {
                return !in_array($item->id, $this->nav_lesson_ids ?? []) && $item->status == 3;
            })->all();

            $lessons_is_nav = $nav_lessons->filter(function ($item) {
                return in_array($item->id, $this->nav_lesson_ids ?? []) && $item->status == 3;
            })->all();

            $lesson_names = array_pluck($lessons_is_nav, 'name');
        } else {
            $filter_lessons = [];
            $lessons_is_nav = [];
            $lesson_names = [];
        }

        $this->user->givePermissionTo('update_nav');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/' . $this->nav->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'nav'       => [
                    'id'             => $this->nav->id,
                    'name'           => $this->nav->name,
                    'pictrue'        => $this->nav->pictrue,
                    'lessons'        => $filter_lessons,
                    'lesson_names'   => $lesson_names,
                    'lessons_is_nav' => $lessons_is_nav,
                    'ordered'        => $this->nav->ordered,
                    'is_hide'        => $this->nav->is_hide,
                    'type'           => $this->nav->type,
                    'order_type'     => $this->nav->order_type,
                    'created_at'     => $this->nav->created_at->toDateTimeString(),
                ],
                'nav_names' => $nav_names

            ]);
    }

    public function test_get_a_nav_tarin_data_success_()
    {
        $nav = factory(Nav::class)->create([
            'type'    => 1,
            'ordered' => 2,
            'is_hide' => 0,
        ]);

        factory(Train::class)->create([
            'status'             => 0,
            '.collect_guest_ids' => [],
            'start_at'           => now()->addMinutes(30),
            'end_at'             => now()->addDay(),
            'nav_id'             => $nav->id
        ]);
        $nav_names = Nav::where('id', '<>', $nav->id)->get()->pluck('name')->toArray();
        $nav_trains = $nav->trains;

        if ($nav_trains) {
            $filter_trains = $nav_trains->filter(function ($item) {
                return !in_array($item->id, $nav->nav_train_ids ?? []) && $item->status == 3;
            })->all();

            $trains_is_nav = $nav_trains->filter(function ($item) {
                return in_array($item->id, $nav->nav_train_ids ?? []) && $item->status == 3;
            })->all();

            $train_names = array_pluck($trains_is_nav, 'name');
        } else {
            $filter_trains = [];
            $trains_is_nav = [];
            $train_names = [];
        }

        $this->user->givePermissionTo('update_nav');
        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/' . $nav->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                'nav'       => [
                    'id'            => $nav->id,
                    'name'          => $nav->name,
                    'pictrue'       => $nav->pictrue,
                    'trains'        => $filter_trains,
                    'train_names'   => $train_names,
                    'trains_is_nav' => $trains_is_nav,
                    'ordered'       => $nav->ordered,
                    'is_hide'       => $nav->is_hide,
                    'type'          => $nav->type,
                    'order_type'    => $nav->order_type,
                    'created_at'    => $nav->created_at->toDateTimeString(),
                ],
                'nav_names' => $nav_names

            ]);
    }

    public function test_get_a_nav_data_403_fial_()
    {

        PassportMultiauth::actingAs($this->user);

        $response = $this->json('GET', '/api/admin/nav/' . $this->nav->id);

        $response->assertStatus(403);
    }

}
