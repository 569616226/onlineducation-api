<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\Resource;

class Guest extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $labels = implode('、', $this->labels->pluck('name')->toArray());
        $role = $this->roles()->first();

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'nickname'   => $this->nickname,
            'phone'      => $this->phone,
            'picture'    => $this->picture,
            'referee'    => $this->referee,
            'company'    => $this->company,
            'offer'      => $this->offer,
            'position'   => $this->position,
            'gender'     => $this->guest_gender,
            'role'       => [
                'id' => $role->id,
                'display_name' => $role->display_name,
                ],
            'labels'     => $labels,
            'label_ids'  =>  $this->labels->pluck('id')->toArray(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
