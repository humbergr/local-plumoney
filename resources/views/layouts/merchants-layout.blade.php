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
    <link rel="stylesheet" href="{{ asset('css/cb-main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('img/cb-img/favicon.png') }}"/>

    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <!-- toastr notifications -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet">

    <!-- google fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet"> -->

    <title>American Kryptos Bank</title>

    <link rel="icon" type="image/png" href="img/cb-img/favicon.png"/>
</head>
<body class="">

<div id="app">

    @include('layouts.merchants-header')

    @yield('content')

    @include('layouts.merchants-footer')

</div>

<!-- SCRIPTS -->
<script src="{{ asset('js/jquery.min.js') }}" charset="utf-8"></script>

<script src="{{ asset('js/Chart.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/bootstrap-datepicker.es.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/wow.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/perfect-scrollbar.js') }}" charset="utf-8"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- <script src="js/main.js" charset="utf-8"></script> -->

<!--<script type="text/javascript">
      // SLICK SLIDER
      $('#recipients-slider, #payment-slider').slick({
        dots: true,
        infinite: true,
        arrows: false,
        speed: 300,
        autoplay: false,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            }
          },
          {
            breakpoint: 576,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            }
          }
        ]
      });

      // Transactions history slider
      $('#transactions-history').slick({
        vertical: true,
        dots: true,
        arrows: false,
        speed: 300,
        autoplay: false,
        slidesToShow: 6,
        slidesToScroll: 5,
      });
</script> -->
<script>
    // FLAG SELECT
    $(document).ready(function () {
        $('select.flag-selector').change(function () {
            var flag = $('option:selected', this).data('flag');
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
        $(function () {
            chatToggler = $("#transactionChat-tab");
            transChat   = $("#transaction-chat");
            transInfo   = $("#transaction-info");

            transChat.hide();

            chatToggler.click(function () {
                chatTogglerText = $('#transactionChat-tab .media-body').html();
                transInfo.toggleClass("d-none");
                transChat.toggleClass("d-block");

                if (chatTogglerText == "Habla con<br>Tu asesor") {
                    $('#transactionChat-tab .media-body').html("Continuar<br>con el pago");
                    $("#chatTab-icon").find("img").attr("src", "img/landing/cash-light.svg");
                    $('#transactionChat-tab').find("#talkAdvisor-icon").hide();
                } else {
                    $('#transactionChat-tab .media-body').html("Habla con<br>Tu asesor");
                    $("#chatTab-icon").find("img").attr("src", "img/landing/msg-icon-light.svg");
                    $('#transactionChat-tab').find("#talkAdvisor-icon").show();
                }
            })
        });
    });
</script>

@include('notifications-creator')
</body>
</html>
