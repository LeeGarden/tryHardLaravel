<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Socialite;
use App\Model\User;
use Exception;

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
        $this->middleware('guest',['except' => ['logout','userLogout']]);
    }

    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($service)
    {
        return Socialite::driver($service)->redirect();
    }

    /**
     * Obtain the user information from Social.
     *
     * @return Response
     */
    public function handleProviderCallback($service)
    {

        try {
            if ($service == 'google') {
                $userSocial = Socialite::driver($service)->stateless()->user();
            }else {
                $userSocial = Socialite::driver($service)->user();
            }

            $existUser = User::where('email',$userSocial->email)->first();

            if($existUser)
            {
                $existUser->name = $userSocial->name;
                $existUser->save();
                Auth::login($existUser);
            }else{
                $user = new User;
                $user->name     = $userSocial->name;
                $user->email    = $userSocial->email;
                $user->password = bcrypt(str_random(10));
                $user->save();

                Auth::login($user);
            }

        } catch (Exception $e) {
            return redirect()->route('login')->with(["err-msg"=>"Failed to authenticate with $service. Please try again."]);
        }

        return redirect()->route('home');
    }
}
