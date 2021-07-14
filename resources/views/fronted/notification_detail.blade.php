@extends('fronted.layouts.app')
@section('title', 'Notification Detail')
@section('content')
@section('home', 'active')
    <div class="home ">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-center">
                    <img src="{{ asset('img/notification.png') }}" style="width:220px;" alt="">
                </div>
                <h6 class="text-center font-weight-bold">{{ $notification->data['title'] }}</h6>
                <p class="mb-1 text-muted text-left">{{ $notification->data['message'] }}</p>
                <p class=" text-center">
                    <small>{{ Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A') }}</small>
                </p>
                <a href="{{ $notification->data['web_link'] }}" class="btn btn-theme btn-sm">Continue</a>
            </div>
        </div>
    </div>
@endsection
