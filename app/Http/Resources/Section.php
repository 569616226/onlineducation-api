<?php

namespace App\Http\Resources;

use App\Models\Lesson;
use App\Models\Video;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Cache;

class Section extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'lesson_name'     => $this->lesson->name,
            'is_free'         => $this->is_free,
            'play_times'      => $this->lesson_play_times,
            'video'           => new \App\Http\Resources\Video( $this->video ),
            'created_at'      => $this->created_at->toDateTimeString(),
        ];
    }
}
