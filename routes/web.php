<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




## Admin Auth
Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::post('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

## User Auth
Auth::routes();
Route::middleware('auth')->namespace('Fronted')->group(function(){

    Route::get('/', 'PageController@home')->name('home');
    Route::get('/profile', 'PageController@profile')->name('profile');
    Route::get('/update-password', 'PageController@updatePassword')->name('update.password');
    Route::post('/update-password', 'PageController@updatePasswordStore')->name('update.password.store');
    Route::get('/wallet', 'PageController@wallet')->name('wallet');
    Route::get('/transfer','PageController@transfer')->name('transfer');
    Route::post('/transfer/confirm','PageController@transferConfirm')->name('transfer.confirm');
    Route::post('/transfer/complete','PageController@transferComplete')->name('transfer.complete');

    Route::get('/transaction','PageController@transaction');
    Route::get('/transaction/{trx_id}','PageController@transactionDetail');

    Route::get('/to-account-verify','PageController@toaccountVerify');
    Route::get('/transfer/confirm/check-password','PageController@checkPassword')->name('check.password');

    Route::get('/receive-qr','PageController@receiveQr')->name('receive.qr');
    Route::get('/scan-and-pay','PageController@scanAndPay');
    Route::get('/scan-and-pay-form','PageController@scanAndPayForm');
    Route::post('/scan-and-pay/confrim','PageController@scanAndPayConfirm')->name('scan_and_pay.confirm');
    Route::post('/scan-and-pay/complete','PageController@scanAndPayComplete')->name('scan_and_pay.complete');
});

