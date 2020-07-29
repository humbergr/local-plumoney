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
    <!-- toastr notifications -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0033b5">
    @yield('css')
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

    <!--@if(Auth::user()->role_id == 4)-->
    <!--    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>-->
    <!--    <script>-->
    <!---->
    <!--        Notification.requestPermission().then(function (result) {-->
    <!--            console.log(result);-->
    <!--        });-->
    <!--        // Enable pusher logging - don't include this in production-->
    <!--        Pusher.logToConsole = true;-->
    <!---->
    <!--        var pusher = new Pusher('889fce6a69a9c7050bd3', {-->
    <!--            cluster: 'us2',-->
    <!--            forceTLS: true-->
    <!--        });-->
    <!---->
    <!--        var channel = pusher.subscribe('my-channel');-->
    <!--        channel.bind('notification-to-{{Auth::user()->id}}', function (data) {-->
    <!---->
    <!--            // Let's check if the browser supports notifications-->
    <!--            if (!("Notification" in window)) {-->
    <!--                console.warn("This browser does not support system notifications");-->
    <!--            }-->
    <!---->
    <!--            // Let's check whether notification permissions have already been granted-->
    <!--            else if (Notification.permission === "granted") {-->
    <!--                // If it's okay let's create a notification-->
    <!--                var notification     = new Notification('CoinBank', {-->
    <!--                    body: data.message,-->
    <!--                    icon: "{{ asset('assets/images/favicon.ico') }}",-->
    <!--                });-->
    <!--                notification.onclick = function (event) {-->
    <!--                    event.preventDefault(); // prevent the browser from focusing the Notification's tab-->
    <!--                    window.open('workroom');//, '_blank');-->
    <!--                    notification.close();-->
    <!--                }-->
    <!--            }-->
    <!--        });-->
    <!--    </script>-->
    <!--@endif-->

    <div class="" id="app">
        @include('layouts.akb-header')

        <main class="dashboard__main">

            <admin-users-states></admin-users-states>

            @yield('content')

        </main>

        @if(Auth::user()->role_id == 4)
        <chat-dashboard :user="{{Auth::user()}}"></chat-dashboard>
        @endif

        @if(Auth::user()->role_id == 9)
        <wallets-chat-dashboard :user="{{Auth::user()}}"></wallets-chat-dashboard>
        @endif

        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 6)
        <admin-chat-dashboard :user="{{Auth::user()}}"></admin-chat-dashboard>
        @endif

        <!--    @if(Auth::user()->role_id == 4 && isset($ads))-->
        <!--        <!-- Traders Chat - Prob. Deprecated-->-->
        <!--        <section class="chat--container">-->
        <!--            @foreach ($ads as $ad)-->
        <!--                @if ($ad->contact_id !== null)-->
        <!--                    <chat :ad="{{json_encode($ad)}}"></chat>-->
        <!--                @endif-->
        <!--            @endforeach-->
        <!--        </section>-->
        <!--    @endif-->

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {
         $('#banco').select2({
         theme: "bootstrap"
          });
    });
        $(document).ready(function() {

            // collapse chat tab
            $(".chat__header").on("click", function() {
                if ($(this).closest(".chat__tab").hasClass("collapsed")) {
                    $(this).closest(".chat__tab").removeClass("collapsed");
                } else {
                    $(this).closest(".chat__tab").addClass("collapsed");
                }
            });

            // close chat tab
            $(".chat__tab .close").on("click", function() {
                $(this).closest(".chat__tab").remove();
                $(".chat__tab .close").tooltip('hide');
            });

            $('[data-toggle="tooltip"]').tooltip();
        });

        // scroll chat to bottom
        $('#chat-body').scrollTop($('#chat-body').prop('scrollHeight'));
    </script>
    <script>
        // FLAG SELECT
        $(document).ready(function() {
            $('select.flag-selector').change(function() {
                let flag = $('option:selected', this).data('flag');
                $(this).css('background-image', 'url(' + flag + ')');
            }).change();
        });
    </script>

    <script>
        $(document).ready(function() {

            //Use the id of the form instead of #change
            $('#change-t-status').change(function() {

                $('.modal-status').modal('show');
                let status_select = '<option value="" disabled selected>Seleccionar Motivo</option>'
                let list_subjects = '';
                let modalEdit = '';
                let asunto = '';
                let categoria = $(this).val();

                $('#status').val(categoria);
                $('#setStatus').val(categoria);
                $.get(window.location.origin + '/statusByID/' + categoria, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    for (let i = 0; i < data.length; i++) {

                        status_select += '<option value="' + data[i].id + '">' + data[i].subject + '</option>';
                        list_subjects += `<tr>
                                    <td>${data[i].id}</td>
                                    <td>${data[i].status}</td>
                                    <td>${data[i].subject}</td>
                                    <th>
                                        <a data-toggle="modal" data-target="#edit-modal-${data[i].id}" href="#">Editar</a>
                                        -
                                        <a href="${window.location.origin}/delete-subject/${data[i].id}">Borrar</a>
                                    </th>
                                </tr>`;
                        modalEdit += `<div class="modal fade" id="edit-modal-${data[i].id}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title text-primary font-weight-bold">Editar</h6>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="container align-content-center">
                                                        <h5>Editar</h5>
                                                        <div class="container py-3">
                                                            <form action="${window.location.origin}/edit-subject/${data[i].id}"" method="post" enctype="multipart/form-data">
                                                                {{ csrf_field() }}
                                                                <div class="form-group">
                                                                    <input type="hidden" name="status" value="${data[i].status}" />
                                                                    <input class="form-control" type="text" name="subject" value="${data[i].subject}" />
                                                                </div>
                                                                <button type="submit" class="btn btn-success rounded-0">Guardar</button>
                                                                <button class="btn btn-light rounded-0" data-dismiss="modal">Cancelar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    }
                    if (categoria == 0) {
                        asunto = `Mensaje - Open`;

                    }
                    if (categoria == 1) {
                        asunto = `Mensaje - Approved`;

                    }
                    if (categoria == 2) {
                        asunto = `Mensaje - Rejected`;

                    }
                    if (categoria == 4) {
                        asunto = `Mensaje -  In Process`;
                    }
                    if (categoria == 5) {
                        asunto = `Mensaje -  Refund`;
                    }
                    $("#subjects").html(status_select);
                    $('#list_subjects').html(list_subjects);
                    $('#modal-edit').html(modalEdit);
                    $('#modal-title-status').html(asunto);
                });
            });

            $('#change-t2-status').change(function() {

                $('.modal-status-incoming').modal('show');
                let status_select = '<option value="" disabled selected>Seleccionar Motivo</option>'
                let list_subjects = '';
                let modalEdit = '';
                let asunto = '';
                let categoria = $(this).val();

                $('#status').val(categoria);
                $('#setStatus').val(categoria);
                $.get(window.location.origin + '/wallet/statusByID/' + categoria, function(data) {
                    //esta el la peticion get, la cual se divide en tres partes. ruta,variables y funcion
                    console.log(data);
                    for (let i = 0; i < data.length; i++) {

                        status_select += '<option value="' + data[i].id + '">' + data[i].subject + '</option>';
                        list_subjects += `<tr>
                                            <td>${data[i].id}</td>
                                            <td>${data[i].status}</td>
                                            <td>${data[i].subject}</td>
                                            <th>
                                                <a data-toggle="modal" data-target="#edit-modal-${data[i].id}" href="#">Editar</a>
                                                -
                                                <a href="${window.location.origin}/wallet/delete-subject/${data[i].id}">Borrar</a>
                                            </th>
                                        </tr>`;
                        modalEdit += `<div class="modal fade" id="edit-modal-${data[i].id}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title text-primary font-weight-bold">Editar</h6>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <div class="container align-content-center">
                                                            <h5>Editar</h5>
                                                            <div class="container py-3">
                                                                <form action="${window.location.origin}/wallet/edit-subject/${data[i].id}"" method="post" enctype="multipart/form-data">
                                                                    {{ csrf_field() }}
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="status" value="${data[i].status}" />
                                                                        <input class="form-control" type="text" name="subject" value="${data[i].subject}" />
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success rounded-0">Guardar</button>
                                                                    <button class="btn btn-light rounded-0" data-dismiss="modal">Cancelar</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                    }
                    if (categoria == 1) {
                        asunto = `Mensaje - Waiting`;

                    }
                    if (categoria == 2) {
                        asunto = `Mensaje - Approved`;

                    }
                    if (categoria == 3) {
                        asunto = `Mensaje - Failed`;

                    }
                    if (categoria == 4) {
                        asunto = `Mensaje -  Rejected`;
                    }
                    if (categoria == 5) {
                        asunto = `Mensaje -  Refund`;
                    }
                    if (categoria == 6) {
                        asunto = `Mensaje -  In Process`;
                    }
                    $("#subjects").html(status_select);
                    $('#list_subjects').html(list_subjects);
                    $('#modal-edit').html(modalEdit);
                    $('#modal-title-status').html(asunto);
                });
            });
        });
    </script>

    <script>
        $(document).ready(function(){
        
            $('.popup-gallery').magnificPopup({
                delegate: 'a',
                type: 'image',
                tLoading: 'Loading image #%curr%...',
                mainClass: 'mfp-img-mobile',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                },
                image: {
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                    titleSrc: function(item) {
                        return item.el.attr('title') + '<small>AKB</small>';
                    }
                }
            });

            $('#edit-profile').on("click", function(e) {
                e.preventDefault();
                $('.edit-userprofile').removeAttr('disabled');
                $('#edit-profile').attr('hidden', 'true');
                $('#save-edit-profile').removeAttr('hidden');
                $('#cancel-edit-profile').removeAttr('hidden');
            });

            $('#cancel-edit-profile').on("click", function(e) {
                e.preventDefault();
                $('.edit-userprofile').attr('disabled', 'true');
                $('#edit-profile').removeAttr('hidden');

                $('#save-edit-profile').attr('hidden', 'true');
                $('#cancel-edit-profile').attr('hidden', 'true');
            });

            $('.subjects-multiple').select2();


        
        });
    </script>

    <script>
        $(document).ready(function(){
            $('#creation-date-filteer').daterangepicker({
                opens: 'center'
            });
            $('input[name="chart-date"]').daterangepicker({
                opens: 'center'
            });
        });
    </script>

    @include('notifications-creator')
    @yield('scripts')
</body>

</html>