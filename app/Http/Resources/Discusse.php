<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Discusse extends Resource
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
            'id'                => $this->id,
            'title'             => $this->name,
            'discusse_counts'   => $this->discusse_counts,
            'discusses'         => $this->lesson_discusses,
            'created_at'        => $this->created_at->toDateTimeString(),
        ];
    }
}
