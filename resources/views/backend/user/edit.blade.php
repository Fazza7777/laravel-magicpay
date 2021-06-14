@extends('backend.layouts.app')
@section('title', 'Edit Admin User ')
@section('admin-user-index', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Edit Admin User </div>

            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.admin-user.update',$admin_user->id) }}" method="POST" id='update'>
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" value="{{ $admin_user->name }}" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <small class="text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" value="{{ $admin_user->email}}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                        <small class="text text-danger">{{ $message }}</small>
                    @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="number" name="phone" value="{{ $admin_user->phone }}" class="form-control @error('phone') is-invalid @enderror">
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
                        <button type="submit" class="btn btn-primary ml-3">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
{{-- {!! JsValidator::formRequest('App\Http\Requests\AdminUserRequest') !!} --}}

{{-- if you have many form in this page can use below --}}
{!! JsValidator::formRequest('App\Http\Requests\AdminUserEditRequest','#update') !!}

@endsection
