<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function flushall()
    {
        Cache::flush();

//        //1. 加载Console内核
//        app()->make(\Illuminate\Contracts\Console\Kernel::class);
//        //2.  获取计划任务列表
//        $scheduleList = app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events();

        return response()->json(['status' => true,'message' => '缓存清除成功']);
    }
}
