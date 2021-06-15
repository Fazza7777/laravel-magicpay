<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fronted/css/style.css') }}">
    @yield('extra_css')
</head>

<body>
    <div id="app">
        <div class="header-menu">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-4 text-center">

                        </div>
                        <div class="col-4 text-center">
                            <a href="">
                              <h3>Magic Pay</h3>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="">
                                <i class="fa fa-bell"></i>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <main class="py-4">
            @yield('content')
        </main>

        <div class="botton-menu">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="">
                                <i class="fas fa-home"></i>
                                <p class="mb-0"> Home</p>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="">
                                <i class="fas fa-qrcode"></i>
                                <p class="mb-0"> Scan</p>
                            </a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="">
                                <i class="fa fa-user"></i>
                                <p class="mb-0"> Account</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('scripts')
</body>

</html>
