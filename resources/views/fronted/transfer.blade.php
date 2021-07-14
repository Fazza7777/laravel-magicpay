@extends('fronted.layouts.app')
@section('title', 'Transfer')
@section('content')
    <div class="transfer">
        <div class="card wallet-card">
            <div class="card-body">
                <form action="{{ route('transfer.confirm') }}" method="POST" autocomplete="false">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="" class="mb-0"><strong>From</strong></label>
                        <p class="mb-1 text-muted">{{ $auth_user->name }}</p>
                        <p class="mb-1 text-muted">{{ $auth_user->phone }}</p>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">To <span class="show-name text-success"></span></label>
                        <div class="input-group">
                            <input type="number" name="to_phone" value="{{ old('to_phone') }}"
                                class="form-control to_phone @error('to_phone') is-invalid @enderror">
                            <div class="input-group-append">
                                <span class="input-group-text btn verify-btn">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            </div>
                        </div>
                        <small class="text text-success show"></small>
                        @error('to_phone')
                            <small class="text text-danger">{{ $message }}</small>
                        @enderror
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
    <script>
        $(document).ready(function() {
            function removeAdd() {
                $('.show').removeClass('text-success')
                $('.show').addClass('text-danger')
            }
            $('.btn-theme').on('click',function(){
                $('.show-name').text('')
                $('.show').text('')
            })
            $('.verify-btn').on('click', function() {
                var phone = $('.to_phone').val();
                $('small').text('')
                if (!phone) {
                    removeAdd()
                    $('.show').text('ဖုန်းနံပါတ် ရိုက်ထည့်ပီးမှ လွှဲမည့်အကောင့်ကို ရှာပါ၊၊')
                } else {
                    $.ajax({ url: '/to-account-verify?phone=' + phone,type: 'GET',
                        success: function(res) {
                            if (res.status == 'success') {
                                $('.show-name').text('( ' + res.data.name + ' )')
                            } else {
                                removeAdd()
                                $('.show').text('ယခု ဖုန်းနံပါတ် ဖြင့်အကောင့်ရှာမတွေ့ပါ၊')
                                $('.show-name').text('')
                            }
                            if (res.status == 'fail') {
                                removeAdd()
                                $('.show').text('သင့်အကောင့်ကို သင်ငွေပြန် လွှဲမရပါ။')
                                $('.show-name').text('')
                            }
                            if (res.status == 'not_account') {
                                removeAdd()
                                $('.show').text('ဖုန်းနံပါတ်သည် Magic Pay အကောင့်ဖွင့်ထားခြင်းမရှိသေးပါ။')
                                $('.show-name').text('')
                            }
                        }

                    })
                }

            })
        })

    </script>
@endsection
