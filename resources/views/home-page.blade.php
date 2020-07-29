<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <!-- STYLES -->
    <link rel="stylesheet" href="{{ asset('css/coinbank.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">

    <!-- toastr notifications -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="{{ asset('css/cb-main.css') }}">

    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Mukta:300,400,600,700" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('img/cb-img/favicon.png') }}"/>

    <title>American Kryptos Bank</title>
</head>
<body class="bg-white">
<div id="app">
    <header class="header--home">
        <nav class="d-none d-md-flex navbar navbar-expand navbar-dark bg-none">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto align-items-center">
                        @if(Auth::user() === null)
                            <li class="nav-item">
                                <a href="{{ URL::to('/signin') }}" class="nav-link"><i class="fa fa-user mr-2"></i>Ingresar</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <img src="{{ asset('img/icons/avatar-icon.png') }}" alt="Avatar">
                            </li>
                            <li class="nav-item">
                                @if(Auth::user()->role_id === 5)
                                    <a href="{{URL::to('settings')}}" class="nav-link">{{Auth::user()->name}}</a>
                                @else
                                    <a href="{{URL::to('user-settings')}}" class="nav-link">{{Auth::user()->name}}</a>
                                @endif
                            </li>
                            <li class="nav-item">
                                <a href="{{URL::to('/logout')}}" class="nav-link"><img
                                            src="{{ asset('img/icons/logout-icon.png') }}" alt="Log Out"></a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <section id="logo-section" class="container text-center text-md-left">
            <a href="/" class="navbar-brand mb-3 mb-md-0">
                <img src="{{ asset('img/cb-img/american-kryptos-bank-logo-light.png') }}" alt="Kryptos Bank Logo"
                     title="Kryptos Bank" class="img-fluid">
            </a>
        </section>
        <nav id="mobile-nav" class="mobile-nav d-block d-md-none">
            <div class="container">
                <ul class="navbar-nav my-auto">
                    <li class="nav-item text-center">
                        <a href="{{URL::to('/signin')}}" class="nav-link">
                            <img src="img/landing/user-secondary-icon.svg" alt="">
                            <small class="d-block">Iniciar sesión</small>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <header class="home__hero">
        <div class="container">
            <div class="row mb-4 mb-lg-0 align-items-center">
                <div class="col-6 col-lg-12 px-2">
                    <h1 class="home__hero__title text-primary font-weight-bold mb-0 text-right text-lg-center mr-2 mr-md-0 lh-125 wow fadeIn">
                        Te acercamos a los tuyos</h1>
                </div>
                <div class="col-6 col-lg-12 px-2">
                    <h5 class="home__hero__subtitle text-primary text-left text-lg-center mb-0 mb-md-2 wow fadeIn">Envía
                        dinero a cualquier lugar del mundo con la tasa justa y desde la comodidad de tu silla.</h5>
                </div>
            </div>

            <home-transaction></home-transaction>
        </div>
    </header>

    <section id="market-rate" class="marketRate text-secondary py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-9 mx-auto">
                    <div class="row">
                        <div class="col-6 border-right border-secondary" data-toggle="tooltip" data-placement="top"
                             title="Coming Soon">
                            <div class="d-flex justify-content-center wow fadeInUp">
                                <div class="media flex-column flex-lg-row justify-content-center justify-content-lg-start align-items-center align-items-lg-start">
                                    <div>
                                        <img src="img/landing/sell-bitcoin.svg" alt="Sell Bitcoin Icon"
                                             class="marketRate__img img-fluid mr-lg-4 mb-3 my-lg-auto">
                                    </div>
                                    <div class="media-body text-center marketRate__text">
                                        <h5 class="font-weight-bold">
                                            Vender Bitcoins
                                            <i class="fa fa-angle-right ml-2 d-none d-md-inline-block"></i>
                                        </h5>
                                        <h2 class="mb-0">$ <span class="usdPriceBtcSale">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" data-toggle="tooltip" data-placement="top"
                             title="Coming Soon">
                            <div class="d-flex justify-content-center wow fadeInUp">
                                <div class="media flex-column flex-lg-row justify-content-center justify-content-lg-start align-items-center align-items-lg-start">
                                    <div>
                                        <img src="img/landing/buy-bitcoin.svg" alt="Sell Bitcoin Icon"
                                             class="marketRate__img img-fluid mr-lg-4 mb-3 my-lg-auto">
                                    </div>
                                    <div class="media-body text-center marketRate__text">
                                        <h5 class="font-weight-bold">
                                            Comprar Bitcoins
                                            <i class="fa fa-angle-right ml-2 d-none d-md-inline-block"></i>
                                        </h5>
                                        <h2 class="mb-0">$ <span class="usdPriceBtcBuy">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main>
        <section id="whatwedo" class="py-section-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 offset-lg-1">
                        <h2 class="text-primary font-weight-bold mb-4 mb-md-5 text-center text-md-left wow fadeInUp">
                            Seguridad, Rapidez y Confidencialidad</h2>
                        <p class="text-primary d-none d-md-inline-block wow fadeInUp">Nos preocupamos porque nuestros
                            clientes se sientan satisfechos y conformes con nuestro servicio, siendo la claridad de la
                            información el pilar fundamental de nuestro servicio.</p>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="hexagons-grid mt-5 mt-lg-0">
                            <div class="hexagon--small wow fadeInUp">
                                <img src="img/landing/icon-1.svg" class="img-fluid mb-2">
                                <small class="d-block text-primary">Las mejores tasas de compra y venta de divisas
                                </small>
                            </div>
                            <div class="hexagon--small wow fadeInUp">
                                <img src="img/landing/icon-2.svg" class="img-fluid mb-2">
                                <small class="d-block text-primary">Un trato personalizado</small>
                            </div>
                            <div class="hexagon--small wow fadeInUp">
                                <img src="img/landing/icon-3.svg" class="img-fluid mb-2">
                                <small class="d-block text-primary">Realiza tus operaciones desde cualquier
                                    dispositivo
                                </small>
                            </div>
                            <div class="hexagon--small wow fadeInUp">
                                <img src="img/landing/icon-4.svg" class="img-fluid mb-2">
                                <small class="d-block text-primary">Seguridad en la asesoría de cambios de divisas
                                </small>
                            </div>
                            <div class="hexagon--small wow fadeInUp">
                                <img src="img/landing/icon-5.svg" class="img-fluid mb-2">
                                <small class="d-block text-primary">Diferentes métodos de pagos.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="howwedoit" class="text-white py-4" style="background-color: #0a2a7f;">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-lg-5 offset-lg-1">
                        <img src="img/landing/mujer-laptop.png" alt="Mujer con laptop con coinbank en la pantalla"
                             id="laptop-woman" class="img-fluid">
                        <div id="card-sendmoney" class="card wow fadeInLeft">
                            <div class="card-body px-md-4 py-5">
                                <img src="img/landing/wallet-primary.svg" class="img-fluid mb-2">
                                <div class="mb-3" style="line-height: 1">
                                    <small class="d-block text-primary ml-1">Enviár</small>
                                    <div class="text-primary my-1">2500 USD</div>
                                    <small class="d-block text-primary">A Guillermo en Venezuela</small>
                                </div>
                                <div class="text-center">
                                    <img src="img/landing/send-money-card.png" class="img-fluid">
                                </div>
                                <img src="img/landing/send-btn-rounded.png" class="img-fluid wow fadeInUp"
                                     id="send-rounded">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 offset-sm-7 col-md-6 offset-md-6 col-lg-4 pl-lg-5 pl-xl-3">
                        <h1 class="howWeDoIt__title wow fadeInUp" style="line-height: 1; font-size: 45px"><span
                                    class="font-weight-bold">Cómo lo</span><br><span
                                    class="font-weight-light">hacemos</span></h1>
                        <p class="howWeDoIt__subtitle ml-3 ml-sm-0 wow fadeInUp">Nos preocupamos porque nuestros
                            clientes se sientan satisfechos y conformes con nuestro servicio, siendo la claridad de la
                            información el pilar fundamental de nuestro servicio.</p>
                    </div>
                    <div class="col-md-8 offset-md-4 col-lg-7 offset-lg-5 mt-4">
                        <div id="steps-row" class="row">
                            <div class="col-6 offset-6 col-md-4 offset-md-0 wow fadeInUp">
                                <div class="text-center mb-3">
                                    <img src="img/landing/icon-secondary-1.png">
                                </div>
                                <h6 class="text-secondary text-uppercase">Ingrese el monto</h6>
                                <p class="font-14 lh-125">Defina la moneda y el país al que quiere enviar dinero, de
                                    inmediato se mostrará la tasa de cambio y el valor que recibirá el destinatario.</p>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 wow fadeInUp">
                                <div class="text-center mb-3">
                                    <img src="img/landing/icon-secondary-2.png">
                                </div>
                                <h6 class="text-secondary text-uppercase">Ingrese los datos de familiares o amigos</h6>
                                <p class="font-14 lh-125">Elija la persona o empresa a la que desea enviar dinero o
                                    ingrese los datos y la cuenta del nuevo usuario</p>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 wow fadeInUp">
                                <div class="text-center mb-3">
                                    <img src="img/landing/icon-secondary-3.png">
                                </div>
                                <h6 class="text-secondary text-uppercase">Ingrese los datos de su tarjeta o cuenta</h6>
                                <p class="font-14 lh-125">Ingrese los datos de su tarjeta o cuenta bancaria a nuestro
                                    servidor seguro, estos detalles solo serán usados para futuras transacciones.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="tasas-de-cambio" class="jumbotron py-4">
            <div class="container">
                <h4 class="text-primary font-weight-bold text-center mb-4">Tasas de cambio</h4>
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="row">
                            <div class="col-4 col-md-2 text-secondary mb-3 mb-md-0 border-right border-primary wow fadeIn">
                                <div class="small font-weight-bold lh-125">EUR/VES</div>
                                <h5 class="font-weight-bold lh-125 mb-0">3.758,94</h5>
                            </div>
                            <div class="col-4 col-md-2 text-secondary mb-3 mb-md-0 border-right border-primary wow fadeIn">
                                <div class="small font-weight-bold lh-125">USD/VES</div>
                                <h5 class="font-weight-bold lh-125 mb-0">3.758,94</h5>
                            </div>
                            <div class="col-4 col-md-2 text-secondary mb-3 mb-md-0 border-right border-primary wow fadeIn">
                                <div class="small font-weight-bold lh-125">BTC/VES</div>
                                <h5 class="font-weight-bold lh-125 mb-0">3.758,94</h5>
                            </div>
                            <div class="col-4 col-md-2 text-secondary mb-3 mb-md-0 border-right border-primary wow fadeIn">
                                <div class="small font-weight-bold lh-125">EUR/VES</div>
                                <h5 class="font-weight-bold lh-125 mb-0">3.758,94</h5>
                            </div>
                            <div class="col-4 col-md-2 text-secondary mb-3 mb-md-0 border-right border-primary wow fadeIn">
                                <div class="small font-weight-bold lh-125">USD/VES</div>
                                <h5 class="font-weight-bold lh-125 mb-0">3.758,94</h5>
                            </div>
                            <div class="col-4 col-md-2 text-secondary mb-3 mb-md-0 wow fadeIn">
                                <div class="small font-weight-bold lh-125">BTC/VES</div>
                                <h5 class="font-weight-bold lh-125 mb-0">3.758,94</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="how-to-help" class="py-section-3">
            <div class="container">
                <h1 class="text-primary font-weight-bold text-center wow fadeIn">¿Cómo podemos ayudarte?</h1>
                <div class="text-center mb-5 wow fadeIn">Consulta nuestras preguntas frecuentes<span
                            class="d-none d-md-inline">, estamos seguros que encontrarás lo que buscas</span></div>
                <div class="row">
                    <div class="col-lg-8 mx-auto">

                        <div class="accordion" id="faqs-accordion">
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingOne" data-toggle="collapse"
                                     data-target="#questionOne" aria-expanded="false" aria-controls="questionOne">
                                    <h6 class="float-left mb-0">¿A qué países puedo enviar?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>

                                <div id="questionOne" class="collapse" aria-labelledby="headingOne"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Envía dinero a tus amigos y familiares desde tu casa y ellos lo recibirán
                                        directamente en su cuenta bancaria, solo debes seguir los pasos y tener
                                        habilitada tu cuenta o tarjeta.
                                    </div>
                                </div>
                            </div>

                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingTwo" data-toggle="collapse"
                                     data-target="#questionTwo" aria-expanded="false" aria-controls="questionTwo">
                                    <h6 class="float-left mb-0">¿Qué bancos aceptan?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionTwo" class="collapse" aria-labelledby="headingTwo"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Envía dinero a tus amigos y familiares desde tu casa y ellos lo recibirán
                                        directamente en su cuenta bancaria, solo debes seguir los pasos y tener
                                        habilitada tu cuenta o tarjeta.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingThree" data-toggle="collapse"
                                     data-target="#questionThree" aria-expanded="false" aria-controls="questionThree">
                                    <h6 class="float-left mb-0">Acerca de la comisión del servicio</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionThree" class="collapse" aria-labelledby="headingThree"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Envía dinero a tus amigos y familiares desde tu casa y ellos lo recibirán
                                        directamente en su cuenta bancaria, solo debes seguir los pasos y tener
                                        habilitada tu cuenta o tarjeta.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingFour" data-toggle="collapse"
                                     data-target="#questionFour" aria-expanded="false" aria-controls="questionFour">
                                    <h6 class="float-left mb-0">Acerca de la comisión del servicio</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionFour" class="collapse" aria-labelledby="headingFour"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Envía dinero a tus amigos y familiares desde tu casa y ellos lo recibirán
                                        directamente en su cuenta bancaria, solo debes seguir los pasos y tener
                                        habilitada tu cuenta o tarjeta.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingFive" data-toggle="collapse"
                                     data-target="#questionFive" aria-expanded="true" aria-controls="questionFive">
                                    <h6 class="float-left mb-0">¿Cómo funciona?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionFive" class="collapse show" aria-labelledby="headingFive"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Envía dinero a tus amigos y familiares desde tu casa y ellos lo recibirán
                                        directamente en su cuenta bancaria, solo debes seguir los pasos y tener
                                        habilitada tu cuenta o tarjeta.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--  <section id="clients" class="py-section-2">
              <div class="container">
                  <h1 class="text-primary font-weight-bold text-center mb-5 wow fadeIn">Nuestros clientes felices,<br> confirman nuestro propósito</h1>
                  <div class="row">
                      <div class="col-lg-8 mx-auto">
                          <div id="clients-slider" class="pb-4 wow fadeIn">
                              <div class="text-center text-primary px-3 px-lg-4">
                                  <img src="img/landing/cliente01.png" alt="" class="img-fluid mx-auto mb-2">
                                  <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                                  <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                              </div>
                              <div class="text-center text-primary px-3 px-lg-4">
                                  <img src="img/landing/cliente02.png" alt="" class="img-fluid mx-auto mb-2">
                                  <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                                  <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                              </div>
                              <div class="text-center text-primary px-3 px-lg-4">
                                  <img src="img/landing/cliente03.png" alt="" class="img-fluid mx-auto mb-2">
                                  <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                                  <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                              </div>
                              <div class="text-center text-primary px-3 px-lg-4">
                                  <img src="img/landing/cliente01.png" alt="" class="img-fluid mx-auto mb-2">
                                  <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                                  <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                              </div>
                              <div class="text-center text-primary px-3 px-lg-4">
                                  <img src="img/landing/cliente03.png" alt="" class="img-fluid mx-auto mb-2">
                                  <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                                  <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                              </div>
                              <div class="text-center text-primary px-3 px-lg-4">
                                  <img src="img/landing/cliente02.png" alt="" class="img-fluid mx-auto mb-2">
                                  <h6 class="font-weight-bold mb-4">Sophia Coppola</h6>
                                  <p class="font-14 lh-125">Recibí mi dinero de manera instantanea, muchas gracias.</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </section> -->
    </main>

    <footer class="py-section-3 pb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="text-center">
                        <img src="{{ asset('img/cb-img/american-kryptos-bank.png') }}" alt="Kryptos Bank Logo"
                             class="img-fluid mb-4 wow fadeInUp" style="max-height: 55px">
                        <div class="wow fadeInUp"><span class="text-primary font-weight-bold">Creciendo</span> <span
                                    class="text-secondary">Contigo.</span></div>
                    </div>
                    <div class="row py-5">
                        <div class="col-md-4">
                            <div class="media mb-3 mb-md-0 wow fadeInUp">
                                <i class="fa fa-phone fa-3x text-primary mr-3"></i>
                                <div class="media-body my-auto">
                                    <h5 class="text-primary">
                                        <a href="tel:+1(786)245-8123">+1 (786) 245-8123</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="media mb-3 mb-md-0 wow fadeInUp">
                                <i class="fa fa-envelope fa-3x text-primary mr-3"></i>
                                <div class="media-body my-auto">
                                    <h5 class="text-primary">
                                        <a href="mailto:info@americankryptosbank.com">info@americankryptosbank.com</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="media mb-3 mb-md-0 wow fadeInUp">
                                <i class="fa fa-map-marker fa-3x text-primary mr-3"></i>
                                <div class="media-body my-auto">
                                    <h5 class="text-primary text-uppercase">3517 Nw 115th Ave. Doral FL 33178</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-primary py-5">
            <div class="text-center">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item wow fadeIn">
                        <a href="" class="text-light">
                              <span class="fa-stack fa-lg">
                                  <i class="fa fa-circle fa-stack-2x"></i>
                                  <i class="fa fa-facebook fa-stack-1x text-primary"></i>
                              </span>
                        </a>
                    </li>
                    <li class="list-inline-item wow fadeIn">
                        <a href="" class="text-light">
                              <span class="fa-stack fa-lg">
                                  <i class="fa fa-circle fa-stack-2x"></i>
                                  <i class="fa fa-instagram fa-stack-1x text-primary"></i>
                              </span>
                        </a>
                    </li>
                    <li class="list-inline-item wow fadeIn">
                        <a href="" class="text-light">
                              <span class="fa-stack fa-lg">
                                  <i class="fa fa-circle fa-stack-2x"></i>
                                  <i class="fa fa-twitter fa-stack-1x text-primary"></i>
                              </span>
                        </a>
                    </li>
                    <li class="list-inline-item wow fadeIn">
                        <a href="" class="text-light">
                              <span class="fa-stack fa-lg">
                                  <i class="fa fa-circle fa-stack-2x"></i>
                                  <i class="fa fa-linkedin fa-stack-1x text-primary"></i>
                              </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    {{$orders}}
    @if(isset($orders))
        <section id="chat" class="chat--container">

            @foreach ($orders as $order)

                <order-transaction-chat :order="{{json_encode($order)}}"
                                        :user="{{Auth::user()}}"></order-transaction-chat>

            @endforeach

        </section>
    @endif

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

<!--    <script type="text/javascript">
      // CHAT
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

      });

    </script> -->

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
                    let salePrice = response.data.price - (response.data.price * 4 / 100),
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

</script>
</body>
</html>
