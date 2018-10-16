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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', 'LoginController@login');// 后台登陆
Route::post('token/refresh', 'LoginController@refresh');// 重新获取token
Route::get('logout', 'LoginController@logout');// 后台退出登陆

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api']], function () {//登陆认证 middleware【auth:api】

    //登陆账号信息
    Route::get('user/me', function () {
        return auth_user();
    });

    Route::get('/cache/flushall', 'CacheController@flushall');//缓存清除
    Route::group(['prefix' => 'wehcat'], function () { //微信菜单
        Route::post('/menu', 'WechatMenuController@menu');//设置微信菜单
        Route::get('/menu/all', 'WechatMenuController@all');//获取微信菜单数据
    });

    Route::group(['prefix' => 'menu'], function () { /*菜单数据*/
        Route::get('lists', 'MenuController@menus');// 获取菜单
    });

    Route::group(['prefix' => 'role'], function () {   /*角色*/
        Route::get('lists', 'RoleController@lists');// 角色列表
        Route::get('create', 'RoleController@create');//所有权限
        Route::get('{role}/edit', 'RoleController@edit');// 编辑
        Route::post('store', 'RoleController@store'); // 创建角色
        Route::post('{role}/update', 'RoleController@update'); // 更新角色
        Route::get('{role}/delete', 'RoleController@destroy');// 删除角色
    });

    Route::group(['prefix' => 'permission'], function () {/*权限*/
        Route::get('lists', 'PermissionsController@allPermissions');// 获取所有权限
        Route::get('{permission}', 'PermissionsController@edit');//
        Route::post('create', 'PermissionsController@store');// 权限
        Route::post('{permission}/update', 'PermissionsController@update');// 更新
        Route::get('{permission}/delete', 'PermissionsController@destroy');   //删除
    });

    Route::group(['prefix' => 'education'], function () {/*教务设置*/
        Route::get('lists', 'EducationController@lists');//列表
        Route::get('{educational}', 'EducationController@edit');//获取
        Route::post('create', 'EducationController@store');// 创建
        Route::post('{educational}/update', 'EducationController@update');// 更新
        Route::get('{educational}/delete', 'EducationController@destroy')->name('edu_del');   //删除
    });

    Route::group(['prefix' => 'user'], function () { /*账号管理*/
        Route::get('lists', 'UsersController@lists');// 账号列表
        Route::get('names', 'UsersController@names');// 账号列表
        Route::post('store', 'UsersController@store');// 创建账号
        Route::get('{user}/edit', 'UsersController@edit');  // 获取指定账号的信息
        Route::get('{user}/delete', 'UsersController@destroy');  // 删除账号
        Route::get('{user}/frozen', 'UsersController@frozen');  // 冻结
        Route::get('{user}/refrozen', 'UsersController@refrozen');  // 解冻
        Route::post('{user}/update', 'UsersController@update');// 更新账号
    });

    Route::group(['prefix' => 'log'], function () { /*系统日志*/
        Route::get('lists', 'LogsController@lists');//列表
    });

    Route::group(['prefix' => 'guest'], function () {/*用户管理*/
        Route::get('lists', 'GuestController@lists');// 用户列表
        Route::post('/', 'GuestController@store');// 创建用户
        Route::get('{guest}/frozen', 'GuestController@frozen');  // 获取指定用户的信息
        Route::get('{guest}/refrozen', 'GuestController@refrozen');  // 获取指定用户的信息
        Route::get('{guest}', 'GuestController@edit');  // 获取指定用户的信息
        Route::post('{guest}/set_label', 'GuestController@setLabel');  // 设置标签
        Route::get('{guest}/delete', 'GuestController@destroy');  // 删除用户
        Route::post('{guest}/update', 'GuestController@update');// 更新
    });

    Route::group(['prefix' => 'video'], function () {/*视频管理*/
        Route::get('lists', 'VideoController@lists');// 列表
        Route::get('names', 'VideoController@names');// 列表
        Route::get('success_lists', 'VideoController@successList');// 转码成功视频列表
        Route::post('/', 'VideoController@store');// 创建
        Route::get('{video}', 'VideoController@edit');  // 获取指定的信息
        Route::get('{video}/delete/{del_origin_video?}', 'VideoController@destroy');  // 删除
        Route::post('{video}/update/{update_origin_video?}', 'VideoController@update');// 更新
        Route::get('get_video_signature', 'VideoController@getVideoSignature');// web端上传视频签名
    });

    Route::group(['prefix' => 'genre'], function () { /*标签管理*/
        Route::get('lists', 'GenreController@lists');// 列表
        Route::get('names', 'GenreController@names');// 列表
        Route::post('/', 'GenreController@store');// 创建父标签
        Route::get('{genre}', 'GenreController@edit');  // 获取指定的信息
        Route::get('{genre}/delete', 'GenreController@destroy');  // 删除
        Route::post('{genre}/update', 'GenreController@update');// 更新
    });

    Route::group(['prefix' => 'nav'], function () {/*栏目管理*/
        Route::get('lists', 'NavController@lists');// 列表
        Route::get('names', 'NavController@names');// 列表
        Route::post('/', 'NavController@store');// 创建
        Route::get('{nav}', 'NavController@edit');  // 获取指定的信息
        Route::get('{nav}/delete', 'NavController@destroy');  // 删除
        Route::post('{nav}/update', 'NavController@update');// 更新
        Route::post('/change_nav_order', 'NavController@changeNavOrder');//栏目排序
    });

    Route::group(['prefix' => 'lesson'], function () {/*课程管理*/
        Route::get('lists', 'LessonController@lists');// 列表
        Route::get('names', 'LessonController@names');// 列表
        Route::get('up_lesson_list', 'LessonController@upLessonList');//上架课程列表（用图文和广告选择课程 ）
        Route::post('/', 'LessonController@store');// 创建
        Route::get('{lesson}/up', 'LessonController@up');//上架
        Route::get('{lesson}/down', 'LessonController@down');//下架
        Route::get('{lesson}', 'LessonController@edit');  // 编辑
        Route::get('{lesson}/delete', 'LessonController@destroy');  // 删除
        Route::post('{lesson}/update', 'LessonController@update');// 更新
        Route::post('{lesson}/set_out_play_times', 'LessonController@setOutPlayTimes');//更新外部显示视频播放量
        Route::post('{lesson}/set_out_like', 'LessonController@setOutLike');//更新外部显示点赞数据

        /*学员管理*/
        Route::get('{lesson}/student/lists', 'LessonController@students');// 列表

        /*课时管理*/
        Route::post('{lesson}/section', 'SectionController@store');// 创建
        Route::get('/section/{section}/delete', 'SectionController@destroy');  // 删除
        Route::post('/section/{section}/update', 'SectionController@update');// 更新
    });

    Route::group(['prefix' => 'label'], function () {/*用户标签*/
        Route::get('lists', 'LabelController@lists');// 列表
        Route::get('names', 'LabelController@names');// 列表
        Route::post('/', 'LabelController@store');// 创建
        Route::get('{label}', 'LabelController@edit');// 编辑
        Route::get('{label}/delete', 'LabelController@destroy');  // 删除
        Route::post('{label}/update', 'LabelController@update');// 更新
    });

    Route::group(['prefix' => 'discusse'], function () { /*评论*/
        Route::get('lists', 'DiscusseController@lists');// 列表
        Route::get('{discusse}/delete', 'DiscusseController@destroy');  // 删除
        Route::get('{discusse}/better', 'DiscusseController@better');// 精选
        Route::get('{discusse}/un_better', 'DiscusseController@unBetter');// 取消精选
        Route::post('{discusse}', 'DiscusseController@store');// 回复评论
    });

    Route::group(['prefix' => 'setting'], function () {/*首页设置*/

        Route::get('{setting}/get_index_type', 'SettingController@getIndexType');//数据
        Route::post('{setting}/set_index_type', 'SettingController@setIndexType');//设置

        Route::get('{setting}/get_vip_send_time', 'SettingController@getVipSendTime');//设置
        Route::post('{setting}/set_vip_send_time', 'SettingController@setVipSendTime');//设置

        Route::get('{setting}/get_wechat_sub', 'SettingController@getWechatSub');//设置
        Route::post('{setting}/set_wechat_sub', 'SettingController@setWechatSub');//设置

        Route::get('{setting}/sign_start_time', 'SettingController@signStartTime');//签到时间编辑
        Route::post('{setting}/set_sign_start_time', 'SettingController@setSignStartTime');//签到时间编辑提交
    });

    Route::group(['prefix' => 'teacher'], function () {/*讲师管理*/
        Route::get('lists', 'TeacherController@lists');// 列表
        Route::get('names', 'TeacherController@names');// 列表
        Route::post('/', 'TeacherController@store');// 创建
        Route::get('{teacher}', 'TeacherController@edit');// 编辑
        Route::get('{teacher}/delete', 'TeacherController@destroy');  // 删除
        Route::post('{teacher}/update', 'TeacherController@update');// 更新
    });

    Route::group(['prefix' => 'train'], function () { /*会销管理*/
        Route::get('lists', 'TrainController@lists');// 列表
        Route::post('/', 'TrainController@store');// 创建
        Route::get('{train}', 'TrainController@edit');  // 获取指定的信息
        Route::get('{train}/delete', 'TrainController@destroy');  // 删除
        Route::post('{train}/update', 'TrainController@update');// 更新
        Route::post('{train}/import', 'TrainController@importTrains');//导入签到名单

    });

    Route::group(['prefix' => 'sign'], function () {  /*签到管理*/
        Route::get('{train}/lists', 'SignController@lists');// 列表
        Route::get('{sign}', 'SignController@edit');  // 获取指定的信息
        Route::get('{sign}/delete', 'SignController@destroy');  // 删除
        Route::post('{sign}/update', 'SignController@update');// 更新
    });

    Route::group(['prefix' => 'advert'], function () {/*广告管理*/
        Route::get('lists', 'AdvertController@lists');// 列表
        Route::post('/', 'AdvertController@store');// 创建
        Route::get('{advert}', 'AdvertController@edit');// 编辑
        Route::get('{advert}/delete', 'AdvertController@destroy');  // 删除
        Route::post('{advert}/update', 'AdvertController@update');// 更新
    });

    Route::group(['prefix' => 'message'], function () {  /*消息管理*/
        Route::get('lists', 'MessageController@lists');// 群发消息列表
        Route::get('sys_list', 'MessageController@sysLists');//系统消息
        Route::post('send_messages', 'MessageController@sendMessages');//群发消息
    });

    Route::group(['prefix' => 'vip'], function () { /*vip管理*/
        Route::get('lists', 'VipController@lists');// 列表
        Route::get('names', 'VipController@names');// 列表
        Route::post('/', 'VipController@store');// 创建
        Route::get('{vip}', 'VipController@edit');// 编辑
        Route::get('{vip}/up', 'VipController@up');// 上架
        Route::get('{vip}/down', 'VipController@down');//下架
        Route::get('{vip}/delete', 'VipController@destroy');  // 删除
        Route::post('{vip}/update', 'VipController@update');// 更新
    });

    Route::group(['prefix' => 'order'], function () {/*订单管理*/
        Route::get('lists', 'OrderController@lists');// 列表
        Route::get('vip_order_list', 'OrderController@vipOrderList');// 列表
        Route::post('/', 'OrderController@store');// 创建
    });

    Route::group(['prefix' => 'image'], function () {/*图片管理*/
        Route::post('upload', 'ImageController@upload');// 列表
    });

});


