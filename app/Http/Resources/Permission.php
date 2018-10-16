<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Permission extends Resource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'display_name' => $this->display_name,
            'parent_id'    => $this->parent_id,
            'created_at'   => $this->created_at->toDateTimeString(),

        ];
    }
}
