<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <meta name="description"
          content="Si tienes un amigo o algún familiar al que quieras enviar dinero, con nuestra plataforma facilmente puedes hacerlo.">
    <meta property="og:image" content="https://www.americankryptosbank.com/android-chrome-512x512.png"/>

    <!-- Scripts -->

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

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141290255-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-141290255-1');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
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
    </noscript>
    <!-- End Facebook Pixel Code -->


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

    @include('layouts.mvp-header')

    @yield('content')

    @include('layouts.mvp-footer')

</div>
<style>
    .nav .dropdown-submenu .dropdown-toggle:hover + ul.dropdown-menu {
        display: block;
        left: 98%;
    }

    ul.dropdown-menu:hover {
        display: block;
        left: 98%;
    }

    /*TEMP*/
    .slick-prev {
        left: 25px;
        z-index: 99;
    }

    .slick-next {
        right: 25px;
        z-index: 99;
    }
</style>

<!-- SCRIPTS -->
{{--<script src="/js/jquery.min.js" charset="utf-8"></script>--}}

<script src="{{url(mix('js/app.js')) }}"></script>
<script src="/js/Chart.min.js" charset="utf-8"></script>
<script src="/js/bootstrap-datepicker.min.js" charset="utf-8"></script>
<script src="/js/bootstrap-datepicker.es.min.js" charset="utf-8"></script>
<script src="/js/slick.js" charset="utf-8"></script>
<script src="/js/wow.min.js" charset="utf-8"></script>
<script src="/js/cb.js" charset="utf-8"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBihZBLEXrE96xcxHVBJHWoopFNofxhNdI&callback=mainMap"></script>
@include('notifications-creator')

<script>
    function mainMap() {

        let oficinaPrincipal = {lat: 29.7610825, lng: -95.3670514};

        // map options
        let settings = {
            zoom: 13,
            center: oficinaPrincipal,
            zoomControl: false,
            fullscreenControl: false,
            mapTypeControl: false,
            streetViewControl: false,
            styles:
                [
                    {
                        "featureType": "all",
                        "elementType": "all",
                        "stylers": [
                            {
                                "hue": "#008eff"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [
                            {
                                "saturation": -70
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [
                            {
                                "visibility": "simplified"
                            },
                            {
                                "saturation": -60
                            }
                        ]
                    }
                ]
        };

        if (document.getElementById('main-office')) {
            let mapOficinaPrincipal = new google.maps.Map(document.getElementById('main-office'), settings);
        }
    }
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // HERO SLIDER
    $('#hero-slider').slick({
        dots: false,
        arrows: true,
        infinite: true,
        speed: 1000,
        // fade: true,
        autoplay: true,
        autoplaySpeed: 6000,
        draggable: false,
        pauseOnHover: true,
        pauseOnFocus: false,
        cssEase: 'ease',
        // customPaging: function (slider, i) {
        //     let title = $(slider.$slides[i]).data('title');
        //     let img   = $(slider.$slides[i]).data('img');
        //     //return '<a class="btn-slider btn-block text-truncate h-100"><img class="d-block mx-auto" style="height: 42px" src="' + img + '"><hr class="my-1">' + title + '</a>';
        //     return '<a class="btn-slider btn-block h-100 d-flex align-items-center p-2" data-toggle="tooltip" title="' + title + '"><div class="flex-grow-1"><img class="d-block  mx-auto" style="max-width: 100%; height: 25px;" src="' + img + '"></div></a>';
        // },
        // appendDots: $('#home-slider-dots'),
    });

    // HOW WE DO IT SLIDER
    $('#howwedoit-slider').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 1000,
        fade: true,
        autoplay: true,
        draggable: false,
        pauseOnHover: true,
        pauseOnFocus: false,
        cssEase: 'ease',
        customPaging: function (slider, i) {
            let title = $(slider.$slides[i]).data('title');
            return '<a class="btn-slider btnSliderLoader btn-block text-truncate h-100 py-md-4">' + title + '</a>';
        },
        appendDots: $('#howwedoit-slider-dots'),
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
        let actives = $(this).find('.collapse.show'),
            hasData;

        if (actives && actives.length) {
            hasData = actives.data('collapse')
            if (hasData && hasData.transitioning) return
            actives.collapse('hide')
            hasData || actives.data('collapse', null)
        }
    });

    $('.navbar-submenu .collapse .dropdown-menu').on('click', function (event) {
        event.stopPropagation();
    });
</script>
<script>
    // FLAG SELECT
    $(document).ready(function () {
        $('select.flag-selector').change(function () {
            let flag = $('option:selected', this).data('flag');
            $(this).css('background-image', 'url(' + flag + ')');
        }).change();
    });
</script>
<script>
    $(document).ready(function () {
        // perfect-scrollbar plugin for chat scroll
        // const chatPs = new PerfectScrollbar('.oChat__body', {
        //     wheelSpeed: .75,
        //     wheelPropagation: false,
        //     minScrollbarLength: 20
        // });
        //
        // // update scrollbar size when new messages are added
        // chatPs.update();

        // upload voucher
        $(function () {
            let inputVoucher = $("._dynamic_file_input");

            inputVoucher.on("change", function () {
                let fileName     = $(this)[0].files[0].name,
                    accountID    = $(this).attr('data-account-id'),
                    selectedFile = $(this).siblings('.__filename');

                selectedFile.children("div").remove();
                selectedFile.append("<div class='small text-truncate text-muted text-right'>" + fileName + "</div>");
                $("#inputFile--selected-" + accountID).addClass("d-block");
                $("#pay-input-" + accountID).fadeIn(1000);
            })
        });

        // mobile - upload voucher
        $(function () {
            mobileBtnVoucher   = $("#upload-voucher-mobile");
            mobileInputVoucher = $("#photo-voucher-input-mobile");
            mobileSelectedFile = $("#selected-file-name-mobile");

            mobileBtnVoucher.click(function () {
                mobileInputVoucher.click();

                mobileInputVoucher.on("change", function () {
                    mobileFileName = $("#photo-voucher-input-mobile")[0].files[0].name;

                    mobileSelectedFile.text(mobileFileName);
                    $("#upload-voucher-mobile-icon").hide();
                    mobileBtnVoucher.removeClass("btn-secondary").addClass("btn-light");
                    $("#fileUploaded-check").toggleClass("d-none");
                })
            });
        });

        // mobile - transaction info and chat tabs
        // $(function () {
        //     let chatToggler = $("#transactionChat-tab");
        //     let transChat   = $("#transaction-chat");
        //     let transInfo   = $("#transaction-info");
        //
        //     transInfo.hide();
        //
        //     chatToggler.click(function () {
        //         let chatTogglerText = $('#transactionChat-tab .media-body').html();
        //         transChat.toggleClass("d-none");
        //         transInfo.toggleClass("d-block");
        //
        //         if (chatTogglerText === "Habla con tu Asesor") {
        //             $('#transactionChat-tab .media-body').html("Continuar con los pagos");
        //             $("#chatTab-icon").find("img").attr("src", "/img/landing/cash-light.svg");
        //
        //             if (window.matchMedia("(max-width: 640px)").matches) {
        //                 let chatTop = $('#transaction-chat').offset().top - 40;
        //                 $("html, body").stop().animate({scrollTop: chatTop}, 800, 'swing');
        //             }
        //
        //             // $('#transactionChat-tab').find("#talkAdvisor-icon").hide();
        //         } else {
        //             $('#transactionChat-tab .media-body').html("Habla con tu Asesor");
        //             $("#chatTab-icon").find("img").attr("src", "/img/landing/msg-icon-light.svg");
        //
        //             if (window.matchMedia("(max-width: 640px)").matches) {
        //                 let chatTop = $('#transaction-info').offset().top - 100;
        //                 $("html, body").stop().animate({scrollTop: chatTop}, 800, 'swing');
        //             }
        //
        //             // $('#transactionChat-tab').find("#talkAdvisor-icon").show();
        //         }
        //     })
        // });
    });
</script>
</body>
</html>
