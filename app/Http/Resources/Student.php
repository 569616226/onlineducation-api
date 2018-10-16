<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\Resource;

class Student extends Resource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'nickname'    => $this->nickname,
            'phone'       => $this->phone,
            'picture'     => $this->picture,
            'learned_per' => $this->learned_per,
            'gender'      => $this->guest_gender,
            'add_date'    => $this->pivot->add_date ? $this->pivot->add_date->toDateTimeString() : null,
        ];
    }
}
