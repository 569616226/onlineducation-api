<?php

namespace App\Listeners;

use App\Events\SendWechatMessage;
use App\Models\Order;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class SendPayLessonMessageNotification
{
    use SerializesModels;

    protected $order;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    /**
     * Handle the event.
     *
     * @param  SendWechatMessage  $event
     * @return void
     */
    public function handle(SendWechatMessage $event)
    {
        send_pay_lesson_success_message($this->order);
    }
}
