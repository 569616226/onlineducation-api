<?php

namespace App\Http\Resources\Mobile;

use App\Models\Order;
use App\Models\User;
use App\Models\Vip;
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

        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'nickname'     => $this->nickname,
            'phone'        => $this->phone,
            'picture'      => $this->picture,
            'position'     => $this->position,
            'gender'       => $this->guest_gender,
            'referee'      => $this->referee,
            'company'      => $this->company,
            'offer'        => $this->offer,
            'role'         => $this->roles->first()->display_name,
            'is_expire'    => $this->guest_is_expire,//是否过期
            'vip_name'     => $this->guest_vip_name,
            'is_vip'       => $this->guest_is_vip,
            'vip_end_date' => $this->guest_vip_end_date,
            'created_at'   => $this->created_at->toDateTimeString(),
        ];

    }
}
