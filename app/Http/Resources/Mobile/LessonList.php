<?php

namespace App\Http\Resources\Mobile;

use App\Models\Nav;
use Illuminate\Http\Resources\Json\Resource;

class LessonList extends Resource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'title'      => $this->title,
            'type'       => $this->type,
            'pictrue'    => $this->pictrue,
            'play_times' => $this->out_play_times == 0 ? $this->lesson_play_times : $this->out_play_times,
            'duration'   => $this->lesson_duration,
            'sections'   => $this->lesson_last_section_name,
        ];
    }
}
