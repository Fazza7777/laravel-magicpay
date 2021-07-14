<?php

namespace App\Http\Controllers\Fronted;
use App\Http\Controllers\Controller;


class NotificationController extends Controller
{
    public function index(){
        $user = auth()->guard('web')->user();
        $notifications = $user->notifications()->paginate(5);
        return view('fronted.notification',compact('notifications'));
    }
    public function detail($id){
        $user = auth()->guard('web')->user();
        $notification = $user->notifications()->where('id',$id)->firstOrFail();
        $notification->markAsRead();
       return view('fronted.notification_detail',compact('notification'));

    }
}
