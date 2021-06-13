@extends('backend.layouts.app')
@section('title', 'Create Admin User ')
@section('admin-user-create', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Create Admin User </div>

            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.admin-user.store') }}" method="POST" id='create'>
                    @csrf
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <small class="text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                        <small class="text text-danger">{{ $message }}</small>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                        <small class="text text-danger">{{ $message }}</small>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <small class="text text-danger">{{ $message }}</small>
                    @enderror
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
{!! JsValidator::formRequest('App\Http\Requests\AdminUserRequest') !!}

{{-- if you have many form in this page can use below --}}
{{-- {!! JsValidator::formRequest('App\Http\Requests\AdminUserRequest','#create') !!} --}}

@endsection
