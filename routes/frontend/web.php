<?php

use Illuminate\Support\Facades\Route;


Route::any('/wechat', 'WeChatController@serve');/*微信框架入口*/

Route::post('/order/notify_url', 'OrderController@notifyUrl');//订单回调






