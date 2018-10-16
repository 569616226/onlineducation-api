<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule( Schedule $schedule )
    {
        // $schedule->command('inspire')
        //          ->hourly();

        /*        $schedule->call(function (){
                    Log::info('定时任务开始');
                    send_message();
                })
                    ->withoutOverlapping()//避免任务重复
                    ->evenInMaintenanceMode()//维护模式
                    ->appendOutputTo(storage_path().'/send_vip_expire_message.txt')
                    ->everyMinute();
                    ->dailyAt('9:30');//每天9：30*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load( __DIR__ . '/Commands' );

        require base_path( 'routes/console.php' );
    }
}
