<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'train'], function () {
    Route::get('{train}/download_qrcode', 'TrainController@downloadQrcode');// 下载二维码
    Route::get('/down_train_template', 'TrainController@downTrainTemplate');//签到模板下载
});

Route::get( 'video/getkeyurl', 'VideoController@getKeyUrl' )->middleware( [ 'api' ] );//获取视频解密钥匙是前端发起的请求会有跨域问题

Route::get( 'video/get_task_list/{status}', 'VideoController@getTaskList' );//拉取事件列表


