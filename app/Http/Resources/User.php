<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $user_gender_array = config('other.user_gender_array');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'frozen' => $this->frozen,
            'gender' => $user_gender_array[$this->gender],
            'role' => [
                'id' => $this->roles->first()->id,
                'display_name' => $this->roles->first()->name,
            ],
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
