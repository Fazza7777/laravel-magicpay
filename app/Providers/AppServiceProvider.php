<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        View::composer('*', function ($view) {
            $unread_noti_count = 0;
            if(Auth::check() && auth()->guard('web')->user()){
                $unread_noti_count = auth()->guard('web')->user()->unreadNotifications->count();
            }
            $view->with('unread_noti_count',$unread_noti_count);
        });
    }
}
