<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;

/*客户端*/
Route::group(['prefix' => 'item'], function () {

    Route::post('login', 'AuthController@login');//微信端登陆
    Route::post('get_wechat_config', 'OrderController@getWxConfig');//获取微信jssdk,签名
    Route::get('get_code', 'AuthController@getCode');//获取code

    Route::group(['prefix' => 'train'], function () { /*会销管理*/
        /*签到管理*/
        Route::post('/{train}/sign', 'SignController@store');// 活动报名
        Route::post('get_sign', 'SignController@getSignByTel');// 根据手机查找以前报名表数据
        Route::post('{train}/guest_signed', 'SignController@guestSigned');// 签到
        Route::get('{train}', 'TrainController@edit');  // 获取指定的信息
    });

    Route::get('lesson/{lesson}/preview', 'LessonController@preview');// 预览

    Route::group(['middleware' => ['auth:mobile_api']], function () {  //登陆认证 middleware【auth:mobile_api】

        Route::group(['prefix' => 'guest'], function () {  /*个人信息*/
            Route::get('profile', 'GuestController@profile');// 个人中心
            Route::post('send_sms', 'GuestController@sendSms');// 发送验证码
            Route::post('{guest}/check_tel', 'GuestController@checkTel');// 绑定手机
            Route::post('{guest}/update', 'GuestController@update');//编辑个人资料
            Route::get('my_collect_train', 'GuestController@myCollectTrain');//我的收藏活动
            Route::get('collect_lessons', 'GuestController@collectLessons');// 收藏课程列表
        });

        Route::group(['prefix' => 'lesson'], function () {/*课程管理*/

            Route::get('index', 'LessonController@index');// 首页数据
            Route::get('{lesson}/edit', 'LessonController@edit'); //课程信息
            Route::get('{nav}/nav_lessons', 'LessonController@navLessons');// 栏目课程列表
            Route::get('{nav}/nav/{genre}/genre_lessons', 'LessonController@genreLessons');//标签课程列表
            Route::get('{lesson}/collect', 'LessonController@collect');//收藏课程
            Route::get('{lesson}/like', 'LessonController@like');//点赞课程
            Route::get('pay_orders', 'LessonController@payOrders');//购买课程列表

            Route::get('learned_lessons', 'LessonController@learnedLessons');//学习记录
            Route::get('search', 'LessonController@search');//搜索
            Route::get('section/{section}', 'SectionController@edit'); /*课时管理*/

        });

        Route::group(['prefix' => 'nav'], function () {  /*栏目管理*/
            Route::get('lists', 'NavController@lists');// 列表
        });

        Route::group(['prefix' => 'message'], function () {/*消息管理*/
            Route::get('/lists', 'MessageController@lists');// 列表
            Route::get('{message}', 'MessageController@show');//查看
        });

        Route::group(['prefix' => 'discusse'], function () {/*评论*/
            Route::get('{lesson}/lesson_discusses', 'DiscusseController@lessonDiscusse');//列表
            Route::get('{lesson}/my_lesson_discusses', 'DiscusseController@myLessonDiscusse');//我的评论
            Route::post('{lesson}', 'DiscusseController@store');// 发表评论
            Route::post('{discusse}/destroy', 'DiscusseController@destroy');// 删除评论
        });

        Route::group(['prefix' => 'order'], function () {/*订单管理*/
            Route::get('{vip}/vip', 'OrderController@createVip');//vip下单
            Route::get('{lesson}/lesson', 'OrderController@createLesson');//课程下单
            Route::get('check_status', 'OrderController@checkStatus');//订单状态查询
        });

        Route::group(['prefix' => 'advert'], function () {/*广告管理*/
            Route::get('lists', 'AdvertController@lists');// 列表
        });

        Route::group(['prefix' => 'train'], function () { /*会销管理*/

            Route::get('{nav}/nav_trains', 'TrainController@navTrains');// 栏目活动列表
            Route::get('{nav}/nav/{genre}/genre_trains', 'TrainController@genreTrains');//标签活动列表
            Route::get('{train}/collect', 'TrainController@collect');//收藏会销活动
            Route::get('{train}/uncollect', 'TrainController@uncollect');//取消收藏

        });

        Route::group(['prefix' => 'setting'], function () { /*首页设置*/
            Route::get('{setting}', 'SettingController@index');
        });

        Route::group(['prefix' => 'genre'], function () { /*标签管理*/
            Route::get('lists', 'GenreController@lists');// 列表
        });

        Route::group(['prefix' => 'vip'], function () { /*vip管理*/
            Route::get('lists', 'VipController@lists');// 列表
        });

        Route::post('staff_message', 'MessageController@staffMessage');// 反馈建议

    });
});

