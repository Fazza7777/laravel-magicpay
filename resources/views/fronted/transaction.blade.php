@extends('fronted.layouts.app')
@section('title', 'Transaction ')
@section('content')
@section('transaction','active')
    <div class="transaction">
        @foreach ($transactions as $transaction)
        <a href="{{ url("/transaction/$transaction->trx_id") }}">
            <div class="card mb-3">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-1"><span>Trx ID : </span>{{ $transaction->trx_id }}</h6>
                        <p class="mb-1 @if ($transaction->type == 1) text-success
                        @elseif($transaction->type == 2) text-danger @endif">
                            @if ($transaction->type == 1)
                                +
                            @elseif ($transaction->type==2)
                                -
                            @endif
                            {{ $transaction->amount }} <small>MMK</small>
                        </p>
                    </div>
                    <p class="mb-1 text-muted">
                        @if ($transaction->type == 1)
                            From -
                        @elseif($transaction->type == 2)
                            To -
                        @endif
                        {{ $transaction->source ? $transaction->source->name : '-' }}
                    </p>
                    <p class="text-muted mb-1">
                        {{ $transaction->created_at }}
                    </p>
                </div>
            </div>
        </a>
        @endforeach
        {{ $transactions->links() }}
    </div>
@endsection
