<?php

namespace App\Http\Resources;

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
            'id'               => $this->id,
            'name'             => $this->name,
            'title'            => $this->title,
            'type'             => $this->lesson_type,
            'status'           => $this->lesson_status,
            'pictrue'          => $this->pictrue,
            'price'            => $this->lesson_price,
            'total_prices'     => $this->lesson_total_prices,
            'likes'            => $this->lesson_likes,
            'out_likes'        => $this->out_like,
            'collects'         => $this->collect_guests->count(),
            'discusses'        => $this->discusses->count(),
            'better_discusses' => $this->lesson_better_discusses,
            'is_top'           => $this->lesson_is_top,
            'students'         => $this->students->count(),
            'play_times'       => $this->lesson_play_times,
            'out_play_times'   => $this->out_play_times,
            'for'              => $this->for,
            'learning'         => $this->learning ? explode('--', $this->learning) : null,
            'describle'        => $this->describle,
            'educational'      => $this->educational,
            'nav'              => $this->nav,
            'teacher'          => $this->teacher,
            'genres'           => $this->genres,
            'sections'         => new SectionCollection($this->sections),
            'created_at'       => $this->created_at->toDateTimeString(),
        ];

    }
}
