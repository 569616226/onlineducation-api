<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\Resource;

class Nav extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {


        $data = [
            'id'         => $this->id,
            'name'       => $this->name,
            'pictrue'    => $this->pictrue,
            'order_type' => $this->order_type,
            'ordered'    => $this->ordered,
            'type'       => $this->type,
        ];

        /*如果是活动栏目，返回活动数据*/
        if ($this->type) {

            $data = array_merge($data, [
                'trains' => $this->nav_top_trains,
            ]);

        } else {

            $data = array_merge($data, [
                'lessons' => $this->nav_top_lessons,
            ]);
        }

        return $data;
    }
}
