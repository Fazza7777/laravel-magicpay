@extends('fronted.layouts.app')
@section('title', 'Scan & Pay')
@section('content')
    <div class="transfer">
        <div class="card wallet-card">
            <div class="card-body">
                <form action="{{ route('scan_and_pay.complete') }}" method="POST" id='form'>
                    @csrf
                    <input type="hidden" name="to_phone" value="{{ $to_account->phone }}">
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    <div class="form-group mb-3">
                        <label for="" class="mb-0"><strong>From</strong></label>
                        <p class="mb-1 text-muted">{{ $from_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $from_account->phone }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="mb-0"><strong>To</strong></label>
                        <p class="mb-1 text-muted">{{ $to_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="mb-0"><strong>Amount</strong></label>
                        <p class="mb-1 text-muted">{{ number_format($amount) }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="mb-0"><strong>Description</strong></label>
                        <p class="mb-1 text-muted">{{ $description }}</p>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-4 confirm-btn">Confirm</button>
                </form>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.confirm-btn').on('click', function(e) {
                e.preventDefault()
                Swal.fire({
                    title: '<strong>Please fill your password</strong>',
                    icon: 'info',
                    html: '<input type="password" class="form-control password text-center"/>',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var password = $('.password').val()
                        $.ajax({
                            url: '/transfer/confirm/check-password?password=' + password,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 'success') {
                                    $('#form').submit()
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: res.message,

                                    })
                                }
                            }
                        })
                    }
                })
            })
        })

    </script>
@endsection
