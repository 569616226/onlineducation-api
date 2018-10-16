<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Proxy\GuestTokenProxy;
use App\Models\AuthPassword;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use EasyWeChat;

class AuthController extends Controller
{

    use AuthenticatesUsers;

    /**
     * @var GuestTokenProxy
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
    public function __construct(GuestTokenProxy $proxy)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->proxy = $proxy;
    }

    /**
     *认证微信用户
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function login()
    {

        return $this->proxy->login(request('openid'));

    }

}
