<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--  <title>Under Construction | Foundation for Sites</title> -->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ url(mix('js/admin.js')) }}" defer></script>

    <!-- Fonts -->
    <!--  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,500,700" rel="stylesheet"> -->
    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dash.css') }}" rel="stylesheet">
</head>
<body class="index default">

@include('layouts.header')

<section class="head-1">
    <div class="grid-container">
        <div class="grid-x">
            <div class="cell"><a href="/"><img src="{{ asset('img/plumoney-grey.svg') }}"
                                               alt="American Kryptos Bank"></a></div>
        </div>
    </div>
</section>

<div class="main-view">
    <div class="wallet-zone">
        <div class="grid-container">
            <div class="grid-x">
                <div class="cell">
                    <img src="{{ asset('img/icons/wallet-icon.png') }}" alt="Wallet">
                    <p style="margin-right:10px">
                        In your wallet
                        <strong>{{ $balance }} BTC</strong>
                    </p>
                    <img src="{{ asset('img/icons/wallet-icon.png') }}" alt="Wallet">
                    <p style="margin-right:10px">
                        In your wallet
                        <strong>{{ number_format($ves->balance, 2) }} VES</strong>
                    </p>
                    <img src="{{ asset('img/icons/wallet-icon.png') }}" alt="Wallet">
                    <p style="margin-right:10px">
                        In your wallet
                        <strong>{{ number_format($usd->balance, 2) }} USD</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="app">
        @yield('content')
    </div>

</div>

<script src="{{ asset('js/fundation.js') }}"></script>
</body>
</html>
