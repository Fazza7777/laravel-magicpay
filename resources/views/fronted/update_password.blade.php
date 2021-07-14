@extends('fronted.layouts.app')
@section('title', 'Update Password')
@section('content')
@section('profile', 'active')
    <div class="update_password">

        <div class="card mb-3 p-4">
            <div class="card-body ">
                <div class="text-center">
                    <img src="{{ asset('img/security.png') }}" alt="">
                </div>
                <form action="{{ route('update.password.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Old Password</label>
                        <input type="password" name="old_password" value="{{ old('old_password') }}" class="form-control @error('old_password') is-invalid @enderror">
                        @error('old_password')
                              <small class="text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-4">
                        <label for="">New Password</label>
                        <input type="password" name="new_password" value="{{ old('new_password') }}" class="form-control @error('new_password') is-invalid @enderror">
                        @error('new_password')
                              <small class="text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-theme btn-block">Change Password</button>
                </form>
            </div>
        </div>

    </div>
@endsection
@section('scripts')

@stop
