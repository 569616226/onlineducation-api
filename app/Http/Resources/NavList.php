<?php

namespace App\Http\Resources;

use App\Models\Nav;
use Illuminate\Http\Resources\Json\Resource;

class NavList extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {


        $response_data = [
            'id'             => $this->id,
            'name'           => $this->name,
            'pictrue'        => $this->pictrue,
            'order_type'     => $this->order_type,
            'ordered'        => $this->ordered,
            'type'           => $this->type,
            'is_hide'        => $this->is_hide,
            'created_at'     => $this->created_at->toDateTimeString(),
        ];

        if($this->type){

            $nav_trains = $this->trains;

            if ($nav_trains) {

                $filter_trains = $nav_trains->filter(function ($item) {
                    return !in_array($item->id, $this->nav_train_ids ?? []) && in_array($item->status,[0,1]);
                })->all();

                $trains_is_nav = $nav_trains->filter(function ($item) {
                    return in_array($item->id, $this->nav_train_ids ?? []) && in_array($item->status,[0,1]);
                })->all();

                $train_names = array_pluck($trains_is_nav, 'name');

            } else {

                $filter_trains = [];
                $trains_is_nav = [];
                $train_names = [];
            }

            $response_data =  array_merge($response_data,[
                'train_names'   => $train_names,
                'trains_is_nav' =>  array_values($trains_is_nav),
                'trains'        => array_values($filter_trains),
            ]);

        }else{

            $nav_lessons = $this->lessons;

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

            $response_data =  array_merge($response_data,[
                'lesson_names'   => $lesson_names,
                'lessons_is_nav' => array_values($lessons_is_nav),
                'lessons'        => array_values($filter_lessons),
            ]);

        }

        return $response_data;

    }
}
