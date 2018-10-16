<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\Resource;

class Message extends Resource
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
            'title'      => $this->title,
            'type'       => $this->type,
            'content'    => html_entity_decode( stripslashes( $this->content ) ),
            'url'        => $this->url,
            'picture'    => $this->picture,
            'created_at' => $this->created_at->toDateTimeString(),
        ];


    }
}
