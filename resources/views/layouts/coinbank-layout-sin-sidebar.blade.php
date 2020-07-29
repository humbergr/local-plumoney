<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ url(mix('js/admin.js')) }}" defer></script>

    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <!-- STYLES -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/coinbank.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/cb-main.css') }}">
    @yield('css')
    <!-- toastr notifications -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0033b5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">

    <title>
        @php
        if (!isset($title)) {
            echo 'American Kryptos Bank - Administration';
        } else {
            echo $title;
        }
        @endphp
    </title>
</head>

<body class="admin-dashboard {{Auth::user()->role_id === 9 || Auth::user()->role_id === 4 ||
    Auth::user()->role_id === 1|| Auth::user()->role_id === 6 ? 'asidebar--open' : ''}}">

    
    <div class="" id="app">
        @include('layouts.akb-header')

        <main class="">

            <admin-users-states></admin-users-states>
        </main>

    </div>



    <div class="" id="">
        @include('layouts.akb-header')

        <main class="">

           @yield('content')

       </main>

   </div>

   <!-- SCRIPTS -->
   <script src="/js/jquery.min.js" charset="utf-8"></script>
    {{--
<script src="/js/popper.min.js" charset="utf-8"></script>
--}}
    {{--
<script src="/js/bootstrap.min.js" charset="utf-8"></script>
--}}
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

@yield('js')



</body>

</html>