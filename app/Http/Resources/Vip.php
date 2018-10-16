<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Vip extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {

        $vip_status_array = config('other.vip_status_array');

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'status'     => $vip_status_array[$this->status],
            'price'      => $this->price,
            'count'      => $this->count,
            'describle'  => $this->describle,
            'expiration' => $this->expiration,
            'up'         => $this->start ? $this->start->toDateTimeString() : null,
            'down'       => $this->end ? $this->end->toDateTimeString() : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
