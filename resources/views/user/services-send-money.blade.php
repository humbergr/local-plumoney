@extends('layouts.mvp-layout')

@section('content')
    <main>
        <div class="triangle--toRight">
            <section class="py-section-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 offset-md-1 my-auto">
                            <div class="text-center text-md-left wow fadeInUp">
                                <img src="/img/landing/sendPerson-secondary.svg" class="img-fluid ml-md-5 mb-3"
                                     style="height: 74px">
                                <h1 class="text-primary font-weight-bold">Enviar <span
                                            class="text-secondary">Dinero</span></h1>
                                <p class="h4 my-3 my-md-4">Te acercamos a los tuyos</p>
                                <p>En American Kryptos Bank tenemos como prioridad proteger tu dinero y tu información,
                                    para ello contamos con un sistema que garantiza la seguridad de tus operaciones</p>
                                <a href="/send-money" class="btn btn-secondary btn-lg btn-pill px-md-4 mt-4 mb-4 mb-md-0">
                                    <span class="mr-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 42 39"><g><g><g><g><g><g><g><g><g><path
                                                                                        fill="#fff"
                                                                                        d="M12.227 4.529A3.215 3.215 0 0 1 9.015 7.74a3.215 3.215 0 0 1-3.211-3.212c0-1.77 1.44-3.213 3.211-3.213a3.216 3.216 0 0 1 3.212 3.213zm.863 0A4.08 4.08 0 0 0 9.015.453 4.08 4.08 0 0 0 4.94 4.529a4.08 4.08 0 0 0 4.075 4.076 4.08 4.08 0 0 0 4.075-4.076z"/></g></g></g></g></g><g><g><g><g><g><path
                                                                                        fill="#fff"
                                                                                        d="M8.847 12.08h.218l.668 5.818-.777.627-.777-.627zm1.799 6.19l-.811-7.055H8.077l-.81 7.056 1.689 1.364z"/></g></g></g></g></g><g><path
                                                                        fill="#fff"
                                                                        d="M16.909 24.47a1.398 1.398 0 0 1-2.386.988 1.388 1.388 0 0 1-.407-.958v-1.458h2.793zM5.123 25.866v-1.365l.002-.033v-2.292h-.002V16.16H4.26v6.017H1.462V14.42A4.123 4.123 0 0 1 5.58 10.3h1.168l1.515 1.779h1.376l1.634-1.778h1.522a4.123 4.123 0 0 1 4.118 4.119v7.757h-2.797V16.16h-.864v7.378l-.002.93.002.032v1.367zm-2.26 0c-.373 0-.724-.145-.988-.41a1.391 1.391 0 0 1-.408-.989l.002-1.426h2.79v1.46a1.397 1.397 0 0 1-1.395 1.365zM10.1 10.3l-.84.914h-.598l-.78-.914zm7.677 4.119a4.988 4.988 0 0 0-4.982-4.983H5.58A4.988 4.988 0 0 0 .598 14.42v9.576h.006l-.001.471a2.242 2.242 0 0 0 .66 1.6 2.25 2.25 0 0 0 2.997.178v.488h9.856v-.485c.397.312.882.485 1.395.485a2.266 2.266 0 0 0 2.261-2.264v-.473h.005z"/></g></g><g><g><g><g><g><path
                                                                                    fill="#fff"
                                                                                    d="M35.95 16.378c0 1.77-1.441 3.211-3.212 3.211a3.215 3.215 0 0 1-3.212-3.211c0-1.77 1.441-3.211 3.212-3.211a3.215 3.215 0 0 1 3.212 3.21zm.863 0a4.079 4.079 0 0 0-4.075-4.074 4.079 4.079 0 0 0-4.076 4.074 4.08 4.08 0 0 0 4.076 4.075 4.08 4.08 0 0 0 4.075-4.075z"/></g></g></g></g></g><g><path
                                                                    fill="#fff"
                                                                    d="M35.17 7.244l-1.983 1.983V7.12A6.192 6.192 0 0 0 27.002.936h-9.111V1.8h9.111a5.328 5.328 0 0 1 5.322 5.322v2.046l-1.982-1.982-.612.61 3.055 3.056 2.995-2.995z"/></g></g><g><path
                                                                fill="#fff"
                                                                d="M40.632 36.322a1.399 1.399 0 0 1-2.387.986 1.386 1.386 0 0 1-.406-.958v-1.458h2.793zm-11.786 1.397v-1.362l.002-.035V34.03h-.002V28.01h-.864v6.019h-2.797v-7.76a4.121 4.121 0 0 1 4.117-4.116h.907a2.844 2.844 0 0 0 2.805 2.407 2.843 2.843 0 0 0 2.804-2.407h.7a4.121 4.121 0 0 1 4.118 4.116v7.76H37.84V28.01h-.864v7.416l-.002.89.002.033v1.37zm-2.26 0c-.373 0-.724-.146-.988-.41a1.387 1.387 0 0 1-.409-.99l.003-1.427h2.79v1.465a1.397 1.397 0 0 1-1.396 1.362zm8.358-15.565a1.978 1.978 0 0 1-3.86 0zM41.5 26.27a4.988 4.988 0 0 0-4.982-4.98h-.663v-.002h-5.682v.001h-.87a4.988 4.988 0 0 0-4.982 4.981v9.577h.005v.47a2.244 2.244 0 0 0 .66 1.602 2.254 2.254 0 0 0 2.996.18v.484h9.857V38.1c.396.313.882.484 1.395.484a2.263 2.263 0 0 0 2.261-2.261v-.475h.005z"/></g><g><g><g><g><g><path
                                                                                fill="#fff"
                                                                                d="M32.505 27.007a.512.512 0 1 1 1.023 0 .512.512 0 0 1-1.023 0z"/></g></g></g></g></g><g><g><g><g><g><path
                                                                                fill="#fff"
                                                                                d="M32.505 30.404a.512.512 0 1 1 1.023 0 .512.512 0 0 1-1.023 0z"/></g></g></g></g></g><g><g><g><g><g><path
                                                                                fill="#fff"
                                                                                d="M32.505 33.802a.512.512 0 1 1 1.023 0 .512.512 0 0 1-1.023 0z"/></g></g></g></g></g><g><g><text
                                                                    fill="#fff" style="font-kerning:normal"
                                                                    dominant-baseline="text-before-edge"
                                                                    font-family="'Mukta','Mukta-Light'" font-size="5"
                                                                    font-style="none" font-weight="lighter"
                                                                    transform="translate(21)"><tspan>$</tspan></text></g></g></g></g></svg>
                                    </span>
                                    <span class="va-middle">Enviar dinero</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7  my-auto px-0">
                            <img src="/img/landing/enviar-dinero.png" alt="" class="object-cover wow fadeIn"
                                 style="pointer-events: none; user-select: none; -webkit-user-select: none;">
                        </div>
                    </div>
                </div>
            </section>

            <div class="py-section-5">
                <section class="mb-5 mb-md-0 mt-md-n5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5 text-center text-md-right">
                                <img src="/img/landing/enviar-dinero2.png" alt=""
                                     class="img-fluid wow fadeIn d-none d-md-inline-block">
                            </div>
                            <div class="col-md-6 my-auto">
                                <div class="wow fadeInUp">
                                    <h1 class="text-primary font-weight-bold">Envía dinero de forma<br>rápida y sencilla
                                    </h1>
                                    <p class="text-primary mb-3 mb-md-4">En American Kryptos Bank sabemos lo importante
                                        que es para ti contar con un aliado para el envió de dinero de forma rápida,
                                        sencilla y segura, bien sea que estés enviando dinero a un familiar o a un
                                        amigo.</p>
                                    <a href="/send-money" class="link--arrowRight text-secondary btn-lg p-0">Envía dinero <img
                                                src="/img/landing/arrow-right-secondary.svg"
                                                class="arrow-right ml-2"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5 mb-md-0 mt-md-n5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 offset-md-1 my-auto">
                                <div class="wow fadeInUp">
                                    <h1 class="text-primary font-weight-bold">Desde cualquier lugar</h1>
                                    <p class="text-primary mb-3 mb-md-4">Envía dinero a tu familia o amigos desde donde
                                        te encuentres por medio de nuestra plataforma. Puedes hacerlo fácil y
                                        rápido.</p>
                                    <a href="/send-money" class="link--arrowRight text-secondary btn-lg p-0">Envía dinero <img
                                                src="/img/landing/arrow-right-secondary.svg"
                                                class="arrow-right ml-2"></a>
                                </div>
                            </div>
                            <div class="col-md-5 text-center text-md-left">
                                <img src="/img/landing/enviar-desde-cualquier-lugar.png" alt=""
                                     class="img-fluid wow fadeIn d-none d-md-inline-block">
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5 mb-md-0 mt-md-n5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-5 text-center text-md-right">
                                <img src="/img/landing/enviar-a-cualquier-lugar-del-mundo.png" alt=""
                                     class="img-fluid wow fadeIn d-none d-md-inline-block">
                            </div>
                            <div class="col-md-6 my-auto">
                                <div class="wow fadeInUp">
                                    <h1 class="text-primary font-weight-bold">A donde quieras</h1>
                                    <p class="text-primary mb-3 mb-md-4">Puedes enviar dinero a cualquier persona en
                                        cualquier parte del mundo de forma rápida y segura.</p>
                                    <a href="/send-money" class="link--arrowRight text-secondary btn-lg p-0">Envía dinero <img
                                                src="/img/landing/arrow-right-secondary.svg"
                                                class="arrow-right ml-2"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>
    </main>
@endsection