@extends('layouts.mvp-layout-internal')

@section('content')


<main>
    <div class="container mt-md-n5">
        <div class="row">
            <div class="col-12 mx-auto px-0 px-md-3">
                <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                    <!--<div id="profile-tabs" class="btn-group" role="group" aria-label="Profile Navigation Tabs">
                        <a href="panel-control.html" class="btn btn-secondary py-md-3 rounded-0"><img
                                    src="/img/landing/boxes-secondary.svg"
                                    class="img-fluid mb-1 mb-md-0 mr-md-2"><span
                                    class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Panel de control</span></a>
                        {{--<a href="perfil-comprar.html" class="btn btn-secondary py-md-3 rounded-0"><img
                                    src="/img/landing/reload-secondary.svg"
                                    class="img-fluid mb-1 mb-md-0 mr-md-2"><span
                                    class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Comprar y Vender</span></a>--}}
                        <a href="perfil-billeteras.html" class="btn btn-secondary py-md-3 rounded-0 active"><img
                                    src="/img/landing/simpleWallet-secondary.svg"
                                    class="img-fluid mb-1 mb-md-0 mr-md-2"><span
                                    class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Mis Billeteras</span></a>
                        <a href="perfil-configuracion.html" class="btn btn-secondary py-md-3 rounded-0"><img
                                    src="/img/landing/settings-secondary.svg"
                                    class="img-fluid mb-1 mb-md-0 mr-md-2"><span
                                    class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Configuración</span></a>
                    </div>-->

                    <wallet-transactions
                            wallets="{{json_encode($userWallets)}}"
                    ></wallet-transactions>
                </div>

                {{--
                <div class="d-none d-md-block text-primary small px-3 px-md-0 wow fadeInUp">--}}
                    {{-- <h6 class="text-center">Información importante</h6>--}}
                    {{-- <p>1 Date available will be displayed on receipt for international transfers over $15. Service
                        and funds may be delayed or unavailable depending on certain factors including the Service
                        selected, the selection of delayed delivery options, special terms applicable to each Service,
                        amount sent, destination country, currency availability, regulatory issues, consumer protection
                        issues, identification requirements, delivery restrictions, agent location hours, and
                        differences in time zones (collectively, “Restrictions”). Additional restrictions may apply; see
                        our terms and conditions for details.</p>--}}
                    {{-- <p>1 Date available will be displayed on receipt for international transfers over $15. Service
                        and funds may be delayed or unavailable depending on certain factors including the Service
                        selected, the selection of delayed delivery options, special terms applicable to each Service,
                        amount sent, destination country, currency availability, regulatory issues, consumer protection
                        issues, identification requirements, delivery restrictions, agent location hours, and
                        differences in time zones (collectively, “Restrictions”). Additional restrictions may apply; see
                        our terms and conditions for details.</p>--}}
                    {{--
                </div>
                --}}
            </div>
        </div>
    </div>
</main>

@endsection
