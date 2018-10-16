<?php

namespace App\Http\Resources\Mobile;

use App\Models\Guest;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Vip;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Cache;

class Order extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {
        $status = '已付款';
        if( $this->status == 1 ){
            $status = '已付款';
        } elseif( $this->status == 2 ){
            $status = '待付款';
        } elseif( $this->status == 3 ){
            $status = '已关闭';
        }

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'order_no'      => $this->order_no,
            'status'        => $status,
            'price'         => $this->price,
            'order_type_id' => $this->order_type_id,
            'pictrue'       => $this->pictrue,
            'title'         => $this->title,
            'created_at'    => $this->created_at->toDateTimeString(),
        ];
    }
}
