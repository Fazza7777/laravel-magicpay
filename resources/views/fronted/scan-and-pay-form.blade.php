@extends('fronted.layouts.app')
@section('title', 'Scan & Pay')
@section('content')
    <div class="transfer">
        <div class="card wallet-card">
            <div class="card-body">
                <form action="{{ route('scan_and_pay.confirm') }}" method="POST" autocomplete="false">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="" class="mb-0"><strong>From</strong></label>
                        <p class="mb-1 text-muted">{{ $auth_user->name }}</p>
                        <p class="mb-1 text-muted">{{ $auth_user->phone }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <input type="hidden" name="to_phone" value="{{  $to_account->phone }}">
                        <label for="" class="mb-0"><strong>To</strong></label>
                        <p class="mb-1 text-muted">{{ $to_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Amout (MMK)</label>
                        <input type="number" name="amount" value="{{ old('amount') }}"
                            class="form-control @error('amount') is-invalid @enderror">
                        @error('amount')
                            <small class="text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Description</label>
                        <textarea class="form-control" value="{{ old('description') }}" name="description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-4">Continue</button>
                </form>
            </div>
        </div>

    </div>
@endsection
@section('scripts')

@endsection
