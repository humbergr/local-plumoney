<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">

    <!-- Scripts -->

    <script src="{{url(mix('js/app.js')) }}"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- STYLES -->
    <link rel="stylesheet" href="{{ asset('css/coinbank.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">

    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <!-- toastr notifications -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="{{ asset('css/cb-main.css') }}">

    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet">

    <title>American Kryptos Bank</title>



    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0033b5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
</head>
<body class="bg-white" style="overflow-x: hidden;">
    <div id="app">

      @include('layouts.home-header')

      @yield('content')

      @include('layouts.home-footer')

    </div>

    <!-- SCRIPTS -->
    <script src="js/jquery.min.js" charset="utf-8"></script>
    <script src="js/popper.min.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>

    <script src="js/Chart.min.js" charset="utf-8"></script>
    <script src="js/bootstrap-datepicker.min.js" charset="utf-8"></script>
    <script src="js/bootstrap-datepicker.es.min.js" charset="utf-8"></script>
    <script src="js/slick.js" charset="utf-8"></script>
    <script src="js/wow.min.js" charset="utf-8"></script>
    @include('notifications-creator')

    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // FLAG SELECT
        $(document).ready(function () {
            $('.flag-selector').change(function () {
                var flag = $('option:selected', this).data('flag');
                $(this).css('background-image', 'url(' + flag + ')');
            }).change();
        });
    </script>

    <script>
        // HERO SLIDER
        $('#hero-slider').slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 6000,
            fade: true,
            autoplay: true,
            pauseOnHover: false,
            pauseOnFocus: false,
            cssEase: 'ease-out'
        });

        // SLICK SLIDER
        $('#clients-slider').slick({
          dots: true,
          arrows: false,
          infinite: true,
          speed: 300,
          slidesToShow: 3,
          slidesToScroll: 3,
          autoplay: true,
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 576,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
        });

        // WOW.JS - Reveal animations when scrolling
        new WOW().init();

        // open one submenu at a time
        $('.multi__submenu').on('show.bs.collapse', function () {
            var actives = $(this).find('.collapse.show'),
                hasData;

            if (actives && actives.length) {
                hasData = actives.data('collapse')
                if (hasData && hasData.transitioning) return
                actives.collapse('hide')
                hasData || actives.data('collapse', null)
            }
        });

        $('.navbar-submenu .collapse .dropdown-menu').on('click', function(event){
            event.stopPropagation();
        });

    </script>

    <script>
        //Dynamic Prices
        $.get('https://www.bitstamp.net/api/ticker/', function (data) {
            let salePrice = parseFloat(data.last) - (parseFloat(data.last) * 1 / 100),
                buyPrice  = parseFloat(data.last) + (parseFloat(data.last) * 4 / 100);
            $('.usdPriceBtcSale').text(salePrice.toFixed(2));
            $('.usdPriceBtcBuy').text(buyPrice.toFixed(2));
        });

        function initWebsocket() {
            let subscribeMsg = {
                    "event": "bts:subscribe",
                    "data": {
                        "channel": "live_trades_btcusd"
                    }
                },
                ws           = new WebSocket("wss://ws.bitstamp.net");

            ws.onopen = function () {
                ws.send(JSON.stringify(subscribeMsg));
            };

            ws.onmessage = function (evt) {
                let response = JSON.parse(evt.data);
                /**
                 * This switch statement handles message logic. It processes data in case of trade event
                 * and it reconnects if the server requires.
                 */
                switch (response.event) {
                    case 'trade': {
                        let salePrice = response.data.price - (response.data.price * 1 / 100),
                            buyPrice  = response.data.price + (response.data.price * 4 / 100);
                        $('.usdPriceBtcSale').text(salePrice.toFixed(2));
                        $('.usdPriceBtcBuy').text(buyPrice.toFixed(2));
                        break;
                    }
                    case 'bts:request_reconnect': {
                        initWebsocket();
                        break;
                    }
                }

            };
            /**
             * In case of unexpected close event, try to reconnect.
             */
            ws.onclose = function () {
                console.log('Websocket connection closed');
                initWebsocket();
            };

        }

        initWebsocket();
    </script>
</body>
</html>
