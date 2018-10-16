<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SendWechatMessage' => [
            'App\Listeners\SendWechatMessageNotification',
            'App\Listeners\SendTeacherReturnMessageNotification',
            'App\Listeners\SendDiscussesBetterMessageNotification',
            'App\Listeners\SendPayLessonMessageNotification',
            'App\Listeners\SendPayVipMessageNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
