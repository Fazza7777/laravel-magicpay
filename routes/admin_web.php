<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->namespace('Backend')->middleware('auth:admin_user')->group(function () {
    Route::get('/','PageController@home')->name('home');
});
