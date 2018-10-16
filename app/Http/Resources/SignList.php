<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SignList extends Resource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'status'     => $this->sign_status,
            'inser_type' => $this->sign_inset_type,
            'referee'    => $this->referee,
            'tel'        => $this->tel,
            'company'    => $this->company,
            'offer'      => $this->offer,
            'created_at' => $this->created_at->toDateTimeString(),
        ];

    }
}
