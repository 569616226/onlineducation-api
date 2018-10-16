<?php

namespace App\Http\Controllers\Backend;

/*查看和设置微信菜单*/

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatMenuController extends Controller
{
    public $menu;

    /**
     * MenuController constructor.
     * @param $menu
     */
    public function __construct(Application $app)
    {
        $this->menu = $app->menu;
    }

    /**
     * @return \EasyWeChat\Menu\Menu
     */
    public function menu(Request $request)
    {

        $buttons = $request->buttons;
//        $mobile_url = config('other.mobile_url');
//        $buttons = [
//            [
//                "name"       => "关于我们",
//                "sub_button" => [
//                    [
//                        "type" => "view",
//                        "name" => "学院简介",
//                        "url"  => $mobile_url . "#/AboutUs/1"
//                    ],
//                    [
//                        "type" => "view",
//                        "name" => "专家团介绍",
//                        "url"  => $mobile_url . "#/AboutUs/2"
//                    ],
//                    [
//                        "type" => "view",
//                        "name" => "产品介绍",
//                        "url"  => $mobile_url . "#/AboutUs/3"
//                    ],
//                    [
//                        "type" => "view",
//                        "name" => "联系我们",
//                        "url"  => $mobile_url . "#/AboutUs/4"
//                    ],
//                ]
//            ], [
//                "type" => "view",
//                "name" => "我要学习",
//                "url"  => $mobile_url . "#/"
//            ], [
//                "name"       => "个人中心",
//                "sub_button" => [
//                    [
//                        "type" => "view",
//                        "name" => "个人中心",
//                        "url"  => $mobile_url . "#/userall/user"
//                    ],
//                    [
//                        "type" => "view",
//                        "name" => "最近学习",
//                        "url"  => $mobile_url . "#/learning"
//                    ],
//                    [
//                        "type" => "view",
//                        "name" => "已购买课程",
//                        "url"  => $mobile_url . "#/userall/buyclass"
//                    ],
//                    [
//                        "type" => "view",
//                        "name" => "售后客服",
//                        "url"  => $mobile_url . "#/writefeedback"
//                    ],
//                ],
//            ],
//        ];

        $return_msg = $this->menu->add($buttons);

        if ($return_msg['errcode'] == 0) {
          return response()->json(['status' => true,'message' =>'操作成功']);
        }else{
            return response()->json(['status' => false,'message' =>$return_msg['errmsg']]);
        }
    }

    public function all()
    {
        return $this->menu->all();
    }

}
