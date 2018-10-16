<?php

namespace App\Listeners;

use App\Events\SendWechatMessage;
use App\Models\Discusse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class SendTeacherReturnMessageNotification
{
    use SerializesModels;

    protected $discusse;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Discusse $discusse)
    {
        $this->discusse = $discusse;
    }

    /**
     * Handle the event.
     *
     * @param  SendWechatMessage  $event
     * @return void
     */
    public function handle(SendWechatMessage $event)
    {
        send_teacher_return_message($this->discusse);
    }
}
