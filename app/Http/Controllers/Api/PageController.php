<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
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
        $data = TransactionResource::collection($transaction)->additional(['result'=>1,'message'=>'success']);
        return $data;
    }
    public function transactionDetail($id)
    {
    }
}
