<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('fronted/css/style.css') }}">
@yield('extra_css')
</head>
<body>
   @yield('content')
   <script src="{{ asset('js/app.js') }}"></script>
   @yield('scripts')
</body>
</html>
