<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Revision extends Resource
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
            'id'                => $this->id,
            'revisionable_type' => $this->log_type,
            'user'              => $this->log_user_name,
            'key'               => $this->log_key,
            'content'           => $this->log_content,
            'created_at'        => $this->created_at->toDateTimeString(),
        ];
    }


}
