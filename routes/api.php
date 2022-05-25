<?php

use Illuminate\Http\Request;

Route::get('sendautherror', function(){
    return response()->json(['result' => 'error', 'message' => 'Unauthorized']);
});
Route::post('login', 'Auth\LoginController@login');
Route::post('signup', 'Auth\RegisterController@signup');
Route::post('sendResetEmail', 'Auth\ForgotPasswordController@SendResetCode');
Route::post('passwordReset', 'Auth\ResetPasswordController@passwordreset');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('user', 'UserController@details');
    Route::get('couponCheck/{couponString}', 'ApiController@cuponCheck');
    Route::post('update/profile', 'ApiController@updateProfile');
    Route::post('update/bio', 'ApiController@updateBio');
    Route::post('order/store', 'ApiController@storeOrder');
    Route::get('getpushhistory', 'ApiController@getPushHistory');
    Route::get('setpushhistory', 'ApiController@setPushHistoryBadge');
});


Route::get('catalogs', 'ApiController@getCatalogs');
Route::get('storeinfo', 'ApiController@storeInfo');
Route::get('check/payment/status/{orderid}', 'ApiController@checkPaymentStatus');
