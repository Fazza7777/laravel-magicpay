<?php

namespace App\Http\Controllers\Fronted;

use App\User;
use App\Wallet;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TransferFormValidate;
use App\Http\Requests\ChangePasswordRequest;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class PageController extends Controller
{
    public function home()
    {
        $user = auth()->guard('web')->user();

        return view('fronted.home', compact('user'));
    }
    public function profile()
    {
        $user = Auth::user();
        return view('fronted.profile', compact('user'));
    }
    public function updatePassword()
    {
        return view('fronted.update_password');
    }
    public function updatePasswordStore(ChangePasswordRequest $request)
    {
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $current_user = Auth::guard('web')->user();
        $user =  User::where('id', $current_user->id);
        ## check old password
        if (Hash::check($old_password, $current_user->password)) {
            $user->update(['password' => Hash::make($new_password)]);
            $title = 'Changed Password!';
            $message = 'Your account password is successfully changed.';
            $sourceable_id = $current_user->id;
            $sourceable_type = User::class;
            $web_link = url('profile');
            $deep_link = [
                'target' => 'profile',
                'parameter' =>null
            ];
            Notification::send([$current_user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));
            return redirect()->route('profile')->with('info', 'Password change successfully !');
        } else {
            return redirect()->back()->withErrors(['old_password' => 'လက်ရှိစကား၀ှက်မှားနေပါသည်။'])->withInput();
        }
    }
    public function wallet()
    {
        $auth_user = Auth::guard('web')->user();
        return view('fronted.wallet', compact('auth_user'));
    }
    public function transfer()
    {
        $auth_user = Auth::guard('web')->user();
        return view('fronted.transfer', compact('auth_user'));
    }
    public function transferConfirm(TransferFormValidate $request)
    {
        $auth_user = auth()->guard('web')->user();
        $to_account = User::where('phone', $request->to_phone)->first();

        if (!$to_account) {
            return redirect()->back()->withErrors(['to_phone' => 'ဖုန်းနံပါတ်မှားယွင်းနေပါသည်။'])->withInput();
        }
        $check_account = Wallet::where('user_id', $to_account->id)->first();

        if (!$check_account) {
            return redirect()->back()->withErrors(['to_phone' => 'ဖုန်းနံပါတ်သည် Magic Pay အကောင့်ဖွင့်ထားခြင်းမရှိသေးပါ။'])->withInput();
        }
        if ($auth_user->phone == $request->to_phone) {
            return redirect()->back()->withErrors(['to_phone' => 'သင့်အကောင့်ကို သင်ငွေပြန် လွှဲမရပါ။။'])->withInput();
        }
        $currentUserWalletAmount = Wallet::where('user_id', $auth_user->id)->first()->amount;
        if ($request->amount > $currentUserWalletAmount) {
            return redirect()->back()->withErrors(['amount' => 'ငွေလက်ကျန်မလုံလောက်ပါ။'])->withInput();
        }
        // if ($request->amount < 1000) {
        //     return redirect()->back()->withErrors(['amount' => 'လွှဲမည့်ငွေပမာဏ အနည်းဆုံး 1000 ကျပ် ဖြစ်ရန်လိုအပ်ပါသည်။'])->withInput();
        // }
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description ? $request->description : '-';

        return view('fronted.transfer_confirm', compact('from_account', 'to_account', 'amount', 'description'));
    }
    public function transferComplete(TransferFormValidate $request)
    {

        $auth_user = auth()->guard('web')->user();
        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return redirect()->back()->withErrors(['to_phone' => 'ဖုန်းနံပါတ်မှားယွင်းနေပါသည်။'])->withInput();
        }
        if ($auth_user->phone == $request->to_phone) {
            return redirect()->back()->withErrors(['to_phone' => 'သင့်အကောင့်ကို သင်ငွေပြန် လွှဲမရပါ။။'])->withInput();
        }
        $from_account = $auth_user;
        $description = $request->description;
        $amount = $request->amount;
        if (!$from_account->wallet || !$to_account->wallet) {
            return redirect()->back()->with('error', 'Something wrong ,check accounts!');
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
                'parameter' =>['trx_id'=>$from_account_transaction->trx_id]
            ];
            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));
            // To Noti
            $title = 'E-money Received!';
            $message = 'Your wallet received ' . number_format($amount) . ' MMK from ' . $from_account->name . ' (' . $from_account->phone . ')';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' =>['trx_id'=>$to_account_transaction->trx_id]
            ];
            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));

            return redirect('/transaction/' . $from_account_transaction->trx_id)->with('success', 'Payment succeful');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            DB::rollBack();
        }
    }
    ## Transaction
    public function transaction(Request $request)
    {
        $authUser = auth()->guard('web')->user();
        $transactions = Transaction::with('user', 'source')
            ->orderBy('created_at', 'desc')
            ->where('user_id', $authUser->id);
        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }
        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }
        $transactions = $transactions->paginate(5);
        return view('fronted.transaction', compact('transactions'));
    }
    public function transactionDetail($trx_id)
    {
        $authUser = auth()->guard('web')->user();
        $transaction = Transaction::with('user', 'source')->where('user_id', $authUser->id)->where('trx_id', $trx_id)->first();
        return view('fronted.transaction_detail', compact('transaction'));
    }
    ##Receive QR
    public function receiveQr()
    {
        $authUser = auth()->guard('web')->user();
        return view('fronted.receive_qr', compact('authUser'));
    }
    public function scanAndPay()
    {
        return view('fronted.scan_and_pay');
    }
    public function scanAndPayForm(Request $request)
    {
        $auth_user = auth()->guard('web')->user();
        $to_account = User::where('phone', $request->to_phone)->first();
        if ($to_account) {
            $check_account = Wallet::where('user_id', $to_account->id)->first();
            if ($check_account) {
                return view('fronted.scan-and-pay-form', compact('auth_user', 'to_account'));
            } else {
                return redirect()->back()->with('error', 'Magic Pay Account does not exist!');
            }
        } else {
            return redirect()->back()->with('error', 'Phone number is invalid');
        }
    }

    public function scanAndPayConfirm(TransferFormValidate $request)
    {
        $auth_user = auth()->guard('web')->user();
        $to_account = User::where('phone', $request->to_phone)->first();

        if (!$to_account) {
            return redirect()->back()->withErrors(['to_phone' => 'ဖုန်းနံပါတ်မှားယွင်းနေပါသည်။'])->withInput();
        }
        $check_account = Wallet::where('user_id', $to_account->id)->first();

        if (!$check_account) {
            return redirect()->back()->withErrors(['to_phone' => 'ဖုန်းနံပါတ်သည် Magic Pay အကောင့်ဖွင့်ထားခြင်းမရှိသေးပါ။'])->withInput();
        }
        if ($auth_user->phone == $request->to_phone) {
            return redirect()->back()->withErrors(['to_phone' => 'သင့်အကောင့်ကို သင်ငွေပြန် လွှဲမရပါ။။'])->withInput();
        }
        $currentUserWalletAmount = Wallet::where('user_id', $auth_user->id)->first()->amount;
        if ($request->amount > $currentUserWalletAmount) {
            return redirect()->back()->withErrors(['amount' => 'ငွေလက်ကျန်မလုံလောက်ပါ။'])->withInput();
        }
        // if ($request->amount < 1000) {
        //     return redirect()->back()->withErrors(['amount' => 'လွှဲမည့်ငွေပမာဏ အနည်းဆုံး 1000 ကျပ် ဖြစ်ရန်လိုအပ်ပါသည်။'])->withInput();
        // }
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description ? $request->description : '-';

        return view('fronted.scan_and_pay_confirm', compact('from_account', 'to_account', 'amount', 'description'));
    }
    public function scanAndPayComplete(TransferFormValidate $request)
    {

        $auth_user = auth()->guard('web')->user();
        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return redirect()->back()->withErrors(['to_phone' => 'ဖုန်းနံပါတ်မှားယွင်းနေပါသည်။'])->withInput();
        }
        if ($auth_user->phone == $request->to_phone) {
            return redirect()->back()->withErrors(['to_phone' => 'သင့်အကောင့်ကို သင်ငွေပြန် လွှဲမရပါ။။'])->withInput();
        }
        $from_account = $auth_user;
        $description = $request->description;
        $amount = $request->amount;
        if (!$from_account->wallet || !$to_account->wallet) {
            return redirect()->back()->with('error', 'Something wrong ,check accounts!');
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

            // From Noti
            $title = 'E-money Transfered!';
            $message = 'Your wallet transfered ' . number_format($amount) . ' MMK to ' . $to_account->name . ' (' . $to_account->phone . ')';
            $sourceable_id = $from_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' =>['trx_id'=>$from_account_transaction->trx_id]
            ];
            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));
            // To Noti
            $title = 'E-money Received!';
            $message = 'Your wallet received ' . number_format($amount) . ' MMK from ' . $from_account->name . ' (' . $from_account->phone . ')';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' =>['trx_id'=>$to_account_transaction->trx_id]
            ];
            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));

            DB::commit();
            return redirect('/transaction/' . $from_account_transaction->trx_id)->with('success', 'Payment succeful');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            DB::rollBack();
        }
    }
    ## transfer verify
    public function checkPassword(Request $request)
    {
        $user = auth()->guard('web')->user();
        if (empty($request->password)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Need! to enter your password!'
            ]);
        }
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'The password is correct'
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'The password is incorrect'
        ]);
    }
    public function toaccountVerify(Request $request)
    {
        if (Auth::guard('web')->user()->phone != $request->phone) {
            $user = User::where('phone', $request->phone)->first();
            if ($user) {
                $account = Wallet::where('user_id', $user->id)->first();
                if ($account) {
                    return response()->json([
                        'status' => 'success',
                        'data' => $user
                    ]);
                } else {
                    return response()->json([
                        'status' => 'not_account',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'wrong',
                ]);
            }
        }
        return response()->json([
            'status' => 'fail',

        ]);
    }
}
