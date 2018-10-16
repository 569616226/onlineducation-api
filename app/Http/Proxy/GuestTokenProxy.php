<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 13:52
 */

namespace App\Http\Proxy;


use App\Models\Guest;

class GuestTokenProxy
{
    protected $http;

    /**
     * TokenProxy constructor.
     *
     * @param $http
     */
    public function __construct(\GuzzleHttp\Client $http)
    {
        $this->http = $http;
    }


    /*
     * 登陆
     * @param $name
     * @param $password
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function login($openid)
    {

        $guest = \App\Models\Guest::where('openid', $openid)->firstOrFail();

        if ($guest) {

            \Auth::login($guest, true);

            $token = $guest->createToken('wechat_token')->accessToken;

            if ($token) {

                return response()->json([
                    'status' => true,
                    'token'  => $token
                ]);
            } else {

                return response()->json([
                    'status'  => false,
                    'message' => '账号密码错误'
                ], 421);
            }

        } else {
            return response()->json(['status' => false, 'message' => '请关注微课堂公众号']);
        }


    }

}