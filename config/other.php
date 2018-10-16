<?php

return [

    'train_status_array'    => [
        0 => '未开始',
        1 => '进行中',
        2 => '已结束',
    ],
    'sign_status_array'     => [
        0 => '未签到',
        1 => '已签到',

    ],
    'sign_inset_type_array' => [
        0 => '表格导入',
        1 => '公众号报名',
        2 => '现场报名',
    ],
    'lesson_status_array'   => [
        1 => '未上架',
        2 => '已下架',
        3 => '已上架',
    ],
    'lesson_type_array'     => [
        1 => '免费课程',
        2 => '付费课程',
        3 => 'VIP课程',
        4 => '访谈课程',
    ],
    'guest_lesson_pivot'    => ['is_like', 'is_collect', 'add_date', 'collect_date', 'is_pay', 'sections', 'last_section'],
    'guest_gender_array'    => [
        0 => '未知',
        1 => '男',
        2 => '女',
    ],
    'vip_status_array'      => [
        1 => '已上架',
        2 => '未上架',
        3 => '已下架',
    ],
    'video_status_array'    => [

//        视频状态， -1：未上传完成，不存在；0：初始化，暂未使用；1：审核不通过，暂未使用；2：正常；3：暂停；4：转码中；5：发布中；6：删除中；7：转码失败；100：已删除
-1  => '视频不存在',
0   => '未知状态',
1   => '审核不通过',
2   => '转码成功',
3   => '暂停',
4   => '转码中',
5   => '发布中',
6   => '删除中',
7   => '转码失败',
100 => '已删除',
    ],
    'user_gender_array'     => [
        0 => '未知',
        1 => '男',
        2 => '女',
    ],
    'log_model_array'       => [
        'App\\Models\\User'        => '账号管理',
        'App\\Models\\Advert'      => '广告管理',
        'App\\Models\\Discusse'    => '评论管理',
        'App\\Models\\Educational' => '教务设置',
        'App\\Models\\Genre'       => '标签管理',
        'App\\Models\\Guest'       => '用户管理',
        'App\\Models\\Label'       => '标签管理',
        'App\\Models\\Lesson'      => '课程管理',
        'App\\Models\\Message'     => '消息管理',
        'App\\Models\\Nav'         => '栏目管理',
        'App\\Models\\Order'       => '订单管理',
        'App\\Models\\Permission'  => '权限管理',
        'App\\Models\\Role'        => '角色管理',
        'App\\Models\\Section'     => '章节管理',
        'App\\Models\\Setting'     => '主页信息管理',
        'App\\Models\\Teacher'     => '讲师管理',
        'App\\Models\\Video'       => '视频库管理',
        'App\\Models\\Vip'         => 'Vip管理',
        'App\\Models\\Train'       => '会销管理',
        'App\\Models\\Sign'        => '签到管理',
    ],
    'log_key_array'         => [
        'up'         => '上加',
        'down'       => '下架',
        'login'      => '登陆',
        'frozen'     => ['解冻', '冻结'],
        'is_better'  => ['取消加精', '加精'],
        'created_at' => '新建',
        'deleted_at' => '删除',
    ],

    'mobile_url'                    => env('MOBILE_URL', 'https://mobile.edu.elinkport.com/'),
    'staff_openid'                  => env('STAFF_OPENID', 'oDMF40TXDO3tvmymHr5aMPM-1gu0'),
    'train_template_url'            => env('TRAIN_TEMPLATE_URL', storage_path('app/temps/template.xls')),
    'setLaravelLogging'             => env('SET_LARAVEL_LOGGING', false),
    'setLogOnlyErrorJobsToDatabase' => env('SET_LOG_ONLY_ERROR_JOBS_DATABASE', true),

    'qcloud_secretid'  => env('QCLOUD_SECRETID', 'AKID54ESVQIi7KPI0dNh73xoaFk6HPRMbjCW'),
    'qcloud_secretkey' => env('QCLOUD_SECRETKEY', 'JNwOiw3zoza5VjvU8A8ycAWfcS7by8Re'),
    'seeder_count'     => intval(env('SEEDER_COUNT', 1)),

];