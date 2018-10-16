<?php

namespace App\Http\Resources;

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
            'type'       => $this->lesson_type,
            'status'     => $this->lesson_status,
            'pictrue'    => $this->pictrue,
            'price'      => $this->lesson_price,
            'students'   => $this->students->count(),
            'nav'        => $this->nav,
            'genres'     => $this->genres,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
