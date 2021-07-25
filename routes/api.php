<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function(){
      Route::get('/test','PageController@test');
});
