<?php

namespace App\Http\Resources\Mobile;

use App\Http\Resources\SectionCollection;
use App\Models\Educational;
use App\Models\Nav;
use App\Models\Teacher;
use Illuminate\Http\Resources\Json\Resource;

class Lesson extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
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
            'like'        => $this->mobile_lesson_likes,
            'is_like'     => $this->lesson_is_like,
            'is_collect'  => $this->lesson_is_collect,
            'is_show'     => $this->lesson_is_show,
            'play_times'  => $this->mobile_lesson_play_times,
            'for'         => $this->for,
            'learning'    => $this->lesson_learning,
            'describle'   => $this->describle,
            'educational' => new \App\Http\Resources\Educational($this->educational),
            'teacher'     => new \App\Http\Resources\Mobile\Teacher($this->teacher),
            'sections'    => $this->sections->isEmpty() ? null : Section::collection($this->sections),
        ];
    }
}