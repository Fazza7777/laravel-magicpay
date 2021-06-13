<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

## User Auth
Auth::routes();


## Admin Auth
Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::post('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

Route::get('/', 'Fronted\PageController@home');

