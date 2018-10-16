<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\Resource;

class Teacher extends Resource
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
//            'id'         => $this->id,
            'name'       => $this->name,
            'office'     => $this->office,
            'avatar'     => $this->avatar,
            'describle'  => $this->describle,
//            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
