<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Proxy\TokenProxy;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * @var TokenProxy
     */
    protected $proxy;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( TokenProxy $proxy )
    {
        $this->middleware( 'guest' )->except( 'logout' );
        $this->proxy = $proxy;
    }

    /**
     * @登陆
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function login()
    {
        return $this->proxy->login( request( 'name' ), request( 'password' ) );
    }

    /**
     * @退出
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return $this->proxy->logout();
    }

    /**
     * @重新登陆
     * @return TokenProxy
     */
    public function refresh()
    {
        return $this->proxy->refresh();
    }

}
