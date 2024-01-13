<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Cookie;
use Auth;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(){
        $data1 = Cookie::get('login1');
        $data2 = Cookie::get('login2');
        return view('auth.login', compact('data1', 'data2'));
    }

    public function login(){
        $cred = $this->validate(request(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($cred)) {
            $active = auth()->user()->active;
            if ( $active == 1 ) {
                if (request()->remember) {
                    $lifetime = time() + 60 * 60 * 24 * 365;
                    Cookie::queue(Cookie::make('login1', request()->username, $lifetime));
                    Cookie::queue(Cookie::make('login2', request()->password, $lifetime));
                }
                else{
                    if(Cookie::has('login1') && Cookie::has('login1')){
                        Cookie::queue(Cookie::forget('login1'));
                        Cookie::queue(Cookie::forget('login2'));
                    }
                }
                return redirect()->action('HomeController@index');
            } else {
                Auth::logout();
                return back()->withErrors(['username' => 'Error de usuario', 'password' => 'Error de contraseña']);
            }
        }
        return back()->withErrors(['username' => 'Error de usuario', 'password' => 'Error de contraseña']);
    }
}
