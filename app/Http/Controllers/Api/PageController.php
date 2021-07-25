<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Wallet;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Requests\TransferFormValidate;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Notification;
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
        $data = TransactionResource::collection($transaction)->additional(['result' => 1, 'message' => 'success']);
        return $data;
    }
    public function transactionDetail($trx_id)
    {
        $user = auth()->user();
        $transaction = Transaction::with('user', 'source')->where('user_id', $user->id)->where('trx_id', $trx_id)->firstOrFail();
        $data = new TransactionDetailResource($transaction);
        return success('success', $data);
    }
    public function notification()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(5);
        $data = NotificationResource::collection($notifications)->additional(['result' => 1, 'message' => 'success']);
        return $data;
    }
    public function notificationDetail($noti_id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->where('id', $noti_id)->firstOrFail();
        $notification->markAsRead();
        $data = new NotificationDetailResource($notification);
        return success('success', $data);
    }
    public function toAccountVerify(Request $request)
    {
        if ($request->phone) {
            $authUser = auth()->user();
            if ($authUser->phone != $request->phone) {
                $user = User::where('phone', $request->phone)->first();
                if ($user) {
                    return success('success', ['name' => $user->name, 'phone' => $user->phone]);
                }
            }
        }
        return fail('Invalid Data', null);
    }

    public function transferConfirm(TransferFormValidate $request)
    {
        $auth_user = auth()->user();
        $to_account = User::where('phone', $request->to_phone)->first();

        if (!$to_account) {
            return fail('Incorrect Phone Number!', null);
        }
        $check_account = Wallet::where('user_id', $to_account->id)->first();

        if (!$check_account) {
            return fail('This Phone Number cannot found Magic Pay Account!', null);
        }
        if ($auth_user->phone == $request->to_phone) {
            return fail('Cannot transfer return to your account.', null);
        }
        $currentUserWalletAmount = Wallet::where('user_id', $auth_user->id)->first()->amount;
        if ($request->amount > $currentUserWalletAmount) {
            return fail('Not engough money your account', null);
        }
        if ($request->amount < 1000) {
            return fail('Amount must be greather than 1000 MMK', null);
        }
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description ? $request->description : '-';
        return success('success', [
            'from_name' => $from_account->name,
            'from_phone' => $from_account->phone,
            'to_name' => $to_account->name,
            'to_phone' => $to_account->phone,
            'amount' => $amount,
            'description' => $description
        ]);
    }
    public function transferComplete(TransferFormValidate $request)
    {
        $auth_user = auth()->user();
        ## check password (add)
        if (!$request->password) {
            return fail('Please fill your password.', null);
        }
        if (!Hash::check($request->password, $auth_user->password)) {
            return fail('Password incorrect!.', null);
        }
        ## end check password
        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return fail('Incorrect Phone Number!', null);
        }
        if ($auth_user->phone == $request->to_phone) {
            return fail('Cannot transfer return to your account.', null);
        }
        $currentUserWalletAmount = Wallet::where('user_id', $auth_user->id)->first()->amount;
        if ($request->amount > $currentUserWalletAmount) {
            return fail('Not engough money in your account', null);
        }
        $from_account = $auth_user;
        $description = $request->description;
        $amount = $request->amount;
        if (!$from_account->wallet || !$to_account->wallet) {
            return fail('Something wrong ,check accounts!.', null);
        }
        DB::beginTransaction();
        try {
            $from__account_wallet = $from_account->wallet;
            $from__account_wallet->decrement('amount', $amount);
            $from__account_wallet->update();

            $to__account_wallet = $to_account->wallet;
            $to__account_wallet->increment('amount', $amount);
            $to__account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();
            ## For from account
            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();
            ## For to account
            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            DB::commit();
            // From Noti
            $title = 'E-money Transfered!';
            $message = 'Your wallet transfered ' . number_format($amount) . ' MMK to ' . $to_account->name . ' (' . $to_account->phone . ')';
            $sourceable_id = $from_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => ['trx_id' => $from_account_transaction->trx_id]
            ];
            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));
            // To Noti
            $title = 'E-money Received!';
            $message = 'Your wallet received ' . number_format($amount) . ' MMK from ' . $from_account->name . ' (' . $from_account->phone . ')';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => ['trx_id' => $to_account_transaction->trx_id]
            ];
            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));
            return success('Successfully Transfered', ['trx_id' => $from_account_transaction->trx_id]);
        } catch (\Exception $e) {
            return fail($e->getMessage(), null);
            DB::rollBack();
        }
    }
}
