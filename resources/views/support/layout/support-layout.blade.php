<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <meta name="description"
            content="Centro de soporte, ayuda.">
        <meta property="og:image" content="https://www.americankryptosbank.com/android-chrome-512x512.png"/>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- STYLES -->
        <link rel="stylesheet" href="{{ asset('css/coinbank.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cb-main.css') }}">
        <!-- google fonts -->
        <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet">
        <title>Centro de Soporte - American Kryptos Bank</title>
    </head>
    <body>
        <div id="app">
            <div class="sides-overlay"></div>
            @include('support.layout.support-header')
            @yield('content')
            @include('layouts.mvp-footer')
        </div>
        <script src="{{url(mix('js/app.js')) }}"></script>
        @include('notifications-creator')
    </body>
    <script>
        $(document).ready(function () {
            // sidebar menu mobile
            // $(function() {
                const body = $('body');
                const sidebarToggler = $('.sidebar__toggler');
                const sidebarTogglerTarget = $('.sidebar__toggler').data('target');
                const sidesOverlay = $('.sides-overlay');

                $('.sidebar__toggler').click(function() {
                    $(sidebarTogglerTarget).toggleClass('--open');
                    body.toggleClass('sidebar--open');
                    sidebarToggler.attr('data-open', sidebarToggler.attr('data-open') == 'true' ? 'false' : 'true');
                });

                // close sidebar when clicking outside
                sidesOverlay.click(function() {
                    body.removeClass('sidebar--open');
                    $(sidebarTogglerTarget).removeClass('--open');
                    sidebarToggler.attr('data-open', 'false');
                });

                // clone nav links from desktpop navbar
                let navbarItems = $('#desktop-navbar').children().clone();

                $('#clone-desktop-nav').append(navbarItems).children().each(function() {
                    $(this).addClass('sidebar__link');
                    $(this).removeClass('nav-link');
                });
            // });
        });
    </script>
</html>