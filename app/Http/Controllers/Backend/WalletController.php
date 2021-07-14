<?php

namespace App\Http\Controllers\Backend;

use App\Wallet;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

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
                return number_format($each->amount,2);
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
}
