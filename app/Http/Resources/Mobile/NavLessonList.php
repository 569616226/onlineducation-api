<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\Resource;

class NavLessonList extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'title'       => $this->title,
            'type'        => $this->lesson_type,
            'pictrue'     => $this->pictrue,
            'price'       => $this->lesson_price,
            'like'        => $this->out_like == 0 ? $this->lesson_likes : $this->out_like,
            'is_like'     => $this->lesson_is_like,
            'is_collect'  => $this->lesson_is_collect,
            'is_show'     => $this->lesson_is_show,
            'play_times'  => $this->out_play_times == 0 ? $this->lesson_play_times :  $this->out_play_times,
            'for'         => $this->for,
            'learning'    => $this->lesson_learning,
            'describle'   => $this->describle,
            'educational' => $this->educational,
            'teacher'     => new \App\Http\Resources\Mobile\Teacher($this->teacher),
            'sections'    => $this->sections->isEmpty() ? null : Section::collection($this->sections),
        ];
    }
}
