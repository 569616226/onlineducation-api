<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TrainList extends Resource
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
            'status'     => $this->train_status,
            'title'      => $this->title,
            'pic'        => $this->pic,
            'start_at'   => $this->start_at->toDateTimeString(),
            'end_at'     => $this->end_at->toDateTimeString(),
            'address'    => $this->address,
            'discrible'  => html_entity_decode(stripslashes($this->discrible)),
            'qr_code'    => $this->qr_code,
            'nav'        => $this->nav->id,
            'genres'     => $this->genres->pluck('id')->toArray(),
            'creator'    => $this->creator,
            'created_at' => $this->created_at->toDateTimeString(),
        ];

    }
}
