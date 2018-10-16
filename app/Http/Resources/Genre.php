<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Genre extends Resource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'created_at' => $this->created_at->toDateTimeString(),

        ];
    }
}
