<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('user/logout',['as'=>'user.logout','uses'=>'Auth\LoginController@userLogout']);        


Route::group(['prefix'=>'admin'], function () {     
	Route::get('/', ['as'=>'admin.dashboard','uses'=>'AdminController@index']);  
    Route::get('login',['as'=>'admin.login','uses'=>'Admin\LoginController@showLoginForm']);        
    Route::post('login',['as'=>'admin.login','uses'=>'Admin\LoginController@login']);
    Route::get('loutout',['as'=>'admin.logout','uses'=>'Admin\LoginController@logout']);        
    Route::post('password/email',['as'=>'admin.password.email','uses'=>'Auth\AdminForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset',['as'=>'admin.password.request','uses'=>'Auth\AdminForgotPasswordController@showLinkRequestForm']);        
    Route::post('password/reset',['uses'=>'Auth\AdminResetPasswordController@reset']);
    Route::get('password/reset/{token}',['as'=>'admin.password.reset','uses'=>'Auth\AdminResetPasswordController@showResetForm']);        
  });