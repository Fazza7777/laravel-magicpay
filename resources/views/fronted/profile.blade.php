@extends('fronted.layouts.app')
@section('title', 'Profile')
@section('content')
@section('profile', 'active')
    <div class="account">
        <div class="profile mb-3">
            <img src="https://ui-avatars.com/api/?background=5842E3&color=fff&name=PyaePhyoeNaing" alt="">
        </div>
        <div class="card mb-3">
            <div class="card-body pr-0">
                <div class="d-flex justify-content-between">
                    <span>
                        Username
                    </span>
                    <span class="mr-3">
                        {{ $user->name }}
                    </span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>
                        Phone
                    </span>
                    <span class="mr-3">
                        {{ $user->phone }}
                    </span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>
                        Email
                    </span>
                    <span class="mr-3">
                       {{ $user->email }}
                    </span>
                </div>

            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body pr-0">
                <div class="d-flex justify-content-between">
                    <span>
                        Update Password
                    </span>
                    <span class="mr-3">
                        <i class="fa fa-angle-right"></i>
                    </span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>
                        Logout
                    </span>
                    <span class="mr-3">
                        <i class="fa fa-sign-out-alt"></i>
                    </span>
                </div>

            </div>
        </div>
    </div>
@endsection
