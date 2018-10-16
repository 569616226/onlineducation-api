<?php

namespace Tests\Feature\Mobile\Order;

use App\Http\Controllers\Frontend\OrderController;
use App\Models\GuestLesson;
use App\Models\Order;
use App\Models\Vip;
use Tests\BaseCase;

class OrderReturnControllerTest extends BaseCase
{

    public function test_get_a_lesson_order_return()
    {
        $order_controller = new OrderController();

        $order_controller->chang_order_status($this->lesson_order);

        $update_order = Order::find($this->lesson_order->id);

//        dump($update_order);

        $this->assertEquals(1, $update_order->status);

        $guest_lessons = GuestLesson::where('lesson_id', $this->lesson_order->order_type_id)
            ->where('guest_id', $this->lesson_order->guest_id)
            ->get();

        $this->assertEquals(1, $guest_lessons->first()->is_pay);

    }


    public function test_get_a_vip_order_return()
    {
        $vip_count = Vip::find($this->vip_order->order_type_id)->count;

        $order_controller = new OrderController();

        $order_controller->chang_order_status($this->vip_order);

        $update_order = Order::find($this->vip_order->id);

//        dump($update_order);

        $this->assertEquals(1, $update_order->status);

        $update_vip_count = Vip::find($this->vip_order->order_type_id)->count;

        $this->assertEquals($vip_count+1, $update_vip_count);

    }

}
