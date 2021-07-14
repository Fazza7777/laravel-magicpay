@extends('fronted.layouts.app')
@section('title', 'Notification')
@section('content')
@section('home', 'active')
    <div class="home ">
        <div class="infinite-scroll">
            @foreach ($notifications as $notification)

                <a href="{{ url("/notification/$notification->id") }}">
                    <div class="card mb-3 px-2 py-1">
                        <div class="card-body p-2">
                           <h6>
                               <i class="fas fa-bell mr-1 @if(is_null($notification->read_at)) text-danger @else text-success @endif"></i>
                               {{\Illuminate\Support\Str::limit($notification->data['title'],40)}}
                            </h6>
                           <p class="mb-1">
                            {{\Illuminate\Support\Str::limit($notification->data['message'],100)}}
                           </p>
                           <span class="text-muted">
                            {{ Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A')}}
                           </span>
                        </div>
                    </div>
                </a>
            @endforeach
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
@section('scripts')
    <script>

        //###############jscroll################################
        $('ul.pagination').hide();
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<div class="text-center"><img src="{{ asset('img/loading.png') }}" alt="Loading..." style="width:30px;"/></div>',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });

    </script>
@endsection
