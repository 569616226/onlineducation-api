<?php

namespace App\Http\Proxy;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/29
 * Time: 23:58
 */
class TokenProxy
{
    protected $http;

    /**
     * TokenProxy constructor.
     *
     * @param $http
     */
    public function __construct( \GuzzleHttp\Client $http )
    {
        $this->http = $http;
    }

    /**
     * 登陆
     * @param $name
     * @param $password
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function login( $name, $password )
    {

        if( auth()->attempt( [ 'name' => $name, 'password' => $password ] ) ){

            $user = User::whereName( $name )->firstOrfail();

            if( $user->frozen == 1 ){
                return response()->json( [
                    'status'  => false,
                    'message' => '账号已被冻结！'
                ], 422 );

            }else{

                return $this->proxy( 'password', [
                    'username' => $name,
                    'password' => $password
                ] );

            }


        }else{

            return response()->json( [
                'status'  => false,
                'message' => '账号密码错误！'
            ], 421 );

        }



    }

    /**
     * 重置token
     * @return TokenProxy
     */
    public function refresh()
    {
        $refreshToken = request( 'refreshToken' );

        return $this->proxy( 'refresh_token', [
            'refresh_token' => $refreshToken
        ] );
    }

    /**
     * 接口
     * @param $grantType
     * @param array $data
     * @return $this
     */
    public function proxy( $grantType, array $data = [] )
    {
        $data = array_merge( $data, [
            'client_id'     => env( 'PASSPORT_CLIENT_ID' ),
            'client_secret' => env( 'PASSPORT_CLIENT_SECRET' ),
            'grant_type'    => $grantType,
            'provider'    => 'users',
            'scope'         => ''
        ] );

        $response = $this->http->post( config('app.url'). '/oauth/token', [
            'form_params' => $data
        ] );

        $token = json_decode( (string)$response->getBody(), true );

        /*登陆日志*/
        if( $token && $grantType == 'password' ){

            log_login( User::whereName( $data['username'] )->firstOrFail(), 'App\Models\User' );

            return response()->json( [
                'token'      => $token['access_token'],
                'auth_id'    => $token['refresh_token'],
                'expires_in' => $token['expires_in'],
            ] );

        }else{
            return response()->json( [
                'status' => false,
                'message' => '登陆失败'
            ] );
        }

    }

    /**
     * 退出
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth()->guard( 'api' )->user();
        if( is_null( $user ) ){
            app( 'cookie' )->queue( app( 'cookie' )->forget( 'refreshToken' ) );

            return response()->json( [
                'message' => '退出登陆'
            ], 204 );
        }

        $accessToken = $user->token();

        app( 'db' )->table( 'oauth_refresh_tokens' )
            ->where( 'access_token_id', $accessToken->id )
            ->update( [
                'revoked' => true,
            ] );

        app( 'cookie' )->queue( app( 'cookie' )->forget( 'refreshToken' ) );

        $accessToken->revoke();

        return response()->json( [
            'message' => '退出登陆'
        ], 204 );

    }
}
