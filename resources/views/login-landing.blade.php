@extends('layouts.mvp-layout-internal')

@section('content')

<main>
        <div class="container mt-md-n5">

            <!-- user login landing -->
            <div class="card shadow-none mb-4 mb-lg-5" style="z-index: 10">
                <div class="card-body py-4 py-lg-5 px-3 px-lg-4">
                    <div class="row justify-content-center">
                        <div class="col-md-9 col-lg-7 col-xl-6">
                            <div class="text-sm-center">
                                <!--<h6 class="font-13 text-uppercase text-muted">HOME</h6>-->
                                <h3 class="text-primary">¡Hola!</h3><!--, <span class="font-weight-bold">Ignacio Salcedo</span></h3>-->
                                <p>Esto es lo que puedes hacer con tu cuenta de American Kryptos Bank</p>
                                <div class="list-group text-left mt-4">
                                    <a href="{{URL::to('/send-money')}}" class="routeLink">Enviar Dinero</a>
                                    <a href="{{URL::to('/convert-money')}}" class="routeLink">Convertir Dinero</a>
                                    <a href="{{URL::to('/wallets')}}" class="routeLink">Gestionar Billeteras <span class="badge badge-danger bg-danger text-white badge-pill ml-2">Nuevo</span></a>
                                    <a href="{{URL::to('/transactions-history')}}" class="routeLink">Historial de Transacciones</a>
                                    <a href="{{URL::to('/user-info')}}" class="routeLink">Editar Perfil</a>
                                    <!--<a href="#" class="routeLink">Reportar un Error</a> -->
                                </div>
                                <img src="{{asset('img/landing/undraw_celebration.svg')}}" class="img-fluid d-none d-md-inline-block mt-5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

@endsection
