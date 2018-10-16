<?php

namespace App\Http\Resources;

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
        $pay_type = '微信支付';
        $status = '待付款';

        if( $this->status == 1 ){
            $status = '已付款';
        } elseif( $this->status == 2 ){
            $status = '待付款';
        } elseif( $this->status == 3 ){
            $status = '已关闭';
        }

        if( $this->pay_type == 1 ){
            $pay_type = '微信支付';
        }

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'type'          => $this->type,
            'status'        => $status,
            'order_no'      => $this->order_no,
            'order_type_id' => $this->order_type_id,
            'price'         => $this->price,
            'pay_type'      => $pay_type,
            'guest'         => $this->guest->nickname,
            'mouth'         => $this->mouth,
            'start'         => $this->status == 1 && $this->start ? $this->start->toDateString() : null,
            'end'           => $this->status == 1 && $this->end ? $this->end->toDateString() : null,
            'pay_date'      => $this->status == 1 && $this->pay_date ? $this->pay_date->toDateTimeString() : null,
            'created_at'    => $this->created_at->toDateTimeString(),
        ];
    }
}
