<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationDetailResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\TransactionResource;
use App\Transaction;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function test()
    {
        return success("Successfully", 'Testing API Magic Pay');
    }
    public function profile()
    {
        $user = auth()->user();
        $data = new ProfileResource($user);
        return success('message', $data);
    }
    public function transaction(Request $request)
    {
        $user = auth()->user();
        $transaction = Transaction::with('user', 'source')->orderBy('created_at', 'DESC')->where('user_id', $user->id);
        if ($request->date) {
            $transaction = $transaction->whereDate('created_at', $request->date);
        }
        if ($request->type) {
            $transaction = $transaction->where('type', $request->type);
        }
        $transaction = $transaction->paginate(5);
        // additional is include page no , next,prev url for pagination
        $data = TransactionResource::collection($transaction)->additional(['result'=>1,'message'=>'success']);
        return $data;
    }
    public function transactionDetail($trx_id)
    {
        $user = auth()->user();
        $transaction = Transaction::with('user','source')->where('user_id',$user->id)->where('trx_id',$trx_id)->firstOrFail();
        $data = new TransactionDetailResource($transaction);
        return success('success',$data);
    }
    public function notification(){
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(5);
        $data = NotificationResource::collection($notifications)->additional(['result'=>1,'message'=>'success']);
        return $data;
    }
    public function notificationDetail($noti_id){
        $user = auth()->user();
        $notification = $user->notifications()->where('id',$noti_id)->firstOrFail();
        $notification->markAsRead();
        $data = new NotificationDetailResource($notification);
        return success('success',$data);
    }
}
