@extends('fronted.layouts.app')
@section('title', 'Wallet')
@section('content')
@section('wallet', 'active')
    <div class="wallet">

        <div class="card wallet-card">
            <div class="card-body">
                <div class="mb-4">
                    <span>Balance</span>
                    <h4>{{ $auth_user->wallet ? number_format($auth_user->wallet->amount) : '-' }} <span>MMK</span></h4>
                </div>
                <div class="mb-4">
                    <span>Account Number</span>
                    <h5>{{ $auth_user->wallet ? $auth_user->wallet->account_number : '-' }}</h5>
                </div>
                <div>
                    <p>{{ $auth_user->name }}</p>
                </div>
            </div>
        </div>

    </div>
@endsection
