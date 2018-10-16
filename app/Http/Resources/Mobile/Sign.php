<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\Resource;

class Sign extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'       => $this->name,
            'referee'    => $this->referee,
            'tel'        => $this->tel,
            'company'    => $this->company,
            'offer'      => $this->offer,
        ];

    }
}
