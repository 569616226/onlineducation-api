<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Advert extends Resource
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
            'type'       => $this->type,
            'path'       => $this->path,
            'order'      => $this->order,
            'url'        => $this->url,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
