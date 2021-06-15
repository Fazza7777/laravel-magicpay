@extends('fronted.layouts.app')
@section('title', 'Magic Pay')
@section('content')
@section('home', 'active')
    <div class="home ">
        <div class="row">
            <div class="col-12">
                <div class="profile mb-3">
                    <img src="https://ui-avatars.com/api/?background=5842E3&color=fff&name={{ $user->name }}" alt="">
                    <h6 class="mt-2">{{ $user->name }}</h6>
                    <p class="text-muted">{{ $user->wallet ? number_format($user->wallet->amount) : '-' }}
                        <span>MMK</span>
                    </p>
                </div>
            </div>
            <div class="col-6">
                <div class="card home-card mb-3">
                    <div class="card-body p-3">
                        <img src="{{ asset('img/scan.png') }}" alt="">
                        <span>Scan & Pay</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card home-card mb-3">
                    <div class="card-body p-3">
                        <img src="{{ asset('img/qr.png') }}" alt="">
                        <span>Receive QR</span>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-3 home-service">
                    <div class="card-body pr-0">
                        <a href="{{ route('transfer') }}" class="d-flex justify-content-between ">
                            <span>
                                <img src="{{ asset('img/transfer.png') }}" alt="">
                                Transfer
                            </span>
                            <span class="mr-3">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <hr>
                        <a href="#" class="d-flex justify-content-between logout">
                            <span>
                                <img src="{{ asset('img/wallet.png') }}" alt="">
                                Wallet
                            </span>
                            <span class="mr-3">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                        <hr>
                        <a href="#" class="d-flex justify-content-between logout">
                            <span>
                                <img src="{{ asset('img/transaction.png') }}" alt="">
                                Transaction
                            </span>
                            <span class="mr-3">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
