<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>American Kryptos Bank</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbNcXJyuUzJg--_ZWtLQps6YD9ndXR_Ks&libraries=places"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" >
    <link href="https://fonts.googleapis.com/css?family=Nunito|Mukta:300,400,700|Poppins:400,500,700"
          rel="stylesheet"
          type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/coinbank.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">


    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0033b5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
</head>
<body>

<div id="app">
    @include('user.merchant-header')
    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                @if(Session::has('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Error!</strong> {{ Session::get('error') }}
                        </div>
                    </div>
                @elseif(Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong> {{ Session::get('success') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @yield('content')
    </main>
</div>

@include('notifications-creator')
</body>
</html>
