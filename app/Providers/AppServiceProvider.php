<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Cron;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen( 'cron.collectJobs', function () {
            Cron::setDisablePreventOverlapping();
            Cron::setLaravelLogging( config('other.setLaravelLogging') );
            Cron::setLogOnlyErrorJobsToDatabase( config('other.setLogOnlyErrorJobsToDatabase') );//只记录错误日志
            Cron::add( 'send_vip_expire_msg', '30 9 * * *', function () {//每天早上9点半触发
                send_message();//vip到期提醒
            }, true );
            Cron::add( 'check_video_transCode_data', '* * * * *', function () {//每分钟触发
                pullEvent();//轮询视频上传回调
            }, true );
            Cron::add( 'check_and_change_trains_status', '* * * * *', function () {//每分钟触发
                check_and_change_trains_status();//活动自动开始和结束
            }, true );
        } );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(  \Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
