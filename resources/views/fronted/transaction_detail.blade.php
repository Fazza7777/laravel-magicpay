@extends('fronted.layouts.app')
@section('title', 'Transaction Detail')
@section('content')
@section('transaction', 'active')
    <div class="transaction_detail">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ asset('img/transaction.png') }}" alt="">
                </div>
                <h6 class="text-center mb-4 @if ($transaction->type == 1) text-success
                @elseif($transaction->type == 2) text-danger @endif">
                    <b>{{ number_format($transaction->amount) }}</b> MMK
                </h6>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Trx ID </p>
                    <p class="mb-0">{{ $transaction->trx_id }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Ref No </p>
                    <p class="mb-0">{{ $transaction->ref_no }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Type</p>
                    <p class="mb-0">
                        @if ($transaction->type == 1)
                            <span class="badge badge-pill badge-success">Income</span>
                        @elseif ($transaction->type == 2)
                            <span class="badge badge-pill badge-danger">Expense</span>

                        @endif
                    </p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Amount</p>
                    <p class="mb-0">{{ number_format($transaction->amount) }} <small>MMK</small></p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Date & Time</p>
                    <p class="mb-0">{{ $transaction->created_at }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">
                        @if ($transaction->type == 1)
                            From
                        @elseif ($transaction->type == 2)
                            To
                        @endif
                    </p>
                    <p class="mb-0">{{ $transaction->source ? $transaction->source->name : '-' }} </p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Description</p>
                    <p class="mb-0">{{ $transaction->description }} </p>
                </div>

            </div>
        </div>


    </div>
@endsection
