<?php


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use Venturecraft\Revisionable\Revision;


if (!function_exists('array_swap')) {
    /**
     * @param $array
     * @param $i
     * @param $j
     * @return mixed
     */
    function array_swap(&$array, $i, $j)
    {
        if ($i != $j && array_key_exists($i, $array) && array_key_exists($j, $array)) {
            $temp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $temp;
        }
        return $array;
    }
}

/*认证用户*/
if (!function_exists('auth_user')) {
    /**
     * @return mixed
     */
    function auth_user()
    {

        $auth_user = \Illuminate\Support\Facades\Auth::guard('api')->user();

        return $auth_user;
    }
}

/*手机认证用户*/
if (!function_exists('guest_user')) {
    /**
     * @return mixed
     */
    function guest_user()
    {

        $guest_user = \Illuminate\Support\Facades\Auth::guard('mobile_api')->user();;

        return $guest_user;
    }
}

/*手机认证用户*/
if (!function_exists('clear_cache')) {
    /**
     * @param $cache_key
     */
    function clear_cache($cache_key)
    {
        Cache::tags($cache_key)->flush();
        Cache::tags('revisions')->flush();
    }
}

if (!function_exists('get_settings')) {

    /*获取系统设置*/
    /**
     * @param $setting_index
     * @return mixed
     */
    function get_settings($setting_index)
    {
        return \App\Models\Setting::value($setting_index);
    }

}

if (!function_exists('get_top_lesson_ids')) {

    /*获取系统top_lesson_ids设置*/
    /**
     * @param $setting_index
     * @return mixed
     */
    function get_top_lesson_ids()
    {
        return \App\Models\Setting::value('top_lesson_ids') ?? [];
    }

}

if (!function_exists('get_top_train_ids')) {

    /*获取系统top_lesson_ids设置*/
    /**
     * @param $setting_index
     * @return mixed
     */
    function get_top_train_ids()
    {
        return \App\Models\Setting::value('top_train_ids') ?? [];
    }

}


if (!function_exists('set_settings')) {

    /*获取系统设置*/
    /**
     * @param $setting_index
     * @return mixed
     */
    function set_settings($setting_index, $value)
    {
        return \App\Models\Setting::first()->update([$setting_index => $value]);
    }

}


if (!function_exists('send_message')) {
    /*
     * vip到期提醒
     *
     * */
    /**
     *
     */
    function send_message()
    {

        $vip_expire_set = get_settings('vip_send_seting');
        $guests = \App\Models\Guest::recent('guests')
            ->where('vip_id', '!=', null)
            ->where('frozen', 0)
            ->where('is_subscribe', 1)
            ->get();

        foreach ($guests as $guest) {

            $orders = $guest->orders()
                ->whereType(2)
                ->orderBy('end', 'desc')
                ->whereStatus(1)
                ->where('order_type_id', $guest->vip_id);

            if ($orders->get()->count()) {

                $order = $orders->firstOrFail();
                $end = $order->end->toDateString();
                $order_data_first = now()->addDays($vip_expire_set)->toDateString();
                $order_data_second = now()->addDays($vip_expire_set - 2)->toDateString();
                $order_data_three = $vip_expire_set - 4 >= 1 ? now()->addDays($vip_expire_set - 4)->toDateString() : null;

//                \Log::info('会员到订单:' . $order->id . '----------end:' . $order->end->toDateString() . '-------------' . \Cron::getRunInterval());

                if ($end == $order_data_first || $end == $order_data_second || $end == $order_data_three) {

                    $title = '您好，您的会员即将到期，请您注意。';
                    $expDate = $order->end->year . '年' . $order->end->month . '月' . $order->end->day . '日';

                    $data = [
                        'touser'      => $guest->openid,
                        'template_id' => config('wechat_template.send_vip_message'),//会员到期提醒
                        'url'         => env('MOBILE_URL') . '#/userall/myvip',
                        'data'        => [
                            "first"   => $title,
                            "name"    => '供应链微课堂' . $order->name,
                            "expDate" => $expDate,
                            "remark"  => "请及时续费会员，会员到期后，将无法观看VIP视频。",
                        ],
                    ];
                    $content = '<div>您的微课堂' . '供应链微课堂' . $order->name . '有效期至' . $expDate . '。备注：请及时续费会员，会员到期后，将无法观看VIP视频。</div>';

                    $result = \EasyWeChat::notice()->send($data);

                    if ($result['errcode'] == 0) {
                        store_template_message([$guest->id], $title, $content);
                        \Log::info('会员到期提醒成功---------' . $guest->nickname);
                    } else {
                        \Log::info('会员到期提醒失败-----------' . $guest->nickname);
                    }

                }
            }
        }
    }
}

/*
 * vip开通成功通知
 *
 * */
if (!function_exists('send_vip_success_message')) {
    /**
     * @param $order
     */
    function send_pay_success_message($order)
    {
        $guest = \App\Models\Guest::getCache($order->guest_id, 'guests');
        $expDate = $order->end->toDateTimeString();
        $title = '恭喜您已成功开通会员！';

        $data = [
            'touser'      => $guest->openid,
            'template_id' => config('wechat_template.send_pay_success_message'),//vip开通成功通知
            'url'         => '',
            'data'        => [
                "first"    => $title,
                "keyword1" => $order->name,
                "keyword2" => $guest->nickname,
                "keyword3" => $order->price . '元',
                "keyword4" => $expDate,
                "remark"   => "即日起，既可享受观看所有VIP专属视频和享受会员特权。",
            ],
        ];

        $content = '<div>会员名称：' . $order->name . '</div><div>会员账号：' . $guest->nickname . '</div><div>支付金额：' . $order->price . '元' .
            '</div><div>到期时间：' . $expDate . '</div><div>即日起，既可享受观看所有VIP专属视频和享受会员特权。</div>';

        $result = \EasyWeChat::notice()->send($data);

        if ($result['errcode'] == 0) {

            store_template_message($guest->id, $title, $content);

            \Log::info('发送vip开通成功通知成功' . $guest->nickname);
        } else {
            \Log::info('发送vip开通成功通知失败' . $guest->nickname);
        }
    }
}

/*
 * 课程更新通知
*/
if (!function_exists('send_lesson_up_message')) {

    /**
     * @param $lesson
     * @return bool
     */
    function send_lesson_up_message($lesson)
    {
        $guests = $lesson->guests()->where('is_subscribe', 1)->get();//关注，收藏，购买，学习过的用户


        if($lesson->type == 4){ /*访谈课程*/
            $url = env('MOBILE_URL') . '#/InterviewDetail/' . $lesson->id;
        }else{
            $url = env('MOBILE_URL') . '#/details/' . $lesson->id;
        }

        if ($guests->count()) {

            foreach ($guests as $guest) {

                $expDate = $lesson->updated_at->year . '年' . $lesson->updated_at->month . '月'
                    . $lesson->updated_at->day . '日';

                $title = '你关注的' . $lesson->name . '课程又更新了，赶紧看看吧！';

                $data = [
                    'touser'      => $guest->openid,
                    'template_id' => config('wechat_template.send_lesson_up_message'),//课程更新通知
                    'url'         => $url,
                    'data'        => [
                        "first"    => $title,
                        "keyword1" => $lesson->name,
                        "keyword2" => $expDate,
                        "remark"   => '',
                    ],
                ];
                $content = '<div>课程：' . $lesson->name . '</div><div>时间：' . $expDate . '</div>';

                $result = \EasyWeChat::notice()->send($data);

                if ($result['errcode'] == 0) {

                    return store_template_message($guest->id, $title, $content);

                } else {

                    return false;

                }
            }
        } else {
            return true;
        }

    }
}

/*
 * 购买课程成功通知
 * */
if (!function_exists('send_pay_lesson_success_message')) {

    /**
     * @param $order
     */
    function send_pay_lesson_success_message($order)
    {
        $lesson = \App\Models\Lesson::getCache($order->order_type_id,'lessons');
        if($lesson->type == 4){ /*访谈课程*/
            $url = env('MOBILE_URL') . '#/InterviewDetail/' . $lesson->id;
        }else{
            $url = env('MOBILE_URL') . '#/details/' . $lesson->id;
        }



        $guest = \App\Models\Guest::getCache($order->guest_id, 'guests');
        $expDate = $order->created_at->toDateTimeString();
        $title = '您已成功购买' . $order->name . '课程';
        $data = [
            'touser'      => $guest->openid,
            'template_id' => config('wechat_template.send_pay_lesson_success_message'),//购买课程成功通知
            'url'         => $url,
            'data'        => [
                "first"    => $title,
                "keyword1" => $order->order_no,
                "keyword2" => $order->name,
                "keyword3" => $order->price . '元',
                "keyword4" => $expDate,
                "remark"   => "感谢您的购买，祝你有所收获！",
            ],
        ];

        $content = '<div>订单编号：' . $order->order_no . '</div><div>课程名称：' . $order->name .
            '</div><div>订单金额：' . $order->price . '元' . '</div><div>购买时间：' . $expDate . '</div><div>感谢您的购买，祝你有所收获！</div>';

        $result = \EasyWeChat::notice()->send($data);
        if ($result['errcode'] == 0) {
            store_template_message($guest->id, $title, $content);
            \Log::info('发送购买课程成功通知成功' . $guest->nickname);
        } else {
            \Log::info('发送购买课程成功通知失败' . $guest->nickname);
        }
    }
}


/*
 * 评论入选通知
 * */
if (!function_exists('send_discusses_set_better_message')) {

    /**
     * @param $discusses
     * @return bool
     */
    function send_discusses_set_better_message($discusses)
    {
        if (config('app.env') === 'testing') {
            return true;
        }

        /*用户有关注公众号才可以发送消息*/
        if ($discusses->guest->is_subscribe) {

            $expDate = $discusses->created_at->toDateTimeString();
            $title = '恭喜您的评论入选为精选评论！';
            $data = [
                'touser'      => $discusses->guest->openid,
                'template_id' => config('wechat_template.send_discusses_set_better_message'),//评论入选通知
                'url'         => env('MOBILE_URL') . '#/morecomment/' . $discusses->lesson->id,
                'data'        => [
                    "first"    => $title,
                    "keyword1" => $discusses->content,
                    "keyword2" => $expDate,
                    "remark"   => "点击查看详情",
                ],
            ];

            $content = '<div>评论内容：' . $discusses->content . '</div><div>评论时间：' . $expDate . '</div><div>点击查看详情</div>';

            $result = \EasyWeChat::notice()->send($data);

            if ($result['errcode'] == 0) {

                return store_template_message($discusses->guest->id, $title, $content);

            } else {

                return false;

            }
        }

    }
}

/*
 * 讲师回复通知
 *
 * */
if (!function_exists('send_teacher_return_message')) {
    /**
     * @param $discusses
     * @return bool
     */
    function send_teacher_return_message($discusses)
    {

        /*用户有关注公众号才可以发送消息*/
        if ($discusses->guest->is_subscribe) {

            $title = '您的课程评价已被讲师回复！';
            $data = [
                'touser'      => $discusses->guest->openid,
                'template_id' => config('wechat_template.send_teacher_return_message'),//讲师回复通知
                'url'         => env('MOBILE_URL') . '#/morecomment/' . $discusses->lesson->id,
                'data'        => [
                    "first"    => $title,
                    "keyword1" => $discusses->lesson->name,
                    "keyword2" => $discusses->lesson->teacher->name,
                    "keyword3" => $discusses->teacherMsg->content,
                    "remark"   => "点击查看详情",
                ],
            ];

            $content = '<div>课程：' . $discusses->lesson->title . '</div><div>讲师：' . $discusses->lesson->teacher->name . '</div><div>回复：' . $discusses->teacherMsg->content . '</div><div>点击查看详情</div>';

            $result = \EasyWeChat::notice()->send($data);

            if ($result['errcode'] == 0) {

                store_template_message($discusses->guest->id, $title, $content);

            } else {
                \Log::info('课程评论回复提醒消息发送失败');
            }
        }
    }
}


/*vip到期上架*/
if (!function_exists('up_vip_on_set_time')) {
    /**
     *
     */
    function up_vip_on_set_time()
    {
        $vips = \App\Models\Vip::recent('vips')->where('up', '<=', now()->timestamp)->get();
        foreach ($vips as $vip) {
            \App\Models\Vip::updateCache($vip->id, ['status' => 1], 'vips');
        }
    }
}

/*vip到期下架*/
if (!function_exists('down_vip_on_set_time')) {
    /**
     *
     */
    function down_vip_on_set_time()
    {
        $vips = \App\Models\Vip::recent('vips')->where('down', '<=', now()->timestamp)->get();
        foreach ($vips as $vip) {
            \App\Models\Vip::updateCache($vip->id, ['status' => 3], 'vips');
        }
    }
}

/*
 * $object 登陆用户
 * 登陆日志
*/
if (!function_exists('log_login')) {

    /**
     * @param $object
     * @param $model
     */
    function log_login($object, $model)
    {
        $revision = new Revision();
        $revision->revisionable_type = $model;
        $revision->revisionable_id = $object->id;
        $revision->user_id = $object->id;
        $revision->key = 'login';
        $revision->new_value = now();
        $revision->save();
    }
}

/*图片上传*/
if (!function_exists('upload')) {
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    function upload($request)
    {
        $file = $request->file('image');
        //判断文件是否上传成功
        if ($file->isValid()) {
            //获取原文件名
            $originalName = $file->getClientOriginalName();
            //扩展名
            $ext = $file->getClientOriginalExtension();
            if (!in_array($ext, ['png', 'gif', 'jpeg', 'jpg'])) {
                return response()->json([
                    'status'  => false,
                    'message' => '图片必须为png,gif,jpeg,jpg格式',
                ]);
            }
            //文件类型
            $type = $file->getClientMimeType();
            //临时绝对路径
            $realPath = $file->getRealPath();

            if ($file->getSize() > 1048576) {
                return response()->json([
                    'status'  => false,
                    'message' => '图片不能大于1M',
                ]);
            }
            $filename = 'nav_' . rtrim($originalName, '.' . $ext) . '-' . uniqid() . '.' . $ext;
            $bool = \Illuminate\Support\Facades\Storage::disk('images')->put($filename, file_get_contents($realPath));
            if ($bool) {
                return response()->json(asset('storage/uploads/images/' . $filename));
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => '图片上传失败',
                ]);
            }
        } else {
            return response()->json([
                'status'  => false,
                'message' => '图片上传失败',
            ]);
        }
    }
}

/*
 * $title 标题
 * $content 内容
 * $ids 消息接受者id
 * 存储模板消息
*/
if (!function_exists('store_template_message')) {

    /**
     * @param $ids
     * @param $title
     * @param $content
     * @param null $label
     * @param null $user_id
     * @param null $url
     * @param null $picture
     * @return bool
     */
    function store_template_message($ids, $title, $content, $label = null, $user_id = null, $url = null, $picture = null)
    {

        $message = \App\Models\Message::create([
            'title'   => $title,
            'content' => htmlentities(addslashes($content)),
            'label'   => $label,
            'user_id' => $user_id,
            'url'     => $url,
            'picture' => $picture,
        ]);

        if ($message) {

            $message->guests()->attach($ids);

            return true;

        } else {
            return false;
            \Log::info('消息保存失败');
        }

    }
}


/**
 * 腾讯视频接口共用方法
 * @param $action
 * @param null $parms
 * @return mixed
 */
if (!function_exists('videoCommon')) {

    /**
     * @param $action
     * @param null $parms
     * @param null $time
     * @return mixed
     */
    function videoCommon($action, $parms = null, $time = null)
    {
        if (!$time) {
            $time = now()->timestamp;
        }
        $signaturemethod = 'HmacSHA256';
        $nonce = rand(1, 9) * 10;

        /*获取腾讯接口签名，参数必须按字母排序*/
        $srcStr = 'GETvod.api.qcloud.com/v2/index.php?Action=' . $action . '&Nonce=' . $nonce . '&SecretId='
            . env('QCLOUD_SECRETID', null) . '&SignatureMethod=' . $signaturemethod . '&Timestamp=' . $time;

        if ($parms) {
            foreach ($parms as $key => $parm) {
                $srcStr .= '&' . $key . '=' . $parm;
            }
        }

        $signature = base64_encode(hash_hmac('sha256', $srcStr, env('QCLOUD_SECRETKEY', null), true));

        $array_merge = [
            'Action'          => $action,
            'Nonce'           => $nonce,
            'SecretId'        => env('QCLOUD_SECRETID', null),
            'Signature'       => $signature,
            'SignatureMethod' => $signaturemethod,
            'Timestamp'       => $time,
        ];

        if ($parms) {
            $array_merge = array_merge([
                'Action'          => $action,
                'Nonce'           => $nonce,
                'SecretId'        => env('QCLOUD_SECRETID', null),
                'Signature'       => $signature,
                'SignatureMethod' => $signaturemethod,
                'Timestamp'       => $time,
            ], $parms);
        }

        if ($action == 'pullEvent') {
            $http = new \GuzzleHttp\Client(['timeout' => 10]);
        } else {
            $http = new \GuzzleHttp\Client();
        }


        $response = $http->request('GET', 'https://vod.api.qcloud.com/v2/index.php', [
            'query' => $array_merge
        ]);

        $result = json_decode((string)$response->getBody(), true);

        return $result;

    }
}

/**
 *拉取事件通知
 */
if (!function_exists('pullEvent')) {

    /**
     *
     */
    function pullEvent()
    {

//        Log::info(' pullEvent  ---- start ------' . now()->toDateTimeString());

        $result = videoCommon('PullEvent', null, now()->timestamp);

        if ($result['code'] == 0 && count($result['eventList'])) {


//            Log::info(count($result['eventList']) . '任务事件列表数量------' . now()->toDateTimeString());

            foreach ($result['eventList'] as $eventlist) {

                $eventType = $eventlist['eventContent']['eventType'];
                $data = $eventlist['eventContent']['data'];

                if ($eventType == 'ProcedureStateChanged') {

//                    Log::info(' pullEvent  ---- ' . $data['status'] . ' ------' . now()->toDateTimeString());

                    $fileId = $data['fileId'];

                    //errCode为1000的时候是视频转码不能从低到高，有些格式转码失败
                    if (in_array($data['errCode'], [0, 1000]) && $data['status'] == 'FINISH') {

                        $is_empty = \App\Models\Video::where('fileId', $fileId)->get()->isEmpty();

                        if (!$is_empty) {

                            $video_data = \App\Models\Video::where('fileId', $fileId)->firstOrFail();

                            //储存加密钥匙
                            if (in_array('drm', array_keys($data))) {
                                $edk_key = $data['drm']['edkList'][0];
                            } else {
                                $edk_key = null;
                            }

                            $data = [
                                'status'  => 2,
                                'edk_key' => $edk_key
                            ];

                            try {
                                \App\Models\Video::updateCache($video_data->id, $data, 'videos');
                            } catch (\Exception $e) {
                                report($e);
                            }

                            $parms = ['fileId' => $fileId];
                            $video = videoCommon('GetVideoInfo', $parms, now()->timestamp);

                            //更新视频转码后的信息
                            if ($video['code'] == 0) {

                                foreach ($video['transcodeInfo']['transcodeList'] as $index => $transImage) {
                                    $size = round($transImage['size'] / 1024 / 1024, 2) . 'M';
                                    $duration = $transImage['duration'];
                                    $url = $transImage['url'];
                                    $video_id = $video_data->id;

                                    $data_url = [
                                        'size'     => $size,
                                        'duration' => $duration,
                                        'url'      => $url,
                                        'video_id' => $video_id,
                                    ];

                                    try {
                                        \App\Models\VideoUrl::storeCache($data_url, 'video_urls');
                                    } catch (\Exception $e) {
                                        report($e);
                                    }
                                }

                            } else {

                                try {
                                    \App\Models\Video::updateCache($video_data->id, ['status' => 7], 'videos');//加密转码失败
                                } catch (\Exception $e) {
                                    report($e);
                                }

                            }
                        } else {

//                            Log::info('数据库没有对应视频----' . $fileId . '------' . now()->toDateTimeString());
                        }

//                        Log::info('视频转码成功----' . $fileId . '------' . now()->toDateTimeString());

                        confirmEvent($eventlist['msgHandle']);//确认事件通知

                    } else {

                        $is_empty = \App\Models\Video::where('fileId', $fileId)->get()->isEmpty();
                        if (!$is_empty) {

                            $video_data = \App\Models\Video::where('fileId', $fileId)->firstOrFail();

                            if ($video_data->status !== 7) {

                                try {
                                    \App\Models\Video::updateCache($video_data->id, ['status' => 7], 'videos');//加密转码失败
                                } catch (\Exception $e) {
                                    report($e);
                                }

                            }
                        }


//                        Log::info('视频转码失败 ----' . $data['errCode'] . $data['message'] . '------' . now()->toDateTimeString());

                        confirmEvent($eventlist['msgHandle']);//确认事件通知

                    }

                } elseif ($eventType !== 'ConcatComplete' && $data['status'] == 0) {

                    confirmEvent($eventlist['msgHandle']);//确认事件通知

//                    Log::info('视频处理成功----' . $eventType . '------' . now()->toDateTimeString());


                } elseif ($eventType == 'ConcatComplete') {//视频拼接
                    confirmEvent($eventlist['msgHandle']);//确认事件通知
                }


//                Log::info('跳过事件循环------' . now()->toDateTimeString());


                continue;
            }

        } else {

//            Log::info(' 确认事件拉取出错----' . $result['message'] . '------' . now()->toDateTimeString());

        }

    }

}

if (!function_exists('confirmEvent')) {
    /**
     * @param $eventlist
     */
    function confirmEvent($msgHandle)
    {
        videoCommon('ConfirmEvent', [
            'msgHandle.0' => $msgHandle,
        ], now()->timestamp);
    }
}

/**
 * 对中英文混杂字符进行分割
 *
 * @param $string
 * @param int $len
 * @return array
 */
if (!function_exists('mbStrSplit')) {

    /**
     * @param $string
     * @param int $len
     * @return array
     */
    function mbStrSplit($string, $len = 1)
    {
        $start = 0;
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, $start, $len, "utf8");
            $string = mb_substr($string, $len, $strlen, "utf8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }


}

/**
 * 对关键词过滤
 * @param $contents
 * @return bool|string
 */
if (!function_exists('checkContent')) {

    /**
     * @param $contents
     * @return bool|string
     */
    function checkContent($contents)
    {
        $word = '';
        foreach ($contents as $content) {

            $data = [
                'str'   => $content,
                'token' => env('HOAPI_TOKEN', '01fb627d4160bffb5a92fe5e334846a3'),
            ];

            $http = new \GuzzleHttp\Client();
            $response = $http->post('http://www.hoapi.com/index.php/Home/Api/check', [
                'form_params' => $data
            ]);

            $token = json_decode((string)$response->getBody(), true);


            if ($token['status'] && $token['code'] == 0) {//敏感词没有检测到敏感词
                continue;
            } else {

                if ($token['code'] == 1 && $token['data']) {//敏感词检测到敏感词
                    $word .= implode('、', array_pluck($token['data']['error'], 'word'));
                } else {
                    $word = $token['msg'];
                }

            }

        }

        if (empty($word)) {
            return false;
        } else {
            return $word;
        }

    }


    if (!function_exists('get_video_other_data')) {
        /**
         * @param $status
         * @param $video_urls
         * @return array
         */
        function get_video_other_data($status, $video_urls)
        {
            if ($status == 2 && !$video_urls->isEmpty()) {

                $video_max = \App\Models\VideoUrl::findOrFail($video_urls->max('id'));
                $url = $video_max->url;
                $duration = $video_max->duration;
                $size = \App\Models\VideoUrl::findOrFail($video_urls->min('id'))->size;

            } else {

                $url = null;
                $size = null;
                $duration = null;
            }

            return [
                'url'      => $url,
                'size'     => $size,
                'duration' => $duration,
            ];

        }
    }

}


if (!function_exists('check_and_change_trains_status')) {
    /**
     *
     */
    function check_and_change_trains_status()
    {
//        \Log::info('活动任务开始');
        $start_trains = \App\Models\Train::notStartOverStartTimeTrains();

        if (count($start_trains)) {
            $train_ids = array_pluck($start_trains, 'id');

            \App\Models\Train::whereIn('id', $train_ids)->update(['status' => 1]);
//            \Log::info('活动开始');
        }

        $going_trains = \App\Models\Train::goingOverEndTimeTrains();

        if (count($going_trains)) {
            $train_ids = array_pluck($going_trains, 'id');

            \App\Models\Train::whereIn('id', $train_ids)->update(['status' => 2]);
//            \Log::info('活动结束');
        }

    }
}


if (!function_exists('change_lesson_other_data')) {
    /*转化课程对外数据字段格式*/
    /**
     * @param $lessons
     * @return mixed
     */
    function change_lesson_other_data($lessons)
    {
        foreach ($lessons as $lesson) {
            $lesson->duration = $lesson->lesson_duration;
            $lesson->like = $lesson->lesson_like;
            $lesson->play_time = $lesson->lesson_play_time;
            $lesson->stauts = $lesson->lesson_stauts;
            $lesson->price = $lesson->lesson_price;
            $lesson->total_price = $lesson->lesson_total_price;
            $lesson->learning = $lesson->lesson_learning;
        }

        return $lessons;


    }
}


if (!function_exists('change_train_other_data')) {
    /*转化活动对外数据字段格式*/
    /**
     * @param $lessons
     * @return mixed
     */
    function change_train_other_data($trains)
    {
        foreach ($trains as $train) {
            $train->is_train = true;
            $train->status = $train->train_status;
        }

        return $trains;


    }
}










