@extends('layouts.mvp-layout-internal')

@section('content')
    <main>
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-xl-10 mx-auto px-0 px-md-3">
                    <div class="card mt-5 mt-md-n5 mt-5 mb-4 wow fadeInUp" style="z-index: 10">
                        <nav id="login-tabs" class="row no-gutters">
                            <div class="col-12 d-none d-md-block">
                                <a class="btn btn-secondary btn-block rounded-0 d-flex flex-column justify-content-center align-items-center h-100"
                                   href="javascript:void(0)">
                                    <div>
                                        <i class="fa fa-user mr-2 d-none d-md-inline-block"></i>
                                        <span>Ingreso / Registro</span>
                                    </div>
                                    <small class="d-block">Inicia sesión para continuar.</small>
                                </a>
                            </div>
                            <!--    <div class="col-6">
                                    <a class="btn btn-secondary btn-block rounded-0 d-flex flex-column justify-content-center align-items-center h-100" href="#"><div><img src="img/landing/enviar-dinero.svg" class="d-none d-md-inline-block mr-2">Enviar dinero</div></a>
                                </div> -->
                        </nav>
                        <div class="card-body py-4 py-lg-5">
                            <div class="row">
                                <div class="col-12 d-md-none mb-3 mb-md-0">
                                    <a class="btn btn-secondary btn-block rounded-0 d-flex flex-column justify-content-center align-items-center h-100"
                                       href="javascript:void(0)">
                                        <div>
                                            <i class="fa fa-user mr-2 d-none d-md-inline-block"></i>
                                            <span>Registro</span>
                                        </div>
                                        <small class="d-block">Si no tienes un usuario, debes registrarte.</small>
                                    </a>
                                </div>
                                <div class="col-md-6 px-lg-5">
                                    <form method="POST" id="register-form" action="{{ URL::to('/register-merchant') }}">
                                        @csrf
                                        <login-transaction></login-transaction>
                                        @isset($code)
                                            <input type="hidden" name="code" value="{{$code->id}}">
                                        @endisset
                                        <h6 class="text-primary font-weight-bold mb-4">Registrate si no tienes una
                                            cuenta</h6>

                                        @if (isset($code))
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-gift text-muted"></i>
                                                    </span>
                                                </div>
                                                <input type="text" readonly class="form-control"
                                                       name="not-used" value="{{$code->code}}">
                                            </div>
                                        @else
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-gift text-muted"></i>
                                                        </span>
                                                </div>
                                                <input type="text"
                                                       class="form-control"
                                                       name="code"
                                                       placeholder="Código promocional">
                                            </div>
                                        @endif

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                  <span class="input-group-text">
                                                      <i class="fa fa-user-o text-muted"></i>
                                                  </span>
                                            </div>
                                            <input type="text" name="first-name" class="form-control"
                                                   placeholder="Nombre" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                  <span class="input-group-text">
                                                      <i class="fa fa-user-o text-muted"></i>
                                                  </span>
                                            </div>
                                            <input type="text" name="last-name" class="form-control"
                                                   placeholder="Apellido" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                  <span class="input-group-text">
                                                      <i class="fa fa-envelope-o text-muted"></i>
                                                  </span>
                                            </div>
                                            <input type="email" name="email" class="form-control" placeholder="Email"
                                                   required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                  <span class="input-group-text">
                                                      <i class="fa fa-lock text-muted"></i>
                                                  </span>
                                            </div>
                                            <input type="password" name="password" class="form-control"
                                                   placeholder="Contraseña" required>
                                        </div>
                                        <button class="btn btn-primary btn-pill btn-block py-2 mt-4">Registrarse y
                                            continuar
                                        </button>
                                    </form>
                                </div>

                                <div class="col-12 d-md-none mt-md-0 mt-5 mb-3 mb-md-0">
                                    <a class="btn btn-secondary btn-block rounded-0 d-flex flex-column justify-content-center align-items-center h-100"
                                       href="javascript:void(0)">
                                        <div>
                                            <i class="fa fa-user mr-2 d-none d-md-inline-block"></i>
                                            <span>Inicia Sesión</span>
                                        </div>
                                        <small class="d-block">Inicia sesión para continuar.</small>
                                    </a>
                                </div>

                                <signin-component></signin-component>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
