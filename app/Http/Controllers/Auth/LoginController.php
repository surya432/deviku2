<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function authenticated()
    {
        $user = \Auth::User();
        $user->tokenApi()->delete(); //delete-token-yang-lama
        $tokenset = $user->createToken($user->email);
        $tokenset->token->save();

        // if($user->auth=='supplier'){
        //     session(['supplier'=> $user->user_supplier()->firstOrFail()]);
        // }
        //\Cookie::queue('token', $tokenset->accessToken, 45000);

        session(['token' => $tokenset->accessToken]);
    }

    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'username';

        return [
            $field => $request->get($this->username()),
            'password' => $request->password,
        ];
    }
    public function logout(Request $request){
        \Auth::logout();
        return redirect('/login');
    }
}
