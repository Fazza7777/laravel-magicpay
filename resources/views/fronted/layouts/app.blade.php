<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fronted/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    @yield('extra_css')
</head>

<body>
    <div id="app">
        <div class="header-menu">
            <div class="justify-content-center d-flex">
                <div class="col-md-8">
                    <div class="row">
                        <div class=" col-2 text-center">
                            @if (!request()->is('/'))
                                <a href="#" class="back"><i class="fas fa-angle-left"></i></a>
                            @endif

                        </div>
                        <div class=" col-8  text-center">
                            <a href="">
                                <h3>@yield('title')</h3>
                            </a>
                        </div>
                        <div class=" col-2 text-center">
                            <a href="">
                                <i class="fa fa-bell"></i>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class=" justify-content-center d-flex">
                <div class="col-md-8 col-12">
                    @yield('content')

                </div>
            </div>
        </div>

        <div class="botton-menu">
            <a href="" class="scan-tab">
                <div class="inside">
                    <i class="fas fa-qrcode"></i>
                </div>
            </a>
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-3 text-center">
                            <a href="{{ route('home') }}" class="@yield('home')">
                                <i class="fas fa-home"></i>
                                <p class="mb-0"> Home</p>
                            </a>
                        </div>
                        <div class="col-3 text-center ">
                            <a href="{{ route('wallet') }}" class="@yield('wallet')">
                                <i class="fas fa-wallet"></i>
                                <p class="mb-0"> Wallet</p>
                            </a>
                        </div>
                        <div class="col-3 text-center ">
                            <a href="{{ url('/transaction') }}" class="@yield('transaction')">
                                <i class="fas fa-exchange-alt"></i>
                                <p class="mb-0"> Transaction</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{ route('profile') }}" class="@yield('profile')">
                                <i class="fas fa-user-circle"></i>
                                <p class="mb-0"> Profile</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- sweat alert 2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('share.flash_message')

    <script>
        $(document).ready(function() {
            let token = document.head.querySelector("meta[name='csrf-token']")
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN': token.content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',

                    }
                })
            }

            // Back
            $('.back').on('click', function(e) {
                e.preventDefault();
                window.history.go(-1)
                // window.history.back();
            })
        })
        //   Sweat Alert Toast
        //   Sweat Alert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        @if (session('create'))
            Toast.fire({
            icon: 'success',
            title: '{{ session('create') }}'
            })
        @endif
        @if (session('update'))
            Toast.fire({
            icon: 'success',
            title: '{{ session('update') }}'
            })
        @endif

    </script>
    @yield('scripts')
</body>

</html>
