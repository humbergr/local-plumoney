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
                    <a class="nav-link active" href="{{URL::to('/auth-config')}}">
                        <i class="fa fa-qrcode" aria-hidden="true"></i> Two Factor Authentication
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{URL::to('/change-password')}}">
                        <i class="fa fa-key" aria-hidden="true"></i> Contraseña
                    </a>
                </li>
            </ul>
            <div class="card shadow-none rounded-lg mb-4">
                <div class="card-body px-lg-5">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h5 class="text-primary font-weight-bold mb-3">Escanee el código QR</h5>
                        </div>
                    </div>
                    @if( Auth::user()->google2fa_secret === '' || Auth::user()->google2fa_secret === null)
                        <div class="row">
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-10 text-center">
                                <p class="__auth_subtitle">
                                    Descarga en tu celular la aplicación Google Authenticator desde
                                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                                       target="_blank">
                                        Play Store
                                    </a> o desde la
                                    <a href="https://apps.apple.com/es/app/google-authenticator/id388497605"
                                       target="_blank">
                                        App Store
                                    </a> para iOS y escanea el siguiente código QR
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="{{$QR_image}}"
                                     alt="Upload selfie" class="img-fluid">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-6 text-center">
                                <p class="__auth_info">
                                    Ahora ingresa el código único que te aparece en la aplicación en el siguiente campo
                                </p>
                                <form action="{{URL::to('/auth-config')}}"
                                      method="post"
                                      class="__auth_form">
                                    {{ csrf_field() }}
                                    <div class="d-flex ws-nowrap">
                                        <input id="authApp-code"
                                               name="code"
                                               type="text"
                                               placeholder="Escriba el codigo aqui"
                                               class="form-control">
                                        <input type="hidden" name="secret"
                                               value="{{$secret}}">
                                        <button type="submit" class="btn btn-success no-wrap ml-3">
                                            <i class="fa fa-lock va-middle mr-2"></i>
                                            Activar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-10 text-center">
                                <p class="text-muted mb-4">Para desactivar el método <abbr
                                        title="Two Factor Authentication">2FA</abbr> sigue los
                                    siguientes pasos.</p>
                                <ol class="pl-3">
                                    <li class="mb-4">Ingresa el código en el siguiente campo.
                                        <div class="body-bg-color rounded-lg p-3 mt-3">
                                            <div class="form-group mb-0">
                                                <label for="authApp-code" class="text-primary">
                                                    Código de Google Authenticator
                                                </label>

                                                <!-- submit code form -->
                                                <form action="{{URL::to('/disable-2fa')}}"
                                                      method="post">
                                                    {{ csrf_field() }}
                                                    <div class="d-flex ws-nowrap">
                                                        <input id="authApp-code" name="code" type="text"
                                                               class="form-control"
                                                               placeholder="Escriba el codigo aqui">
                                                        <input type="hidden" name="secret"
                                                               value="{{$secret}}">
                                                        <button class="btn btn-danger no-wrap ml-3"
                                                                type="submit"><i
                                                                class="fa fa-lock va-middle mr-2"></i>
                                                            Desactivar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

@endsection
