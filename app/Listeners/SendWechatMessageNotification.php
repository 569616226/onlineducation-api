<?php

namespace App\Listeners;

use App\Events\SendWechatMessage;
use App\Models\Lesson;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class SendWechatMessageNotification
{
    use SerializesModels;

    protected $lesson;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    /**
     * Handle the event.
     *
     * @param  SendWechatMessage  $event
     * @return void
     */
    public function handle(SendWechatMessage $event)
    {
        send_lesson_up_message($this->lesson);
    }
}
