@extends('backend.layouts.app')
@section('title', ' User Wallet')
@section('wallet-active', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div> Add Amount </div>

            </div>
        </div>
    </div>

    <div class="content py-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ url('admin/wallet/add/amount/store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">User</label>
                        <select name="user_id" class="user form-control @error('user_id')is-invalid @enderror">
                            <option vlue=""></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->phone }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                           <small class="text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Amount</label>
                        <input type="number" name="amount" class="form-control @error('user_id') is-invalid @enderror">
                        @error('amount')
                           <small class="text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-outline-secondary back-btn">Cancel</button>
                        <button type="submit" class="btn btn-primary ml-3">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.user').select2({
                theme: 'bootstrap4',
                placeholder: "-- Please Choose User --",
                allowClear: true
            });
        });
    </script>
@stop
