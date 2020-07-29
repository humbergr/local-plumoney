@extends('layouts.home-layout')

@section('content')
    <header class="home__hero">

        <div id="hero-slider" class="home__hero__slider">
            <div class="slider__item" style="background-image: url('img/landing/hero-bg.png')"></div>
            <div class="slider__item" style="background-image: url('img/landing/hero-bg-2.png')"></div>
        </div>

        <div class="home-login container w-100 d-none d-lg-block">
            @if(!isset(Auth::user()->id))
                <div class="card card--alpha wow fadeIn" style="max-width: 285px;">
                    <div class="card-body pb-1">
                        <h6 class="text-primary mb-3"><i class="fa fa-user mr-2"></i>Ingresa a tu cuenta</h6>
                        <form method="POST" id="login-form" action="{{ route('login') }}"
                              aria-label="{{ __('Login') }}">
                            @csrf
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user-o text-muted"></i>
                            </span>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                       placeholder="Email">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-lock text-muted"></i>
                            </span>
                                </div>
                                <input type="password" name="password" class="form-control" placeholder="Contraseña">
                            </div>
                            <div class="text-center">
                                <button class="btn btn-secondary btn-pill px-lg-4" type="submit">Ingresar y continuar
                                </button>
                            </div>
                        </form>
                        <div class="clearfix mt-3">
                            <a href="{{URL::to('/signin')}}" class="text-secondary float-left small">Crear cuenta</a>
                            <a href="#!" class="text-secondary float-right small">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="container">
            <div class="row">
                <div @if(!isset(Auth::user()->id)) class="col-md-12 col-lg-6 col-xl-4 ml-md-auto px-0 px-md-3"
                     @else class="col-md-12 col-lg-6 col-xl-6 mx-auto px-0 px-md-3" @endif>
                    <div class="card text-light text-onlymob-primary card--alpha--primary card--alpha--onlymob wow fadeIn">
                        <home-order></home-order>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="market-rate" class="marketRate text-secondary py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-9 mx-auto">
                    <div class="row">
                        <div class="col-6 border-right border-secondary">
                            <div class="d-flex justify-content-center wow fadeInUp">
                                <div class="media flex-column flex-lg-row justify-content-center justify-content-lg-start align-items-center align-items-lg-start">
                                    <div>
                                        <img src="img/landing/sell-bitcoin.svg" alt="Sell Bitcoin Icon"
                                             class="marketRate__img img-fluid mr-lg-4 mb-3 my-lg-auto">
                                    </div>
                                    <div class="media-body text-center marketRate__text">
                                        <h5 class="font-weight-bold">Vender Bitcoins <i
                                                    class="fa fa-angle-right ml-2 d-none d-md-inline-block"></i></h5>
                                        <h2 class="mb-0">$ <span class="usdPriceBtcSale">0.00</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-center wow fadeInUp">
                                <div class="media flex-column flex-lg-row justify-content-center justify-content-lg-start align-items-center align-items-lg-start">
                                    <div>
                                        <img src="img/landing/buy-bitcoin.svg" alt="Sell Bitcoin Icon"
                                             class="marketRate__img img-fluid mr-lg-4 mb-3 my-lg-auto">
                                    </div>
                                    <div class="media-body text-center marketRate__text">
                                        <h5 class="font-weight-bold">Comprar Bitcoins <i
                                                    class="fa fa-angle-right ml-2 d-none d-md-inline-block"></i></h5>
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
                    <div class="col-lg-5 offset-lg-1">
                        <h2 class="text-primary font-weight-bold mb-4 mb-md-5 text-center text-md-left wow fadeInUp">
                            Seguridad, Rapidez y Confidencialidad</h2>
                        <p class="text-primary d-none d-md-inline-block wow fadeInUp" style="margin-bottom: 5rem">
                            Son muchas las oportunidades que te ofrecermos. Si tienes un amigo o algún familiar al que
                            quieras enviar dinero, con nuestra plataforma facilmente puedes hacerlo. Trabajamos con
                            muchos países, Venezuela entre ellos, y con el uso de la red BlockChain garantizamos
                            seguridad y rapidez.
                            <br>
                            <br>
                            Nosotros creemos en las Criptomonedas. Puedes ahorrar en criptos con nosotros, comprarlas o
                            venderlas, tenemos excelentes precios. Si eres un poco más arriesgado, haz crecer tu dinero
                            con nuestros productivos planes de invesión y un mínino esfuerzo; incluso si quieres ahorrar
                            en Euros, Dólares y hasta Libras Esterlinas aprovecha nuestro servicio de Cambio de Divisas.
                            <br>
                            <br>
                            American Kryptos Bank. Creciendo contigo.
                        </p>
                    </div>
                    <div class="col-lg-6">
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

        <section id="clients" class="py-section-2">
            <div class="container">
                <h1 class="text-primary font-weight-bold text-center mb-5 wow fadeIn">Nuestros clientes felices,<br>
                    confirman nuestro propósito</h1>
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
        </section>
    </main>



    <!-- login mobile modal -->
    <div class="modal fade" id="loginMobileModal" tabindex="-1" role="dialog" aria-labelledby="loginMobileModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary font-weight-bold">Ingresa si tienes una cuenta</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4">
                    <form method="POST" id="login-form" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user-o text-muted"></i>
                            </span>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                   placeholder="Email">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-lock text-muted"></i>
                            </span>
                            </div>
                            <input type="password" name="password" class="form-control" placeholder="Contraseña">
                        </div>
                        <div class="text-center">
                            <button class="btn btn-secondary btn-pill">Ingresar y continuar</button>
                        </div>
                        <div class="clearfix mt-3">
                            <a href="{{URL::to('signin')}}" class="text-secondary float-left small">Crear cuenta</a>
                            <a href="#!" class="text-secondary float-right small">¿Olvidaste tu contraseña?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

