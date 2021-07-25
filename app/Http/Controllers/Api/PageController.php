<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Wallet;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Http\Requests\TransferFormValidate;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

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
    public function toAccountVerify(Request $request){
        if($request->phone){
           $authUser = auth()->user();
           if($authUser->phone != $request->phone){
               $user = User::where('phone',$request->phone)->first();
               if($user){
                   return success('success',['name'=>$user->name,'phone'=>$user->phone]);
               }
           }
        }
        return fail('Invalid Data',null);
    }

    public function transferConfirm(TransferFormValidate $request)
    {
        $auth_user = auth()->user();
        $to_account = User::where('phone', $request->to_phone)->first();

        if (!$to_account) {
            return fail('Incorrect Phone Number!',null);
        }
        $check_account = Wallet::where('user_id', $to_account->id)->first();

        if (!$check_account) {
            return fail('This Phone Number cannot found Magic Pay Account!',null);

        }
        if ($auth_user->phone == $request->to_phone) {
            return fail('Cannot transfer return to your account.',null);
        }
        $currentUserWalletAmount = Wallet::where('user_id', $auth_user->id)->first()->amount;
        if ($request->amount > $currentUserWalletAmount) {
            return fail('Not engough money your account',null);

        }
        if ($request->amount < 1000) {
            return fail('Amount must be greather than 1000 MMK',null);
        }
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description ? $request->description : '-';
        return success('success',[
           'from_account'=>$from_account,
           'to_account'=>$to_account,
           'amount'=>$amount,
           'description'=>$description
        ]);
    }
}
