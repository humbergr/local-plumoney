@extends('layouts.mvp-layout')

@section('content')
    <main>
        <div class="triangle--toRight">
            <section class="py-section-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 offset-md-1 my-auto">
                            <div class="text-center text-md-left wow fadeInUp">
                                <img src="/img/landing/B-secondary.svg" alt="" class="img-fluid ml-md-5 mb-3"
                                     style="height: 74px">
                                <h1 class="text-primary font-weight-bold mb-4 mb-md-5">Compra y venta <span
                                            class="text-secondary">de Criptomonedas</span></h1>
                                <p>Con nuestra plataforma podrás convertir tus criptomonédas a moneda fiduciaria.</p>
                                <a href="javascript:void(0);"
                                   data-toggle="tooltip" title="Coming Soon"
                                   class="btn btn-secondary btn-lg btn-pill px-md-5 mt-4 mb-4 mb-md-0">
                                    <span class="mr-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 31 37"><g><g><g><g><g><g><g><path
                                                                                fill="#fff"
                                                                                d="M10.058 9.006l8.452.002a2.93 2.93 0 0 1 2.929 2.929v.432a2.932 2.932 0 0 1-2.929 2.928h-8.452zm8.452 7.84a4.483 4.483 0 0 0 4.479-4.477v-.432a4.488 4.488 0 0 0-3.704-4.414v-.066H8.508v9.39h6.654zm-8.452 2.243l10.523.002c2.32 0 4.207 1.89 4.207 4.209v.546a4.212 4.212 0 0 1-4.207 4.208H10.058zM20.581 29.6a5.762 5.762 0 0 0 5.757-5.755V23.3a5.765 5.765 0 0 0-5.757-5.76H8.508v12.064h9.794zm-19.03-.065a4.455 4.455 0 0 0 3.672-4.382v-13.25A4.46 4.46 0 0 0 1.55 7.52v-.753h8.766V2.076h1.557v4.692h3.991V2.076h1.558v4.692h3.127v-.004a5.177 5.177 0 0 1 5.172 5.173v.432a5.16 5.16 0 0 1-1.438 3.57l-.835.872 1.143.394a6.454 6.454 0 0 1 4.354 6.095v.546a6.454 6.454 0 0 1-6.45 6.447h-5.073v4.69h-1.558v-4.69h-3.99v4.69h-1.558v-4.69H1.55zm17.42 6.996v-4.687h3.524c4.411 0 8-3.586 8-8V23.3a8.006 8.006 0 0 0-4.402-7.14 6.668 6.668 0 0 0 1.178-3.79v-.433c0-3.448-2.604-6.292-5.947-6.678v-.042h-.65c-.042-.002-.083-.002-.125-.002l-1.577.002V.527h-4.658v4.69h-.89V.527H8.766v4.69H0v3.789h.775c1.598 0 2.898 1.3 2.898 2.897v13.251c0 1.599-1.3 2.9-2.898 2.9H0v3.788h8.766v4.693l4.658-.003v-4.69h.89v4.693z"/></g></g></g></g></g></g></g></svg>                                    </span>
                                    <span class="va-middle">Compra y vende</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7 my-auto px-0">
                            <img src="/img/landing/comprar-vender-btc.png" alt="" class="object-cover wow fadeIn"
                                 style="pointer-events: none; user-select: none; -webkit-user-select: none;">
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-section-3 mb-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3 offset-md-1 my-auto">
                            <div class="text-center text-md-left wow fadeInUp">
                                <h2 class="text-primary font-weight-bold mb-4 mb-md-5">¿Cómo lo hacemos?</h2>
                                <p class="text-primary font-weight-bold h5">En American Kryptos Bank estamos
                                    comprometidos en entregarte el control de tus finanzas. <a href="">Regístrate ya</a>
                                </p>
                                <a href="javascript:void(0);"
                                   data-toggle="tooltip" title="Coming Soon"
                                   class="btn btn-secondary btn-pill d-none d-md-inline-block px-md-5 mt-4 mb-5 mb-md-0">
                                    <span class="mr-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 31 37"><g><g><g><g><g><g><g><path
                                                                                fill="#fff"
                                                                                d="M10.058 9.006l8.452.002a2.93 2.93 0 0 1 2.929 2.929v.432a2.932 2.932 0 0 1-2.929 2.928h-8.452zm8.452 7.84a4.483 4.483 0 0 0 4.479-4.477v-.432a4.488 4.488 0 0 0-3.704-4.414v-.066H8.508v9.39h6.654zm-8.452 2.243l10.523.002c2.32 0 4.207 1.89 4.207 4.209v.546a4.212 4.212 0 0 1-4.207 4.208H10.058zM20.581 29.6a5.762 5.762 0 0 0 5.757-5.755V23.3a5.765 5.765 0 0 0-5.757-5.76H8.508v12.064h9.794zm-19.03-.065a4.455 4.455 0 0 0 3.672-4.382v-13.25A4.46 4.46 0 0 0 1.55 7.52v-.753h8.766V2.076h1.557v4.692h3.991V2.076h1.558v4.692h3.127v-.004a5.177 5.177 0 0 1 5.172 5.173v.432a5.16 5.16 0 0 1-1.438 3.57l-.835.872 1.143.394a6.454 6.454 0 0 1 4.354 6.095v.546a6.454 6.454 0 0 1-6.45 6.447h-5.073v4.69h-1.558v-4.69h-3.99v4.69h-1.558v-4.69H1.55zm17.42 6.996v-4.687h3.524c4.411 0 8-3.586 8-8V23.3a8.006 8.006 0 0 0-4.402-7.14 6.668 6.668 0 0 0 1.178-3.79v-.433c0-3.448-2.604-6.292-5.947-6.678v-.042h-.65c-.042-.002-.083-.002-.125-.002l-1.577.002V.527h-4.658v4.69h-.89V.527H8.766v4.69H0v3.789h.775c1.598 0 2.898 1.3 2.898 2.897v13.251c0 1.599-1.3 2.9-2.898 2.9H0v3.788h8.766v4.693l4.658-.003v-4.69h.89v4.693z"/></g></g></g></g></g></g></g></svg>                                    </span>
                                    <span class="va-middle">Compra y vende</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-5 offset-md-1">
                            <div class="media align-items-center mb-2 wow fadeInUp">
                                <img src="/img/landing/icon350.png" class="img-fluid mr-3 mr-md-4">
                                <div class="media-body text-muted">Crea una cuenta en American Kryptos Bank y sigue el
                                    proceso de registro. <strong>es rápido y seguro</strong>.
                                </div>
                            </div>
                            <div class="media align-items-center mb-2 wow fadeInUp">
                                <img src="/img/landing/icon351.png" class="img-fluid mr-3 mr-md-4">
                                <div class="media-body text-muted">Elige tu forma de pago.</div>
                            </div>
                            <div class="media align-items-center mb-2 wow fadeInUp">
                                <img src="/img/landing/icon352.png" class="img-fluid mr-3 mr-md-4">
                                <div class="media-body text-muted">Y comienza a disfrutar del mundo de las
                                    Criptomonédas
                                </div>
                            </div>
                            <a href="javascript:void(0);"
                               data-toggle="tooltip" title="Coming Soon"
                               class="btn btn-secondary btn-pill px-md-5 mt-4 mb-5 mb-md-0">
                                <span class="mr-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 31 37"><g><g><g><g><g><g><g><path
                                                                            fill="#fff"
                                                                            d="M10.058 9.006l8.452.002a2.93 2.93 0 0 1 2.929 2.929v.432a2.932 2.932 0 0 1-2.929 2.928h-8.452zm8.452 7.84a4.483 4.483 0 0 0 4.479-4.477v-.432a4.488 4.488 0 0 0-3.704-4.414v-.066H8.508v9.39h6.654zm-8.452 2.243l10.523.002c2.32 0 4.207 1.89 4.207 4.209v.546a4.212 4.212 0 0 1-4.207 4.208H10.058zM20.581 29.6a5.762 5.762 0 0 0 5.757-5.755V23.3a5.765 5.765 0 0 0-5.757-5.76H8.508v12.064h9.794zm-19.03-.065a4.455 4.455 0 0 0 3.672-4.382v-13.25A4.46 4.46 0 0 0 1.55 7.52v-.753h8.766V2.076h1.557v4.692h3.991V2.076h1.558v4.692h3.127v-.004a5.177 5.177 0 0 1 5.172 5.173v.432a5.16 5.16 0 0 1-1.438 3.57l-.835.872 1.143.394a6.454 6.454 0 0 1 4.354 6.095v.546a6.454 6.454 0 0 1-6.45 6.447h-5.073v4.69h-1.558v-4.69h-3.99v4.69h-1.558v-4.69H1.55zm17.42 6.996v-4.687h3.524c4.411 0 8-3.586 8-8V23.3a8.006 8.006 0 0 0-4.402-7.14 6.668 6.668 0 0 0 1.178-3.79v-.433c0-3.448-2.604-6.292-5.947-6.678v-.042h-.65c-.042-.002-.083-.002-.125-.002l-1.577.002V.527h-4.658v4.69h-.89V.527H8.766v4.69H0v3.789h.775c1.598 0 2.898 1.3 2.898 2.897v13.251c0 1.599-1.3 2.9-2.898 2.9H0v3.788h8.766v4.693l4.658-.003v-4.69h.89v4.693z"/></g></g></g></g></g></g></g></svg>                                    </span>
                                <span class="va-middle">Compra y vende</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>
@endsection