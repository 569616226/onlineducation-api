<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AuthPassword;
use App\Models\Guest;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Log;
use EasyWeChat;
use Illuminate\Http\Request;

class WeChatController extends Controller
{

    public $server;
    public $user;

    /**
     * WeChatController constructor.
     * @param $app
     */
    public function __construct()
    {
        $this->server = EasyWeChat::server();
        $this->user = EasyWeChat::user();
    }

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {

        $this->server->setMessageHandler(function ($message) {
            //获取微信用户OPENID
            $openid = $message->FromUserName;
            $guests = Guest::where('openid', $openid)->get();//本地微信用户

            switch ($message->MsgType) {

                case 'event':

                    if ($message->Event == 'subscribe') {//微信关注

                        $wechat_sub = get_settings('wechat_sub');
                        $subMessage = strip_tags(str_replace('<br>', "\n", html_entity_decode(stripslashes($wechat_sub))), "<a>");

                        if (empty($subMessage)) {
                            $subMessage = '感谢您关注微课堂';
                        }

                        return $subMessage;

                    } elseif ($message->Event == 'unsubscribe') {//取消关注

                        return '感谢您关注微课堂!';
                    }
                    break;
                case
                'text'://文本回复
//                    return '感谢您关注微课堂!';
                    break;
                case 'image':
//                  return '感谢您关注微课堂!';
                    break;
                case 'voice':
//                  return '感谢您关注微课堂!';
                    break;
                case 'video':
//                  return '感谢您关注微课堂!';
                    break;
                case 'location':
//                  return '感谢您关注微课堂!';
                    break;
                case 'link':
//                  return '感谢您关注微课堂!';
                    break;
                // ... 其它消息
                default:
//                  return '感谢您关注微课堂!';
                    break;
            }

            if ($guests->isEmpty()) {

                $this->setGuest($openid,false); //储存用户信息

            } else {

                $we_guest = $guests->first();//本地微信用户
                $this->setGuest($openid, $we_guest); //储存用户信息

            }
        });

        return $this->server->serve();
    }


    /**
     * 更新用户数据
     * @param $we_guest
     * @param $nickname
     * @param $headimgurl
     * @param $sex
     * @param $position
     */
    public function setGuest($openid, $we_guest)
    {

        try{

            //获取微信用户昵称
            $we_user = $this->user->get($openid);
            $nickname = $we_user->nickname;  //获取微信用户昵称
            $headimgurl = $we_user->headimgurl;//头像
            $sex = $we_user->sex;//性别
            $position = $we_user->country . $we_user->province . $we_user->city;//位置
            $subscribe = $we_user->subscribe;//用户是否订阅该公众号标识

            DB::beginTransaction();

            if ($subscribe) {

                if (!$we_guest) {//没有关注过

                    $data = [
                        'nickname'         => $nickname,
                        'openid'           => $openid,
                        'picture'          => $headimgurl,
                        'gender'           => $sex,
                        'position'         => $position,
                        'is_subscribe'     => $subscribe,
                    ];

                    $guest = Guest::storeCache($data,'guests');

                    if($guest){

                        $guest->assignRole(['guest']);
                        DB::commit();

                    }else{

                        DB::rollback();

                    }

                } else {

                    $data = [
                        'is_subscribe' => $subscribe,
                    ];

                    if ($headimgurl && $we_guest->picture !== $headimgurl) {

                        $data = array_merge($data, [
                            'picture' => $headimgurl
                        ]);

                    }

                    if ($sex && $we_guest->gender !== $sex) {

                        $data = array_merge($data, [
                            'gender' => $sex
                        ]);

                    }

                    if ($position && $we_guest->position !== $position) {

                        $data = array_merge($data, [
                            'gender' => $sex
                        ]);

                    }

                    $guest = Guest::updateCache($we_guest->id,$data,'guests');

                    if($guest){

                        $guest->assignRole(['guest']);
                        DB::commit();

                    }else{

                        DB::rollback();

                    }

                }
            }

        }catch (\Exception $exception){
            report($exception);
        }


    }

}
