<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Wallet;
use Carbon\Carbon;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Query\Expression;

class WalletController extends Controller
{
    public function index()
    {
        return view('backend.wallet.index');
    }
    public function ssd()
    {
        $data = Wallet::with('user'); // use ecar loading for performence
        return Datatables::of($data)
            ->addColumn('account_person', function ($each) {
                $user = $each->user;
                if ($user) {
                    return '<table class="table table-bordered">
                    <tbody>
                      <tr>
                         <td>Name</td>
                         <td>' . $user->name . '</td>
                      </tr>
                      <tr>
                         <td>Email</td>
                         <td>' . $user->email . '</td>
                      </tr>
                      <tr>
                         <td>Phone</td>
                         <td>' . $user->phone . '</td>
                      </tr>
                    </tbody>
                  </table>';
                }
            })
            ## Thousand sperator
            ->editColumn('amount', function ($each) {
                return number_format($each->amount, 2);
            })
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format('Y-m-d H:m:s');
            })
            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->updated_at)->format('Y-m-d H:m:s');
            })
            ->rawColumns(['account_person'])
            ->make(true);
    }
    public function addAmount()
    {
        $users = User::orderBy('name')->get();
        return view('backend.wallet.add_amount', compact('users'));
    }
    public function addAmountStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'amount' => 'required|integer',
        ], [
            'user_id.required' => 'Please choose user!'
        ]);
        if($request->amount < 1000){
            return redirect()->back()->withErrors(['amount' => 'ငွေပမာဏ အနည်းဆုံး 1000 ကျပ် ဖြည့်ရန်လိုအပ်ပါသည်။'])->withInput();
        }
        $description = $request->description;
        $amount = $request->amount;
        $ref_no = UUIDGenerate::refNumber();
        DB::beginTransaction();
        try {
            $to_account = User::with('wallet')->where('id', $request->user_id)->firstOrFail();
            $to__account_wallet = $to_account->wallet;
            $to__account_wallet->increment('amount', $amount);
            $to__account_wallet->update();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            DB::commit();
            return redirect('/admin/wallet')->with('success', 'Add amount succeful');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            DB::rollBack();
        }
    }
    public function reduceAmount()
    {
        $users = User::orderBy('name')->get();

        return view('backend.wallet.reduce_amount',compact('users'));
    }
    public function reduceAmountStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'amount' => 'required|integer',
        ], [
            'user_id.required' => 'Please choose user!'
        ]);

        if($request->amount < 1){
            return redirect()->back()->withErrors(['amount' => 'ငွေပမာဏ အနည်းဆုံး 1 ကျပ် ဖြည့်ရန်လိုအပ်ပါသည်။'])->withInput();
        }

        $description = $request->description;
        $amount = $request->amount;
        $ref_no = UUIDGenerate::refNumber();
        DB::beginTransaction();
        try {
            $to_account = User::with('wallet')->where('id', $request->user_id)->firstOrFail();
            $to__account_wallet = $to_account->wallet;

            if($amount > $to__account_wallet->amount){
                throw new Exception('The amount is greather than in wallet balence.');
            }
            $to__account_wallet->decrement('amount', $amount);
            $to__account_wallet->update();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 2;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            DB::commit();
            return redirect('/admin/wallet')->with('success', 'Reduce amount succeful');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            DB::rollBack();
        }
    }
}
