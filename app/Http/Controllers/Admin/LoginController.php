<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class LoginController extends Controller
{
	public function __construct()
	{
		$this->middleware('guest:admin',['except' => ['logout']]);
	}

    public function showLoginForm()
    {

    	return view('admin.login');
    }

    public function login(Request $request)		
    {
    	//validate form date
    	$this->validate($request, [
    		'email' => 'required|email',
    		'password' => 'required|min:6'
    	]);
    	//Attemp to log user in
    	if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password],$request->remember))
    	{
    		//if successful, then redirect their intended
    		return redirect()->intended(route('admin.dashboard'));
    	}

    	//if unsuccessful, then redirect to form login
    	return redirect()->route('admin.login')->withInput($request->only('email','remember'));
    }

    public function logout()
    {
    	Auth::guard('admin')->logout();
    	return redirect('/');
    }
}
