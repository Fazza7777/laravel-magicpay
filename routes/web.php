<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




## Admin Auth
Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::post('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

## User Auth
Auth::routes();
Route::middleware('auth')->group(function(){
    Route::get('/', 'Fronted\PageController@home');

});

