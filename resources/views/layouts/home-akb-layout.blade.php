<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Si tienes un amigo o algún familiar al que quieras enviar dinero, con nuestra plataforma facilmente puedes hacerlo.">
    <meta property="og:title" content="AKB Fintech - Creciendo contigo" />
    <meta property="og:image" content="{{ asset('assets/img/brand/favicon96x96.png') }}"/>
    <meta property="og:description" content="Si tienes un amigo o algún familiar al que quieras enviar dinero, con nuestra plataforma facilmente puedes hacerlo." />
    <meta name="theme-color" content="#4a47ad">
    <meta name="apple-mobile-web-app-status-bar-style" content="#4a47ad">
    <meta name="msapplication-navbutton-color" content="#4a47ad">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css')}}" />

    <link rel="icon" type="image/png" href="{{ asset('assets/img/brand/favicon16x16.png') }}" sizes="16x16" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/brand/favicon32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/brand/favicon96x96.png') }}" sizes="96x96" />

    <title>AKB Fintech - Creciendo contigo</title>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141290255-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-141290255-1');
    </script> -->

    <!-- Facebook Pixel Code -->
    <!-- <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '400832697428238');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=400832697428238&ev=PageView&noscript=1"/>
    </noscript> -->
    <!-- End Facebook Pixel Code -->

</head>
<body class="bg-white" style="overflow-x: hidden;">
    <div id="app">

      @yield('content')

    </div>

    <!-- Scripts. -->
    <script src="{{url(mix('js/index.js')) }}"></script>
    <script defer src="/assets/js/popper.min.js" charset="utf-8"></script>
    <script defer src="/assets/js/vendors.js" charset="utf-8"></script>
    <script defer src="/assets/js/main.js" charset="utf-8"></script>
</body>
</html>
