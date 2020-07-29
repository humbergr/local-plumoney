@extends('layouts.mvp-layout-internal')

@section('content')

<main>
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-xl-10 mx-auto px-0 px-md-3">
                <div class="card mt-5 mt-md-n5 mt-5 mb-4 wow fadeInUp" style="z-index: 10">
                    <nav id="login-tabs" class="row no-gutters">
                        <div class="col-12">
                            <a class="btn btn-secondary btn-block rounded-0 d-flex flex-column justify-content-center align-items-center h-100" href="#!">
                                <div><i class="fa fa-user mr-2 d-none d-md-inline-block"></i> <span>Two Factor Authentication</span></div>
                                <small class="d-block">Haz de tu inicio de sesion un proceso mas seguro.</small>
                            </a>
                        </div>
                    <!--    <div class="col-6">
                            <a class="btn btn-secondary btn-block rounded-0 d-flex flex-column justify-content-center align-items-center h-100" href="#"><div><img src="img/landing/enviar-dinero.svg" class="d-none d-md-inline-block mr-2">Enviar dinero</div></a>
                        </div> -->
                    </nav>
                    <div class="card-body py-4 py-lg-5">
                      <div class="text-center">
                        <form class="" action="{{URL::to('/set-2fa')}}" method="post">
                          @csrf
                          <input type="hidden" name="secret" value="{{$secret}}">
                          <p>Escanea el siguiente codigo desde Google Authenticator</p>
                          <p>Si no tienes la app, <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">descargala aqui</a></p>
                          <img src="{{ $QR_image }}">
                          <p>Haz click en continuar solo si ya has escaneado el codigo.</p>
                          <button type="submit" class="btn btn-outline-secondary btn-pill py-2">Continuar</button>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
