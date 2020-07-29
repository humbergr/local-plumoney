@extends('layouts.mvp-layout-internal')

@section('content')

    <main class="dashboard__main"
          style="padding: 24px; background: #f4f4f9">
        <div class="container">
            <div class="alert alert-warning rounded-lg px-3 py-2 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2 mb-md-0">Debes completar tu perfil de usuario para continuar</h6>
                    </div>
                      <div class="col-md-3">
                    <span class="badge {{$personProfile->datos_verified ? 'badge-success' : 'badge-warning'  }}">{{$personProfile->datos_verified ? 'Verificado' : 'Pendiente por verificar'  }} Datos Personales</span>
                    <span class="badge {{$personProfile->identity_verified ? 'badge-success' : 'badge-warning'  }}">{{$personProfile->identity_verified ? 'Verificado' : 'Pendiente por verificar'  }} Identidad</span>
                    <span class="badge  {{$personProfile->mobile_verified ? 'badge-success' : 'badge-warning'  }}">{{$personProfile->mobile_verified ? 'Verificado' : 'Pendiente por verificar'  }} Telf. Celular</span>

                </div>
                    <div class="col-md-3">
                        <div class="text-primary lh-125 font-13">Completa tu perfil para que puedas seguir haciendo uso
                            de nuestros servicios.
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav profileSettingsNav d-flex flex-row justify-content-center flex-nowrap mb-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{URL::to('/user-info')}}">
                        <i class="fa fa-user" aria-hidden="true"></i> Información General
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{URL::to('/business-info')}}">
                        <i class="fa fa-building-o" aria-hidden="true"></i> Perfil de Empresa
                        @if($personProfile->selfie === null || $personProfile->street === null)
                            <span class="badge badge-danger badge-pill float-right ml-2"
                                  data-toggle="tooltip"
                                  title="Para registrar un perfil de empresa primero debe haber llenado todos los campos en Informacion General e Información de Ubicación.">
                                        <i class="fa fa-lock"></i>
                                    </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{URL::to('/auth-config')}}">
                        <i class="fa fa-qrcode" aria-hidden="true"></i> Two Factor Authentication
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active"
                       href="{{URL::to('/change-password')}}">
                        <i class="fa fa-key" aria-hidden="true"></i> Contraseña
                    </a>
                </li>
            </ul>
            <div class="card shadow-none rounded-lg mb-4">
                <div class="card-body px-lg-5">
                    <div class="row">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mt-5">
                                    <h6 class="text-primary font-weight-bold mb-4">Actualizar contraseña</h6>
                                    <p class="__pass_info">
                                        La contraseña debe tener:
                                    </p>
                                    <ul class="list-unstyled font-14 ml-3">
                                        <li><i class="fa-check fa"></i> Al menos una letra mayúscula</li>
                                        <li><i class="fa-check fa"></i> Al menos un número</li>
                                        <li><i class="fa-check fa"></i> Mínimo 6 carácteres</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 mt-5">
                                    <form action="{{URL::to('/change-password')}}"
                                          method="post">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group mb-5 mb-sm-3">
                                                    <label for="" class="text-primary">Contraseña actual</label>
                                                    <input id="merchant-password"
                                                           type="password"
                                                           class="form-control"
                                                           name="password"
                                                           value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="" class="text-primary">Nueva contraseña</label>
                                                    <input id="merchant-password"
                                                           type="password"
                                                           class="form-control"
                                                           name="password-new"
                                                           value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="" class="text-primary">Repetir nueva contraseña</label>
                                                    <input id="merchant-password"
                                                           type="password"
                                                           class="form-control"
                                                           name="password-confirmation"
                                                           value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-sm-12 text-right">
                                                <button class="btn btn-danger btn-pill px-lg-4">
                                                    <i class="fa fa-lock mr-2 va-middle"></i>
                                                    Actualizar contraseña
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
