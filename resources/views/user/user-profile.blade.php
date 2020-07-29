<?php

use Carbon\Carbon;
use App\UserPersonProfile;

/** @var UserPersonProfile $personProfile */
?>

@extends('layouts.coinbank-layout')

@section('content')

    <div class="container mt-md-n5">
        <div class="row">
            <div class="col-12 mx-auto px-0 px-md-3">
                <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                    {{--
                        <div id="profile-tabs" class="btn-group" role="group" aria-label="Profile Navigation Tabs">
                            <a href="panel-control.html" class="btn btn-secondary py-md-3 rounded-0"><img
                                        src="{{asset('img/landing/boxes-secondary.svg')}}"
                    class="img-fluid mb-1 mb-md-0 mr-md-2"><span
                        class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Panel de control</span></a>
                    <a href="perfil-comprar.html" class="btn btn-secondary py-md-3 rounded-0"><img
                            src="{{asset('img/landing/reload-secondary.svg')}}" class="img-fluid mb-1 mb-md-0 mr-md-2"><span
                            class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Comprar y Vender</span></a>
                    <a href="perfil-billeteras.html" class="btn btn-secondary py-md-3 rounded-0"><img
                            src="{{asset('img/landing/simpleWallet-secondary.svg')}}"
                            class="img-fluid mb-1 mb-md-0 mr-md-2"><span
                            class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Mis Billeteras</span></a>
                    <a href="perfil-configuracion.html" class="btn btn-secondary py-md-3 rounded-0 active"><img
                            src="{{asset('img/landing/settings-secondary.svg')}}"
                            class="img-fluid mb-1 mb-md-0 mr-md-2"><span
                            class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Configuración</span></a>
                </div>
                --}}

                    <div class="card-body py-4 py-lg-4">
                        <div class="row justify-content-center mt-4 mb-4">
                            <div class="col-4 col-md-3 col-xl-2 px-1">
                                <a href="javascript:void(0);" id="active-natural-form" class="cardBank mx-2 --active">
                                    <img src="{{asset('img/landing/person-primary.svg')}}" alt="Person Icon"
                                         class="d-inline-block mb-2">
                                    <h6 class="cardBank__title">Persona</h6>
                                </a>
                            </div>
                            @if (!is_null($companyProfile))
                                <div class="col-4 col-md-3 col-xl-2 px-1">
                                    <a href="javascript:void(0);" id="active-company-form" class="cardBank mx-2">
                                        <img src="{{asset('img/landing/usd-rounded-primary.svg')}}" alt="Person Icon"
                                             class="d-inline-block mb-2">
                                        <h6 class="cardBank__title">Empresa</h6>
                                    </a>
                                </div>
                            @endif
                            <div class="col-6 col-md-3 col-xl-2 px-1 ml-3">
                                @if($userHasUsedRegistrationCode)
                                    <p class="font-weight-light font-14">
                                        Usuario registrado por el codigo
                                        de: {{ $userHasUsedRegistrationCode->code->code }} en la
                                        fecha {{ Carbon::parse($personProfile->user->created_at)->format('m/d/Y') }}
                                    </p>
                                    <hr>
                                @endif
                                @if( $personProfile->user->session_id === 'block' )
                                    <h5 class="font-weight-bold text-danger">Usuario Bloqueado</h5>
                                @elseif( $personProfile->approval_status === 0 )
                                    <h5 class="font-weight-bold">Perfil sin completar</h5>
                                @elseif( $personProfile->approval_status === 1 )
                                    <h5 class="font-weight-bold text-info">Perfil esperando respuesta</h5>
                                @elseif( $personProfile->approval_status === 2 )
                                    <h5 class="font-weight-bold text-success">Perfil Aprobado</h5>
                                @elseif( $personProfile->approval_status === 3 )
                                    <h5 class="font-weight-bold text-warning">Perfil Rechazado</h5>
                                @elseif( $personProfile->approval_status === 4 )
                                    <h5 class="font-weight-bold text-danger">Perfil provisionalmente en revisión TIER</h5>
                                @endif
                                <h5 class="font-weight-bold text-secundary">TIER Level: {{$personProfile->tier_level}}</h5>
                            </div>
                        </div>
                        {{--
                                <div class="row mb-4">
                                    <div class="col-md-8 mx-auto">
                                        <nav class="subtabs nav justify-content-center flex-nowrap mt-md-4 mb-5">
                                            <a class="subtabs__item nav-link mx-4 text-center active"
                                               href="perfil-configuracion.html"><img src="img/landing/person-primary.svg"
                                                                                     class="img-fluid mr-1"><span
                                                        class="va-middle text-truncate">Perfil</span></a>
                                            <a class="subtabs__item nav-link mx-4 text-center" href="perfil-configuracion-pay.html"><img
                                                        src="img/landing/payment-primary.svg" class="img-fluid mr-1"><span
                                                        class="va-middle text-truncate">Métodos de Pago</span></a>
                                        </nav>
                                    </div>
                                </div>
                                --}}

                        @if ($personProfile)
                            <form class="form-cotrol" id="merchant-natural"
                                  action="{{URL::to('verify-person-profile/'.$personProfile->id)}}"
                                  enctype="multipart/form-data"
                                  method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-3 pl-lg-5">
                                        <div class="row">
                                            <div class="popup-gallery">
                                                <div class="col-6 col-md-12">
                                                    <div class="text-center mb-3 mb-md-5">
                                                        <div class="setImg--wrapper mx-auto">
                                                            @if ($personProfile->selfie === null)
                                                                <img src="{{asset('img/landing/avatar-placeholder.png')}}"
                                                                     class="object-cover">
                                                            @else
                                                                <a href="{{URL::to($personProfile->selfie)}}"
                                                                   target="_blank"><img
                                                                            src="/{{$personProfile->selfie}}"
                                                                            class="object-cover"></a>
                                                            @endif
                                                        </div>
                                                        <div class="my-1">Fotografía</div>
                                                    <!--  <label class="btn btn-light btn-sm rounded-0">
                                                Cargar foto
                                                <input type="file"
                                                       class="form-control"
                                                       style="display: none;"
                                                       value="{{$personProfile->selfie}}"
                                                       name="UserPersonProfile[selfie]">
                                            </label>
                                            <small class="d-block text-muted mt-2">Tamaño máximo 2mb.</small> -->
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-12">
                                                    <div class="text-center mb-3 mb-md-5">
                                                        <div class="setImg--wrapper mx-auto">
                                                            @if ($personProfile->id_confirmation === null)
                                                                <img src="{{asset('img/landing/id-placeholder.png')}}"
                                                                     class="object-cover">
                                                            @else
                                                                <a href="{{URL::to($personProfile->id_confirmation)}}"
                                                                   target="_blank"><img
                                                                            src="/{{$personProfile->id_confirmation}}"
                                                                            class="object-cover"></a>
                                                            @endif
                                                        </div>
                                                        <div class="my-1">Documento frontal</div>
                                                    <!--  <label class="btn btn-light btn-sm rounded-0">
                                                Cargar foto
                                                <input type="file"
                                                       class="form-control"
                                                       style="display: none;"
                                                       value="{{$personProfile->id_confirmation}}"
                                                       name="UserPersonProfile[id_confirmation]">
                                            </label>
                                            <small class="d-block text-muted mt-2">Tamaño máximo 2mb.</small> -->
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-12">
                                                    <div class="text-center mb-3 mb-md-5">
                                                        <div class="setImg--wrapper mx-auto">
                                                            @if ($personProfile->id_confirmation_back === null)
                                                                <img src="{{asset('img/landing/id-placeholder.png')}}"
                                                                     class="object-cover">
                                                            @else
                                                                <a href="{{URL::to($personProfile->id_confirmation_back)}}"
                                                                   target="_blank"><img
                                                                            src="/{{$personProfile->id_confirmation_back}}"
                                                                            class="object-cover"></a>
                                                            @endif
                                                        </div>
                                                        <div class="my-1">Documento reverso</div>
                                                    <!--  <label class="btn btn-light btn-sm rounded-0">
                                                Cargar foto
                                                <input type="file"
                                                       class="form-control"
                                                       style="display: none;"
                                                       value="{{$personProfile->id_confirmation_back}}"
                                                       name="UserPersonProfile[id_confirmation]">
                                            </label>
                                            <small class="d-block text-muted mt-2">Tamaño máximo 2mb.</small> -->
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-12">
                                                    <div class="text-center mb-3 mb-md-5">
                                                        <div class="setImg--wrapper mx-auto">
                                                            @if ($personProfile->selfie_id === null)
                                                                <img src="{{asset('img/landing/id-placeholder.png')}}"
                                                                     class="object-cover">
                                                            @else
                                                                <a href="{{URL::to($personProfile->selfie_id)}}"
                                                                   target="_blank"><img
                                                                            src="/{{$personProfile->selfie_id}}"
                                                                            class="object-cover"></a>
                                                            @endif
                                                        </div>
                                                        <div class="my-1">AKB Selfie</div>
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-12">
                                                    <div class="text-center mb-3 mb-md-5">
                                                        <div>
                                                            <small class="text-primary"
                                                                   style="word-break: break-all;">{{$personProfile->gps}}</small>
                                                        </div>
                                                        <div class="my-1">Geo Ip</div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9 pr-lg-5">
                                        <h5 class="text-primary font-weight-bold mb-1">Datos Personales</h5>
                                        <p class="text-muted font-weight-light font-14 mb-3">Aquí puede ingresar y
                                            editar
                                            los datos asociados a su cuenta con American Kryptos Bank </p>
                                        @if( Auth::user()->role_id === 6 || Auth::user()->role_id === 1)
                                            <button class="btn btn-secondary my-3" id="edit-profile">Editar Perfil
                                            </button>
                                            <button class="btn btn-primary my-3" id="save-edit-profile" hidden>Guardar
                                                Cambios
                                            </button>
                                            <button class="btn btn-danger my-3" id="cancel-edit-profile" hidden>
                                                Cancelar
                                            </button>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="nombres" class="text-primary">Nombre</label>
                                                    <input type="text" class="form-control edit-userprofile"
                                                           name="UserPersonProfile[first_name]"
                                                           value="{{$personProfile->first_name}}"
                                                           disabled>
                                                    <input type="hidden" class="form-control"
                                                           name="UserPersonProfile[id]"
                                                           value="{{$personProfile->id}}">
                                                    <input type="hidden" class="form-control" name="User[id]"
                                                           value="{{Auth::user()->id}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="nombres" class="text-primary">Segundo Nombre</label>
                                                    <input type="text" class="form-control edit-userprofile"
                                                           name="UserPersonProfile[second_name]"
                                                           value="{{$personProfile->second_name}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="apellidos" class="text-primary">Apellido</label>
                                                    <input type="text" class="form-control edit-userprofile"
                                                           name="UserPersonProfile[last_name]"
                                                           value="{{$personProfile->last_name}}"
                                                           disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="apellidos" class="text-primary">Segundo Apellido</label>
                                                    <input type="text" class="form-control edit-userprofile"
                                                           name="UserPersonProfile[second_last_name]"
                                                           value="{{$personProfile->second_last_name}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="apellidos" class="text-primary">Tipo de
                                                        identificación</label>
                                                    <select name="UserPersonProfile[id_type]"
                                                            class="custom-select  edit-userprofile" disabled>
                                                        <option> -- Seleccione una opción --</option>
                                                        <option value="Cédula"
                                                                {{ $personProfile->id_type === 'Cédula' ? 'selected':''}}>
                                                            Cédula
                                                        </option>
                                                        <option value="Licencia de conducir"
                                                                {{ $personProfile->id_type === 'Licencia de conducir' ? 'selected':''}}>
                                                            Licencia de conducir
                                                        </option>
                                                        <option value="Pasaporte"
                                                                {{ $personProfile->id_type === 'Pasaporte' ? 'selected':''}}>
                                                            Pasaporte
                                                        </option>
                                                        <option value="Permiso de trabajo"
                                                                {{ $personProfile->id_type === 'Permiso de trabajo' ? 'selected':''}}>
                                                            Permiso de trabajo
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="apellidos" class="text-primary">Número de
                                                        identificación</label>
                                                    <input type="text" class="form-control edit-userprofile"
                                                           name="UserPersonProfile[id_number]"
                                                           value="{{$personProfile->id_number}}"
                                                           disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="text-primary">Fecha de emisión</label>
                                                    @php
                                                        $idCreationDate = $personProfile->id_creation_date ?
                                                        Carbon::createFromFormat(
                                                        'Y-m-d H:i:s',
                                                        $personProfile->id_creation_date
                                                        )->format('Y-m-d') : ''
                                                    @endphp
                                                    <input type="date" class="form-control edit-userprofile"
                                                           name="UserPersonProfile[id_creation_date]"
                                                           value="{{$idCreationDate}}"
                                                           placeholder="Escribe..." disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="text-primary">Fecha de vencimiento</label>
                                                    @php
                                                        $idExpirationDate = $personProfile->id_expiration_date ?
                                                        Carbon::createFromFormat(
                                                        'Y-m-d H:i:s',
                                                        $personProfile->id_expiration_date
                                                        )->format('Y-m-d') : ''
                                                    @endphp
                                                    <input type="date" class="form-control edit-userprofile"
                                                           value="{{$idExpirationDate}}"
                                                           name="UserPersonProfile[id_expiration_date]"
                                                           placeholder="Escribe..." disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email" class="text-primary">Email</label>
                                                    <input type="email" class="form-control edit-userprofile"
                                                           name="UserPersonProfile[email]"
                                                           value="{{$personProfile->email}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone" class="text-primary">Teléfono</label>
                                                    {{-- @php
                                                        $mobileNumber = explode(' ', $personProfile->mobile);
                                                    @endphp --}}
                                                    <div class="input-group">
                                                        {{-- <div class="input-group-prepend" style="width: 35%">
                                                            @php
                                                                $mobileNumber = explode(' ', $personProfile->mobile);
                                                            @endphp
                                                            <select name="UserPersonProfile[pre-mobile]"
                                                                    id="__u_mobile_prefix"
                                                                    class="custom-select edit-userprofile" disabled>
                                                                <optgroup label="Sur América">
                                                                    <option {{$mobileNumber[0]=== '+58' ? 'selected': ''}} value="+58">
                                                                        +58
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+57' ? 'selected': ''}} value="+57">
                                                                        +57
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+51' ? 'selected': ''}} value="+51">
                                                                        +51
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+56' ? 'selected': ''}} value="+56">
                                                                        +56
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+54' ? 'selected': ''}} value="+54">
                                                                        +54
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+55' ? 'selected': ''}} value="+55">
                                                                        +55
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+593' ? 'selected': ''}} value="+593">
                                                                        +593
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+591' ? 'selected': ''}} value="+591">
                                                                        +591
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+595' ? 'selected': ''}} value="+595">
                                                                        +595
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+59' ? 'selected': ''}} value="+59">
                                                                        +59
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Centro América">
                                                                    <option {{$mobileNumber[0]=== '+507' ? 'selected': ''}} value="+507">
                                                                        +507
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+1-809' ? 'selected': ''}} value="+1-809">
                                                                        +1-809
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+502' ? 'selected': ''}} value="+502">
                                                                        +502
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+503' ? 'selected': ''}} value="+503">
                                                                        +503
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+504' ? 'selected': ''}} value="+504">
                                                                        +504
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+505' ? 'selected': ''}} value="+505">
                                                                        +505
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+506' ? 'selected': ''}} value="+506">
                                                                        +506
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+50' ? 'selected': ''}} value="+50">
                                                                        +50
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Norte América">
                                                                    <option {{$mobileNumber[0]=== '+52' ? 'selected': ''}} value="+52">
                                                                        +52
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+1' ? 'selected': ''}} value="+1">
                                                                        +1
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Islas del Caribe">
                                                                    <option {{$mobileNumber[0]=== '+1-787' ? 'selected': ''}} value="+1-787">
                                                                        +1-787
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+297' ? 'selected': ''}} value="+297">
                                                                        +297
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+599' ? 'selected': ''}} value="+599">
                                                                        +599
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+1-868' ? 'selected': ''}} value="+1-868">
                                                                        +1-868
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+1-242' ? 'selected': ''}} value="+1-242">
                                                                        +1-242
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+1-24' ? 'selected': ''}} value="+1-24">
                                                                        +1-24
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Europa">
                                                                    <option {{$mobileNumber[0]=== '+44' ? 'selected': ''}} value="+44">
                                                                        +44
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+34' ? 'selected': ''}} value="+34">
                                                                        +34
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+351' ? 'selected': ''}} value="+351">
                                                                        +351
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+39' ? 'selected': ''}} value="+39">
                                                                        +39
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+33' ? 'selected': ''}} value="+33">
                                                                        +33
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+4' ? 'selected': ''}} value="+4">
                                                                        +4
                                                                    </option>
                                                                    <option {{$mobileNumber[0]=== '+49' ? 'selected': ''}} value="+49">
                                                                        +49
                                                                    </option>
                                                                </optgroup>
                                                                <option {{$mobileNumber[0]===
                                                                    '+61' ? 'selected': ''}} value="+61">
                                                                    +61
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <input type="text" class="form-control edit-userprofile"
                                                               id="__u_mobile_main" value="{{$mobileNumber[1] ?? ''}}"
                                                               name="UserPersonProfile[main-mobile]" disabled> --}}
                                                        <input id="phone" class="form-input form-control edit-userprofile" type="tel" name="UserPersonProfile[mobile]" value="{{$personProfile->mobile}}" disabled>

                                                    </div>
                                                    @if($personProfile->mobile_verified != true  )
                                                        {{-- <a href="javascript:void(0)"
                                                           class="text-danger text-right small form-text"
                                                           id="__verify_phone_button" data-toggle="modal"
                                                           data-target="#verify-phone">
                                                            <i class="fa fa-warning"></i>
                                                            Debe verificar tu número.
                                                            <br>
                                                            Haz click aquí.
                                                        </a> --}}
                                                        <p class="text-danger text-right small form-text">
                                                            <i class="fa fa-warning"></i>
                                                            Debe verificar el número.
                                                            <br>
                                                            Haz click aquí.
                                                        </p>
                                                        
                                                    @else
                                                        <p id="__verified_phone"
                                                           class="text-success text-right small form-text">
                                                            Número verificado con éxito.
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone" class="text-primary">Teléfono Local</label>
                                                    {{-- @php
                                                        $personLocalNumber = explode(' ', $personProfile->local_phone);
                                                    @endphp --}}
                                                    <div class="input-group">
                                                        {{-- <div class="input-group-prepend">
                                                            <select class="custom-select edit-userprofile"
                                                                    name="UserPersonProfile[pre-local]" disabled>
                                                                <optgroup label="Sur América">
                                                                    <option {{$personLocalNumber[0]==='+58' ? 'selected': ''}} value="+58">
                                                                        +58
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+57' ? 'selected': ''}} value="+57">
                                                                        +57
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+51' ? 'selected': ''}} value="+51">
                                                                        +51
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+56' ? 'selected': ''}} value="+56">
                                                                        +56
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+54' ? 'selected': ''}} value="+54">
                                                                        +54
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+55' ? 'selected': ''}} value="+55">
                                                                        +55
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+593' ? 'selected': ''}} value="+593">
                                                                        +593
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+591' ? 'selected': ''}} value="+591">
                                                                        +591
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+595' ? 'selected': ''}} value="+595">
                                                                        +595
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+59' ? 'selected': ''}} value="+59">
                                                                        +59
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Centro América">
                                                                    <option {{$personLocalNumber[0]==='+507' ? 'selected': ''}} value="+507">
                                                                        +507
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+1-809' ? 'selected': ''}} value="+1-809">
                                                                        +1-809
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+502' ? 'selected': ''}} value="+502">
                                                                        +502
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+503' ? 'selected': ''}} value="+503">
                                                                        +503
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+504' ? 'selected': ''}} value="+504">
                                                                        +504
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+505' ? 'selected': ''}} value="+505">
                                                                        +505
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+506' ? 'selected': ''}} value="+506">
                                                                        +506
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+50' ? 'selected': ''}} value="+50">
                                                                        +50
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Norte América">
                                                                    <option {{$personLocalNumber[0]==='+52' ? 'selected': ''}} value="+52">
                                                                        +52
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+1' ? 'selected': ''}} value="+1">
                                                                        +1
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Islas del Caribe">
                                                                    <option {{$personLocalNumber[0]==='+1-787' ? 'selected': ''}} value="+1-787">
                                                                        +1-787
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+297' ? 'selected': ''}} value="+297">
                                                                        +297
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+599' ? 'selected': ''}} value="+599">
                                                                        +599
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+1-868' ? 'selected': ''}} value="+1-868">
                                                                        +1-868
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+1-242' ? 'selected': ''}} value="+1-242">
                                                                        +1-242
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+1-24' ? 'selected': ''}} value="+1-24">
                                                                        +1-24
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Europa">
                                                                    <option {{$personLocalNumber[0]==='+44' ? 'selected': ''}} value="+44">
                                                                        +44
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+34' ? 'selected': ''}} value="+34">
                                                                        +34
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+351' ? 'selected': ''}} value="+351">
                                                                        +351
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+39' ? 'selected': ''}} value="+39">
                                                                        +39
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+33' ? 'selected': ''}} value="+33">
                                                                        +33
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+4' ? 'selected': ''}} value="+4">
                                                                        +4
                                                                    </option>
                                                                    <option {{$personLocalNumber[0]==='+49' ? 'selected': ''}} value="+49">
                                                                        +49
                                                                    </option>
                                                                </optgroup>
                                                                <option {{$personLocalNumber[0]=== '+61' ? 'selected': ''}} value="+61">
                                                                    +61
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <input type="text" class="form-control edit-userprofile"
                                                               name="UserPersonProfile[main-local]"
                                                               value="{{$personLocalNumber[1] ?? ''}}{{$personLocalNumber[2] ?? ''}}{{$personLocalNumber[3] ?? ''}}{{$personLocalNumber[4] ?? ''}}"
                                                               disabled> --}}
                                                        <input id="local" value="{{$personProfile->local_phone}}"  class="form-input form-control edit-userprofile" type="tel" name="UserPersonProfile[local]" disabled>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fecha-nacimiento" class="text-primary">
                                                        Mes de nacimiento
                                                    </label>
                                                    <select id="fecha-nacimiento" name="UserPersonProfile[birth_month]"
                                                            class="custom-select edit-userprofile" disabled>
                                                        <option {{ $personProfile->birth_month === '01' ? 'selected':'' }}
                                                                value="01">Enero
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '02' ? 'selected':'' }}
                                                                value="02">Febrero
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '03' ? 'selected':'' }}
                                                                value="03">Marzo
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '04' ? 'selected':'' }}
                                                                value="04">Abril
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '05' ? 'selected':'' }}
                                                                value="05">Mayo
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '06' ? 'selected':'' }}
                                                                value="06">Junio
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '07' ? 'selected':'' }}
                                                                value="07">Julio
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '08' ? 'selected':'' }}
                                                                value="08">Agosto
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '09' ? 'selected':'' }}
                                                                value="09">Septiembre
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '10' ? 'selected':'' }}
                                                                value="10">Octubre
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '11' ? 'selected':'' }}
                                                                value="11">Noviembre
                                                        </option>
                                                        <option {{ $personProfile->birth_month === '12' ? 'selected':'' }}
                                                                value="12">Diciembre
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    @php
                                                        $days = [];
                                                        $i = 0;
                                                        while ($i < 31) { $i++; $days[]=$i; } @endphp <label for=""
                                                                                             class="text-primary">Dia de
                                                        Nacimiento</label>
                                                    <select class="custom-select edit-userprofile"
                                                            name="UserPersonProfile[birth_day]" disabled>
                                                        @foreach ($days as $day)
                                                            <option value="{{$day}}"
                                                                    {{ $personProfile->birth_day === $day ? 'selected':'' }}>
                                                                {{$day}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    @php
                                                        $years = [];
                                                        $i = (int)date('Y') - 99;
                                                        while ($i < (int)date('Y')) { $i++; $years[]=$i; } @endphp <label for=""
                                                                                                          class="text-primary">Año
                                                        de nacimiento</label>
                                                    <select class="custom-select edit-userprofile"
                                                            name="UserPersonProfile[birth_year]" disabled>
                                                        @foreach ($years as $year)
                                                            <option value="{{$year}}"
                                                                    {{ $personProfile->birth_year === $year ? 'selected':'' }}>
                                                                {{$year}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="merchant-select-country"
                                                           class="text-primary">País</label>
                                                    <select id="merchant-select-country"
                                                            class="custom-select edit-userprofile flag-selector flag-selector--full"
                                                            name="UserPersonProfile[country]" disabled>
                                                        <optgroup label="Sur América">
                                                            <option {{ $personProfile->country === 'VE' ? 'selected':''
                                                        }} value="VE" data-flag="/img/landing/flags/ve.svg">Venezuela
                                                            </option>
                                                            <option {{ $personProfile->country === 'CO' ? 'selected':''
                                                        }} value="CO" data-flag="/img/landing/flags/co.svg">Colombia
                                                            </option>
                                                            <option {{ $personProfile->country === 'PE' ? 'selected':''
                                                        }} value="PE" data-flag="/img/landing/flags/pe.svg">Perú
                                                            </option>
                                                            <option {{ $personProfile->country === 'CL' ? 'selected':''
                                                        }} value="CL" data-flag="/img/landing/flags/cl.svg">Chile
                                                            </option>
                                                            <option {{ $personProfile->country === 'AR' ? 'selected':''
                                                        }} value="AR" data-flag="/img/landing/flags/ar.svg">Argentina
                                                            </option>
                                                            <option {{ $personProfile->country === 'BR' ? 'selected':''
                                                        }} value="BR" data-flag="/img/landing/flags/br.svg">Brazil
                                                            </option>
                                                            <option {{ $personProfile->country === 'EC' ? 'selected':''
                                                        }} value="EC" data-nb="true"
                                                                    data-flag="/img/landing/flags/ec.svg">Ecuador
                                                            </option>
                                                            <option {{ $personProfile->country === 'BO' ? 'selected':''
                                                        }} value="BO" data-nb="true"
                                                                    data-flag="/img/landing/flags/bo.svg">Bolivia
                                                            </option>
                                                            <option {{ $personProfile->country === 'PY' ? 'selected':''
                                                        }} value="PY" data-nb="true"
                                                                    data-flag="/img/landing/flags/py.svg">Paraguay
                                                            </option>
                                                            <option {{ $personProfile->country === 'UY' ? 'selected':''
                                                        }} value="UY" data-nb="true"
                                                                    data-flag="/img/landing/flags/uy.svg">Uruguay
                                                            </option>
                                                        </optgroup>
                                                        <optgroup label="Centro América">
                                                            <option {{ $personProfile->country === 'PA' ? 'selected':''
                                                        }} value="PA" data-flag="/img/landing/flags/pa.svg">Panamá
                                                            </option>
                                                            <option {{ $personProfile->country === 'GT' ? 'selected':''
                                                        }} value="GT" data-nb="true"
                                                                    data-flag="/img/landing/flags/gt.svg">Guatemala
                                                            </option>
                                                            <option {{ $personProfile->country === 'SV' ? 'selected':''
                                                        }} value="SV" data-nb="true"
                                                                    data-flag="/img/landing/flags/sv.svg">El Salvador
                                                            </option>
                                                            <option {{ $personProfile->country === 'HN' ? 'selected':''
                                                        }} value="HN" data-nb="true"
                                                                    data-flag="/img/landing/flags/hn.svg">Honduras
                                                            </option>
                                                            <option {{ $personProfile->country === 'NI' ? 'selected':''
                                                        }} value="NI" data-nb="true"
                                                                    data-flag="/img/landing/flags/ni.svg">Nicaragua
                                                            </option>
                                                            <option {{ $personProfile->country === 'CR' ? 'selected':''
                                                        }} value="CR" data-nb="true"
                                                                    data-flag="/img/landing/flags/cr.svg">Costa Rica
                                                            </option>
                                                            <option {{ $personProfile->country === 'BZ' ? 'selected':''
                                                        }} value="BZ" data-nb="true"
                                                                    data-flag="/img/landing/flags/bz.svg">Belize
                                                            </option>
                                                        </optgroup>
                                                        <optgroup label="Norte América">
                                                            <option {{ $personProfile->country === 'MX' ? 'selected':''
                                                        }} value="MX" data-flag="/img/landing/flags/mx.svg">México
                                                            </option>
                                                            <option {{ $personProfile->country === 'US' ? 'selected':''
                                                        }} value="US" data-flag="/img/landing/flags/us.svg">United
                                                                States
                                                            </option>
                                                            <option {{ $personProfile->country === 'CA' ? 'selected':''
                                                        }} value="CA" data-nb="true"
                                                                    data-flag="/img/landing/flags/ca.svg">Canada
                                                            </option>
                                                        </optgroup>
                                                        <optgroup label="Islas del Caribe">
                                                            <option {{ $personProfile->country === 'DO' ? 'selected':''
                                                        }} value="DO" data-nb="true"
                                                                    data-flag="/img/landing/flags/do.svg">República
                                                                Dominicana
                                                            </option>
                                                            <option {{ $personProfile->country === 'PR' ? 'selected':''
                                                        }} value="PR" data-nb="true"
                                                                    data-flag="/img/landing/flags/pr.svg">Puerto Rico
                                                            </option>
                                                            <option {{ $personProfile->country === 'AW' ? 'selected':''
                                                        }} value="AW" data-nb="true"
                                                                    data-flag="/img/landing/flags/aw.svg">Aruba
                                                            </option>
                                                            <option {{ $personProfile->country === 'CW' ? 'selected':''
                                                        }} value="CW" data-nb="true"
                                                                    data-flag="/img/landing/flags/cw.svg">Curacao
                                                            </option>
                                                            <option {{ $personProfile->country === 'TT' ? 'selected':''
                                                        }} value="TT" data-nb="true"
                                                                    data-flag="/img/landing/flags/tt.svg">Trinidad y
                                                                Tobago
                                                            </option>
                                                            <option {{ $personProfile->country === 'BS' ? 'selected':''
                                                        }} value="BS" data-nb="true"
                                                                    data-flag="/img/landing/flags/bs.svg">Bahamas
                                                            </option>
                                                            <option {{ $personProfile->country === 'BB' ? 'selected':''
                                                        }} value="BB" data-nb="true"
                                                                    data-flag="/img/landing/flags/bb.svg">Barbados
                                                            </option>
                                                        </optgroup>
                                                        <optgroup label="Europa">
                                                            <option {{ $personProfile->country === 'GB' ? 'selected':''
                                                        }} value="GB" data-flag="/img/landing/flags/gb.svg">Reino Unido
                                                            </option>
                                                            <option {{ $personProfile->country === 'ES' ? 'selected':''
                                                        }} value="ES" data-flag="/img/landing/flags/es.svg">España
                                                            </option>
                                                            <option {{ $personProfile->country === 'PT' ? 'selected':''
                                                        }} value="PT" data-flag="/img/landing/flags/pt.svg">Portugal
                                                            </option>
                                                            <option {{ $personProfile->country === 'IT' ? 'selected':''
                                                        }} value="IT" data-flag="/img/landing/flags/it.svg">Italia
                                                            </option>
                                                            <option {{ $personProfile->country === 'FR' ? 'selected':''
                                                        }} value="FR" data-flag="/img/landing/flags/fr.svg">Francia
                                                            </option>
                                                            <option {{ $personProfile->country === 'DE' ? 'selected':''
                                                        }} value="DE" data-flag="/img/landing/flags/de.svg">Alemania
                                                            </option>
                                                        </optgroup>
                                                        <option {{ $personProfile->country === 'AU' ? 'selected':''}} value="AU"
                                                                data-flag="/img/landing/flags/au.svg">Australia
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="direccion-postal1"
                                                           class="text-primary">Dirección</label>
                                                    <input id="direccion-postal1" name="UserPersonProfile[street]"
                                                           value="{{$personProfile->street}}" type="text"
                                                           class="form-control edit-userprofile" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="tipo_doc_person" class="text-primary">Tipo de
                                                        domicilio</label>
                                                    <select name="UserPersonProfile[address_place_type]"
                                                            class="custom-select edit-userprofile" disabled>
                                                        <option value="Casa"
                                                                {{ $personProfile->address_place_type === 'Casa' ? 'selected':''}}>
                                                            Casa
                                                        </option>
                                                        <option value="Edificio"
                                                                {{ $personProfile->address_place_type === 'Edificio' ? 'selected':''}}>
                                                            Edificio
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="floor_person" class="text-primary">Planta / Piso</label>
                                                    <input id="floor_person" type="text"
                                                           class="form-control edit-userprofile"
                                                           name="UserPersonProfile[address_floor]"
                                                           value="{{$personProfile->address_floor}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="place_number_person" class="text-primary">N.
                                                        Habitación</label>
                                                    <input id="place_number_person" type="text"
                                                           class="form-control edit-userprofile"
                                                           name="UserPersonProfile[address_place_number]"
                                                           value="{{$personProfile->address_place_number}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="codigo-postal" class="text-primary">Código
                                                        Postal</label>
                                                    <input id="codigo-postal" type="text"
                                                           class="form-control edit-userprofile"
                                                           name="UserPersonProfile[zip_code]"
                                                           value="{{$personProfile->zip_code}}"
                                                           disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="ciudad" class="text-primary">Ciudad</label>
                                                    <input disabled id="ciudad" type="text"
                                                           class="form-control edit-userprofile"
                                                           name="UserPersonProfile[city]"
                                                           value="{{$personProfile->city}}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group" data-user-state="{{$personProfile->state}}">
                                                    <label for="merchant-select-state" class="merchant-select-state"
                                                           style="color:#303392 !important">
                                                        Estado/Departamento
                                                    </label>
                                                    <input disabled id="ciudad" type="text"
                                                           class="form-control edit-userprofile"
                                                           name="UserPersonProfile[state]"
                                                           value="{{$personProfile->state}}"/>
                                                </div>
                                            </div>
                                        </div>
                                    <!--
                                <hr>
                                <div class="row">
                                    {{ csrf_field() }}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="merchant-username" class="text-primary">Nivel</label>
                                                    <select class="custom-select" name="level"
                                                            value="{{$personProfile->level}}">
                                                <option value="1" @if ($personProfile->level == 1) selected @endif>
                                                    1
                                                </option>
                                                <option value="2" @if ($personProfile->level == 2) selected @endif>
                                                    2
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    @if ($personProfile->status == 0)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="verify-user" class="text-primary">Status</label>
                                                <select class="custom-select" name="status" value="">
                                                    <option value="0" disbled>No Verificado</option>
                                                    <option value="1">Verificado</option>
                                                </select>
                                            </div>
                                        </div>
@endif
                                            <div class="col-md-4">
                                                <label for="">&nbsp;</label>
                                        <button type="submit"
                                                id=""
                                                class="btn btn-light btn-block rounded-0">
                                            Actualizar
                                        </button>
                                    </div>
                                </div>
                                -->
                                    </div>

                                </div>
                            </form>
                        @endif

                        <form class="form-cotrol" id="merchant-company" style="display:none;"
                              action="{{URL::to('verify-comp-profile/'.$companyProfile->id)}}"
                              enctype="multipart/form-data"
                              method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-3 pl-lg-5">
                                    <div class="row">
                                        <!--                                        <div class="col-6 col-md-12">-->
                                        <!--                                            <div class="text-center mb-3 mb-md-5">-->
                                        <!--                                                <div class="setImg--wrapper mx-auto">-->
                                    <!--                                                    @if ($companyProfile->logo === null)-->
                                    <!--                                                        <img src="{{asset('img/landing/avatar-placeholder.png')}}"-->
                                        <!--                                                             class="object-cover">-->
                                        <!--                                                    @else-->
                                    <!--                                                        <img src="{{$companyProfile->logo}}"-->
                                        <!--                                                             class="object-cover">-->
                                        <!--                                                    @endif-->
                                        <!--                                                </div>-->
                                        <!--                                                <div class="my-1">Fotografía del registro de empresa</div>-->
                                        <!--                                                 <label class="btn btn-light btn-sm rounded-0">-->
                                        <!--                                                Cargar logo-->
                                        <!--                                                <input type="file"-->
                                        <!--                                                       class="form-control"-->
                                        <!--                                                       style="display: none;"-->
                                    <!--                                                       value="{{$companyProfile->logo}}"-->
                                        <!--                                                       name="UserCompanyProfile[logo]">-->
                                        <!--                                            </label>-->
                                        <!--                                            <small class="d-block text-muted mt-2">Tamaño máximo 2mb.</small> -->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <div class="col-6 col-md-12">
                                            <div class="text-center mb-3 mb-md-5">
                                                <div class="setImg--wrapper mx-auto">
                                                    @if ($companyProfile->id_confirmation === null)
                                                        <img src="{{asset('img/landing/tributary-placeholder.png')}}"
                                                             class="object-cover">
                                                    @elseif(substr($companyProfile->id_confirmation, -4) === '.pdf')
                                                        <a href="{{URL::to($companyProfile->id_confirmation)}}"
                                                           target="_blank"><img
                                                                    src="{{asset('img/landing/tributary-placeholder.png')}}"
                                                                    class="object-cover"></a>
                                                    @else
                                                        <a href="{{URL::to($companyProfile->id_confirmation)}}"
                                                           target="_blank"><img
                                                                    src="/{{$companyProfile->id_confirmation}}"
                                                                    class="object-cover"></a>
                                                    @endif
                                                </div>
                                                <div class="my-1">Fotografía del Registro de Empresa</div>
                                            <!--    <label class="btn btn-light btn-sm rounded-0">
                                                Cargar foto
                                                <input type="file"
                                                       class="form-control"
                                                       style="display: none;"
                                                       value="{{$companyProfile->id_confirmation}}"
                                                       name="UserCompanyProfile[id_confirmation]">
                                            </label>
                                            <small class="d-block text-muted mt-2">Tamaño máximo 2mb.</small> -->
                                            </div>
                                            <div class="text-center mb-3 mb-md-5">
                                                <div class="setImg--wrapper mx-auto">
                                                    @if ($companyProfile->public_service_doc === null)
                                                        <img src="{{asset('img/landing/tributary-placeholder.png')}}"
                                                             class="object-cover">
                                                    @elseif(substr($companyProfile->public_service_doc, -4) === '.pdf')
                                                        <a href="{{URL::to($companyProfile->public_service_doc)}}"
                                                           target="_blank"><img
                                                                    src="{{asset('img/landing/tributary-placeholder.png')}}"
                                                                    class="object-cover"></a>
                                                    @else
                                                        <a href="{{URL::to($companyProfile->public_service_doc)}}"
                                                           target="_blank"><img
                                                                    src="/{{$companyProfile->public_service_doc}}"
                                                                    class="object-cover"></a>
                                                    @endif
                                                </div>
                                                <div class="my-1">Fotografía o PDF de un servicio público</div>
                                            <!--    <label class="btn btn-light btn-sm rounded-0">
                                                    Cargar foto
                                                    <input type="file"
                                                           class="form-control"
                                                           style="display: none;"
                                                           value="{{$companyProfile->public_service_doc}}"
                                                           name="UserCompanyProfile[id_confirmation]">
                                                </label>
                                                <small class="d-block text-muted mt-2">Tamaño máximo 2mb.</small> -->
                                            </div>
                                            <div class="text-center mb-3 mb-md-5">
                                                <div class="setImg--wrapper mx-auto">
                                                    @if ($companyProfile->tax_id_doc === null)
                                                        <img src="{{asset('img/landing/tributary-placeholder.png')}}"
                                                             class="object-cover">
                                                    @elseif(substr($companyProfile->tax_id_doc, -4) === '.pdf')
                                                        <a href="{{URL::to($companyProfile->tax_id_doc)}}"
                                                           target="_blank"><img
                                                                    src="{{asset('img/landing/tributary-placeholder.png')}}"
                                                                    class="object-cover"></a>
                                                    @else
                                                        <a href="{{URL::to($companyProfile->tax_id_doc)}}"
                                                           target="_blank"><img
                                                                    src="/{{$companyProfile->tax_id_doc}}"
                                                                    class="object-cover"></a>
                                                    @endif
                                                </div>
                                                <div class="my-1">Fotografía o PDF del ID tributario</div>
                                            <!--    <label class="btn btn-light btn-sm rounded-0">
                                                    Cargar foto
                                                    <input type="file"
                                                           class="form-control"
                                                           style="display: none;"
                                                           value="{{$companyProfile->tax_id_doc}}"
                                                           name="UserCompanyProfile[id_confirmation]">
                                                </label>
                                                <small class="d-block text-muted mt-2">Tamaño máximo 2mb.</small> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 pr-lg-5">
                                    <h5 class="text-primary font-weight-bold mb-1">Datos Empresariales</h5>
                                    <p class="text-muted font-weight-light font-14 mb-3">Aquí puede ingresar y editar
                                        los datos asociados a su cuenta con American Kryptos Bank</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company-nombre" class="text-primary">Nombre de la
                                                    empresa</label>
                                                <input id="company-nombre" type="text" class="form-control"
                                                       value="{{$companyProfile->name}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="company-id-number" class="text-primary">
                                                    Número de Identificación Tributaria
                                                    <small>(RUT, RIF, EIN - Tax ID Number, etc.)</small>
                                                </label>
                                                <input id="company-id-number" type="text" class="form-control"
                                                       value="{{$companyProfile->tax_number}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company-mobile" class="text-primary">Teléfono Móvil</label>
                                                @php
                                                    $companyMobileNumber = explode(' ', $companyProfile->mobile);
                                                @endphp
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <select id="company-mobile" class="custom-select" disabled>
                                                            <optgroup label="Sur América">
                                                                <option {{$companyMobileNumber[0]===
                                                            '+58' ? 'selected': ''}} value="+58">
                                                                    +58
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+57' ? 'selected': ''}} value="+57">
                                                                    +57
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+51' ? 'selected': ''}} value="+51">
                                                                    +51
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+56' ? 'selected': ''}} value="+56">
                                                                    +56
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+54' ? 'selected': ''}} value="+54">
                                                                    +54
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+55' ? 'selected': ''}} value="+55">
                                                                    +55
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+593' ? 'selected': ''}} value="+593">
                                                                    +593
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+591' ? 'selected': ''}} value="+591">
                                                                    +591
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+595' ? 'selected': ''}} value="+595">
                                                                    +595
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+59' ? 'selected': ''}} value="+59">
                                                                    +59
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Centro América">
                                                                <option {{$companyMobileNumber[0]===
                                                            '+507' ? 'selected': ''}} value="+507">
                                                                    +507
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+1-809' ? 'selected': ''}} value="+1-809">
                                                                    +1-809
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+502' ? 'selected': ''}} value="+502">
                                                                    +502
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+503' ? 'selected': ''}} value="+503">
                                                                    +503
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+504' ? 'selected': ''}} value="+504">
                                                                    +504
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+505' ? 'selected': ''}} value="+505">
                                                                    +505
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+506' ? 'selected': ''}} value="+506">
                                                                    +506
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+50' ? 'selected': ''}} value="+50">
                                                                    +50
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Norte América">
                                                                <option {{$companyMobileNumber[0]===
                                                            '+52' ? 'selected': ''}} value="+52">
                                                                    +52
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+1' ? 'selected': ''}} value="+1">
                                                                    +1
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Islas del Caribe">
                                                                <option {{$companyMobileNumber[0]===
                                                            '+1-787' ? 'selected': ''}} value="+1-787">
                                                                    +1-787
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+297' ? 'selected': ''}} value="+297">
                                                                    +297
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+599' ? 'selected': ''}} value="+599">
                                                                    +599
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+1-868' ? 'selected': ''}} value="+1-868">
                                                                    +1-868
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+1-242' ? 'selected': ''}} value="+1-242">
                                                                    +1-242
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+1-24' ? 'selected': ''}} value="+1-24">
                                                                    +1-24
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Europa">
                                                                <option {{$companyMobileNumber[0]===
                                                            '+44' ? 'selected': ''}} value="+44">
                                                                    +44
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+34' ? 'selected': ''}} value="+34">
                                                                    +34
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+351' ? 'selected': ''}} value="+351">
                                                                    +351
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+39' ? 'selected': ''}} value="+39">
                                                                    +39
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+33' ? 'selected': ''}} value="+33">
                                                                    +33
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+4' ? 'selected': ''}} value="+4">
                                                                    +4
                                                                </option>
                                                                <option {{$companyMobileNumber[0]===
                                                            '+49' ? 'selected': ''}} value="+49">
                                                                    +49
                                                                </option>
                                                            </optgroup>
                                                            <option {{$companyMobileNumber[0]===
                                                        '+61' ? 'selected': ''}} value="+61">
                                                                +61
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <input type="text" class="form-control"
                                                           value="{{$companyMobileNumber[1] ?? ''}}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company-office_phone" class="text-primary">Teléfono de
                                                    oficina</label>
                                                @php
                                                    $companyOfficeNumber = explode(' ', $companyProfile->office_phone);
                                                @endphp
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <select id="company-office_phone" class="custom-select"
                                                                disabled>
                                                            <optgroup label="Sur América">
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+58' ? 'selected': ''}} value="+58">
                                                                    +58
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+57' ? 'selected': ''}} value="+57">
                                                                    +57
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+51' ? 'selected': ''}} value="+51">
                                                                    +51
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+56' ? 'selected': ''}} value="+56">
                                                                    +56
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+54' ? 'selected': ''}} value="+54">
                                                                    +54
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+55' ? 'selected': ''}} value="+55">
                                                                    +55
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+593' ? 'selected': ''}} value="+593">
                                                                    +593
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+591' ? 'selected': ''}} value="+591">
                                                                    +591
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+595' ? 'selected': ''}} value="+595">
                                                                    +595
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+59' ? 'selected': ''}} value="+59">
                                                                    +59
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Centro América">
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+507' ? 'selected': ''}} value="+507">
                                                                    +507
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+1-809' ? 'selected': ''}} value="+1-809">
                                                                    +1-809
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+502' ? 'selected': ''}} value="+502">
                                                                    +502
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+503' ? 'selected': ''}} value="+503">
                                                                    +503
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+504' ? 'selected': ''}} value="+504">
                                                                    +504
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+505' ? 'selected': ''}} value="+505">
                                                                    +505
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+506' ? 'selected': ''}} value="+506">
                                                                    +506
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+50' ? 'selected': ''}} value="+50">
                                                                    +50
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Norte América">
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+52' ? 'selected': ''}} value="+52">
                                                                    +52
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+1' ? 'selected': ''}} value="+1">
                                                                    +1
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Islas del Caribe">
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+1-787' ? 'selected': ''}} value="+1-787">
                                                                    +1-787
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+297' ? 'selected': ''}} value="+297">
                                                                    +297
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+599' ? 'selected': ''}} value="+599">
                                                                    +599
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+1-868' ? 'selected': ''}} value="+1-868">
                                                                    +1-868
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+1-242' ? 'selected': ''}} value="+1-242">
                                                                    +1-242
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+1-24' ? 'selected': ''}} value="+1-24">
                                                                    +1-24
                                                                </option>
                                                            </optgroup>
                                                            <optgroup label="Europa">
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+44' ? 'selected': ''}} value="+44">
                                                                    +44
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+34' ? 'selected': ''}} value="+34">
                                                                    +34
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+351' ? 'selected': ''}} value="+351">
                                                                    +351
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+39' ? 'selected': ''}} value="+39">
                                                                    +39
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+33' ? 'selected': ''}} value="+33">
                                                                    +33
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+4' ? 'selected': ''}} value="+4">
                                                                    +4
                                                                </option>
                                                                <option {{$companyOfficeNumber[0]===
                                                            '+49' ? 'selected': ''}} value="+49">
                                                                    +49
                                                                </option>
                                                            </optgroup>
                                                            <option {{$companyOfficeNumber[0]===
                                                        '+61' ? 'selected': ''}} value="+61">
                                                                +61
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <input type="text" class="form-control"
                                                           value="{{$companyOfficeNumber[1] ?? ''}}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company-website" class="text-primary">Website</label>
                                                <input id="company-website" type="text" class="form-control"
                                                       value="{{$companyProfile->website}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company-email" class="text-primary">Email
                                                    corporativo</label>
                                                <input id="company-email" type="email" class="form-control"
                                                       value="{{$companyProfile->email}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-select-country" class="text-primary">País</label>
                                                <select id="company-select-country"
                                                        class="custom-select flag-selector flag-selector--full"
                                                        {{ $companyProfile->country !== null ? 'disabled' : '' }} disabled>
                                                    <optgroup label="Sur América">
                                                        <option {{ $companyProfile->country === 'VE' ? 'selected':''
                                                        }} value="VE" data-flag="/img/landing/flags/ve.svg">Venezuela
                                                        </option>
                                                        <option {{ $companyProfile->country === 'CO' ? 'selected':''
                                                        }} value="CO" data-flag="/img/landing/flags/co.svg">Colombia
                                                        </option>
                                                        <option {{ $companyProfile->country === 'PE' ? 'selected':''
                                                        }} value="PE" data-flag="/img/landing/flags/pe.svg">Perú
                                                        </option>
                                                        <option {{ $companyProfile->country === 'CL' ? 'selected':''
                                                        }} value="CL" data-flag="/img/landing/flags/cl.svg">Chile
                                                        </option>
                                                        <option {{ $companyProfile->country === 'AR' ? 'selected':''
                                                        }} value="AR" data-flag="/img/landing/flags/ar.svg">Argentina
                                                        </option>
                                                        <option {{ $companyProfile->country === 'BR' ? 'selected':''
                                                        }} value="BR" data-flag="/img/landing/flags/br.svg">Brazil
                                                        </option>
                                                        <option {{ $companyProfile->country === 'EC' ? 'selected':''
                                                        }} value="EC" data-nb="true"
                                                                data-flag="/img/landing/flags/ec.svg">Ecuador
                                                        </option>
                                                        <option {{ $companyProfile->country === 'BO' ? 'selected':''
                                                        }} value="BO" data-nb="true"
                                                                data-flag="/img/landing/flags/bo.svg">Bolivia
                                                        </option>
                                                        <option {{ $companyProfile->country === 'PY' ? 'selected':''
                                                        }} value="PY" data-nb="true"
                                                                data-flag="/img/landing/flags/py.svg">Paraguay
                                                        </option>
                                                        <option {{ $companyProfile->country === 'UY' ? 'selected':''
                                                        }} value="UY" data-nb="true"
                                                                data-flag="/img/landing/flags/uy.svg">Uruguay
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Centro América">
                                                        <option {{ $companyProfile->country === 'PA' ? 'selected':''
                                                        }} value="PA" data-flag="/img/landing/flags/pa.svg">Panamá
                                                        </option>
                                                        <option {{ $companyProfile->country === 'GT' ? 'selected':''
                                                        }} value="GT" data-nb="true"
                                                                data-flag="/img/landing/flags/gt.svg">Guatemala
                                                        </option>
                                                        <option {{ $companyProfile->country === 'SV' ? 'selected':''
                                                        }} value="SV" data-nb="true"
                                                                data-flag="/img/landing/flags/sv.svg">El Salvador
                                                        </option>
                                                        <option {{ $companyProfile->country === 'HN' ? 'selected':''
                                                        }} value="HN" data-nb="true"
                                                                data-flag="/img/landing/flags/hn.svg">Honduras
                                                        </option>
                                                        <option {{ $companyProfile->country === 'NI' ? 'selected':''
                                                        }} value="NI" data-nb="true"
                                                                data-flag="/img/landing/flags/ni.svg">Nicaragua
                                                        </option>
                                                        <option {{ $companyProfile->country === 'CR' ? 'selected':''
                                                        }} value="CR" data-nb="true"
                                                                data-flag="/img/landing/flags/cr.svg">Costa Rica
                                                        </option>
                                                        <option {{ $companyProfile->country === 'BZ' ? 'selected':''
                                                        }} value="BZ" data-nb="true"
                                                                data-flag="/img/landing/flags/bz.svg">Belize
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Norte América">
                                                        <option {{ $companyProfile->country === 'MX' ? 'selected':''
                                                        }} value="MX" data-flag="/img/landing/flags/mx.svg">México
                                                        </option>
                                                        <option {{ $companyProfile->country === 'US' ? 'selected':''
                                                        }} value="US" data-flag="/img/landing/flags/us.svg">United
                                                            States
                                                        </option>
                                                        <option {{ $companyProfile->country === 'CA' ? 'selected':''
                                                        }} value="CA" data-nb="true"
                                                                data-flag="/img/landing/flags/ca.svg">Canada
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Islas del Caribe">
                                                        <option {{ $companyProfile->country === 'DO' ? 'selected':''
                                                        }} value="DO" data-nb="true"
                                                                data-flag="/img/landing/flags/do.svg">República
                                                            Dominicana
                                                        </option>
                                                        <option {{ $companyProfile->country === 'PR' ? 'selected':''
                                                        }} value="PR" data-nb="true"
                                                                data-flag="/img/landing/flags/pr.svg">Puerto Rico
                                                        </option>
                                                        <option {{ $companyProfile->country === 'AW' ? 'selected':''
                                                        }} value="AW" data-nb="true"
                                                                data-flag="/img/landing/flags/aw.svg">Aruba
                                                        </option>
                                                        <option {{ $companyProfile->country === 'CW' ? 'selected':''
                                                        }} value="CW" data-nb="true"
                                                                data-flag="/img/landing/flags/cw.svg">Curacao
                                                        </option>
                                                        <option {{ $companyProfile->country === 'TT' ? 'selected':''
                                                        }} value="TT" data-nb="true"
                                                                data-flag="/img/landing/flags/tt.svg">Trinidad y
                                                            Tobago
                                                        </option>
                                                        <option {{ $companyProfile->country === 'BS' ? 'selected':''
                                                        }} value="BS" data-nb="true"
                                                                data-flag="/img/landing/flags/bs.svg">Bahamas
                                                        </option>
                                                        <option {{ $companyProfile->country === 'BB' ? 'selected':''
                                                        }} value="BB" data-nb="true"
                                                                data-flag="/img/landing/flags/bb.svg">Barbados
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Europa">
                                                        <option {{ $companyProfile->country === 'GB' ? 'selected':''
                                                        }} value="GB" data-flag="/img/landing/flags/gb.svg">Reino Unido
                                                        </option>
                                                        <option {{ $companyProfile->country === 'ES' ? 'selected':''
                                                        }} value="ES" data-flag="/img/landing/flags/es.svg">España
                                                        </option>
                                                        <option {{ $companyProfile->country === 'PT' ? 'selected':''
                                                        }} value="PT" data-flag="/img/landing/flags/pt.svg">Portugal
                                                        </option>
                                                        <option {{ $companyProfile->country === 'IT' ? 'selected':''
                                                        }} value="IT" data-flag="/img/landing/flags/it.svg">Italia
                                                        </option>
                                                        <option {{ $companyProfile->country === 'FR' ? 'selected':''
                                                        }} value="FR" data-flag="/img/landing/flags/fr.svg">Francia
                                                        </option>
                                                        <option {{ $companyProfile->country === 'DE' ? 'selected':''
                                                        }} value="DE" data-flag="/img/landing/flags/de.svg">Alemania
                                                        </option>
                                                    </optgroup>
                                                    <option {{ $companyProfile->country === 'AU' ? 'selected':''}} value="AU"
                                                            data-flag="/img/landing/flags/au.svg">Australia
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="company-direccion" class="text-primary">Dirección</label>
                                                <input id="company-direccion" value="{{$companyProfile->street}}"
                                                       type="text"
                                                       class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-address_place_type" class="text-primary">
                                                    Tipo
                                                </label>
                                                <input id="company-address_place_type" type="text" class="form-control"
                                                       value="{{$companyProfile->address_place_type}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-address_floor" class="text-primary">
                                                    Planta / Piso
                                                </label>
                                                <input id="company-address_floor" type="text" class="form-control"
                                                       value="{{$companyProfile->address_floor}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-address_place_number" class="text-primary">
                                                    Número
                                                </label>
                                                <input id="company-address_place_number" type="text"
                                                       class="form-control"
                                                       value="{{$companyProfile->address_place_number}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-codigo-postal" class="text-primary">Código
                                                    Postal</label>
                                                <input id="company-codigo-postal" type="text" class="form-control"
                                                       value="{{$companyProfile->zip_code}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-ciudad" class="text-primary">Ciudad</label>
                                                <input id="company-ciudad" type="text" class="form-control"
                                                       value="{{$companyProfile->city}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" data-user-state="{{$companyProfile->state}}">
                                                <label for="company-select-state" class="text-primary"
                                                       style="color:#303392 !important">Estado/Departamento</label>
                                                <input id="company-ciudad" type="text" class="form-control"
                                                       value="{{$companyProfile->state}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        {{ csrf_field() }}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="merchant-username" class="text-primary">Nivel</label>
                                                <select class="custom-select" name="level"
                                                        value="{{$companyProfile->level}}">
                                                    <option value="1" @if ($companyProfile->level == 1) selected @endif>
                                                        1
                                                    </option>
                                                    <option value="2" @if ($companyProfile->level == 2) selected @endif>
                                                        2
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        @if ($companyProfile->status == 0)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="verify-user" class="text-primary">Status</label>
                                                    <select class="custom-select" name="status" value="">
                                                        <option value="0" disbled>No Verificado</option>
                                                        <option value="1">Verificado</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-4">
                                            <label for="">&nbsp;</label>
                                            <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                                                Actualizar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
<hr>
    <div class="container">
        <div class="col-12 mx-auto px-0 px-md-3">
            <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                <div class="card-body py-4 py-lg-4">
                    <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                    Tarjetas del usuario

                    <table class="table">
                        <thead class="text-primary">
                            <tr>
                                <th>Id.</th>
                                <th>Nombre.</th>
                                <th>Número</th>
                                <th>CVC</th>
                                <th>Expiry</th>
                                <th>Active</th>
                                 
                                <th>Foto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (tarjetasUser($personProfile->user_id) as $item)
                            <tr>
                                <td scope="row">{{ $item->id }}</td>
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->numero }}</td>
                                <td>{{ $item->cvc }}</td>
                                <td>{{ $item->expiry }}</td>
                                <td > <button class="btn">
                                          <span class="badge  {{ $item->active ? 'badge-success' : 'badge-danger' }}">{{ $item->active ? 'SI' : 'NO' }}</span>
                                </button></td>
                              
                                <td><a target="_blank" href="{{ asset($item->foto) }}"><img width="80"
                                    src="{{ asset($item->foto) }}" alt=""></a></td>

                                    <td> 
                                
                                    @if (!$item->verified)
                                    
                                    <form action="{{ url('user-profile-card-action/'.$item->id) }}">
        
                                        <div class="form-group">

                                            <div class="form-group">
                                                <label for=""></label>
                                                <select class="form-control" required name="proceso" id="">
                                                  <option value="">Seleccione</option>
                                                  <option value="Rechazado">Rechazado</option>
                                                  <option value="Aprobado">Aprobado</option>
                                                </select>
                                              </div>

                                        <input type="text" class="form-control" name="comentario" id="comentario"
                                            aria-describedby=""   placeholder="Comentario (Opcional)">

                                           
                                        </div>

                                        <button class="btn btn-primary" type="submit">Enviar</button>
                                         
                                    </form>
                                    
                                    @endif
                                    <p>{{ $item->comentario }}</p>
                                </td>
                            </tr>

                            
                            @endforeach
                            
                        </tbody>
                    </table>

                  
                        

                
                    </h6>
                </div>
            </div>
        </div>
      
    </div>
    <hr>
    <div class="container">
        <form class="" action="{{URL::to('internal-notes-user/'.$personProfile->user_id)}}"
              enctype="multipart/form-data"
              method="post">
            <hr>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for="t-note" class="text-primary">Internal Notes</label>
                        <textarea class="form-control" name="notes" id="t-note" rows="7"
                                  placeholder="Internal notes..."></textarea>
                    </div>
                    <div class="form-group col-sm-10">
                        <input type="file" name="file[]" id="miarchivo[]" multiple class="form-control"
                               placeholder="Agregue una respuesta"/>
                    </div>
                </div>
            </div>
            <div class="row">
                {{ csrf_field() }}
                <div class="col-md-4">
                    <label for="">&nbsp;</label>
                    <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                        Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>


    <hr/>

    @if ($personProfile !== null && $personProfile->approval_status === 1)
        <main style="margin-top:100px">
            <div class="container mt-md-n5">
                <div class="row">
                    <div class="col-12 mx-auto px-0 px-md-3">
                        <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                            <div class="card-body py-4 py-lg-4">
                                <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                    Este perfil ha sido completado
                                </h6><br>
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <div class="text-center mb-3 mb-md-5">

                                            <form action="{{URL::to('/approve-profile/'.$personProfile->id)}}" method="post" enctype="multipart/form-data">
                                                
                                                {{ csrf_field() }}
                                                <input type="hidden" id="gps" name="gps" value="">
                                                <button type="submit" id="approved" class="btn btn-light btn-block rounded-0">
                                                    Aprobar Perfil
                                                </button>
                                               
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <div class="text-center mb-3 mb-md-5">
                                            <button class="btn btn-light btn-block rounded-0" data-toggle="modal"
                                                    data-target="#refuse-profile-modal">
                                                Rechazar Perfil
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    @endif

    @if($personProfile->user->session_id !== 'block')
        @if ($personProfile !== null && $personProfile->approval_status === 2)
            <main style="margin-top:100px">
                <div class="container mt-md-n5">
                    <div class="row">
                        <div class="col-12 mx-auto px-0 px-md-3">
                            <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                <div class="card-body py-4 py-lg-4">
                                    <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                        Estado de Perfil: @if($personProfile->approval_status === 2)
                                            Aprobado
                                        @elseif($personProfile->approval_status === 3)
                                            Rechazado
                                        @endif
                                    </h6><br>
                                    <div class="row">
                                        <div class="col-6 col-md-6">
                                            <div class="text-center mb-3 mb-md-5">
                                                <form action="{{URL::to('/approve-profile/'.$personProfile->id)}}" method="post" enctype="multipart/form-data">
                                                
                                                    {{ csrf_field() }}
                                                    <input type="hidden" id="gps" name="gps" value="">
                                                    <button type="submit" id="approved" class="btn btn-light btn-block rounded-0 active text-white" disabled>
                                                        Aprobar Perfil
                                                    </button>
                                                   
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="text-center mb-3 mb-md-5">
                                                <button class="btn btn-light btn-block rounded-0" data-toggle="modal"
                                                        data-target="#refuse-profile-modal">
                                                    Inhabilitar Perfil
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        @endif

        @if ($personProfile !== null && $personProfile->approval_status === 3)
            <main style="margin-top:100px">
                <div class="container mt-md-n5">
                    <div class="row">
                        <div class="col-12 mx-auto px-0 px-md-3">
                            <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                <div class="card-body py-4 py-lg-4">
                                    <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                        Estado de Perfil
                                    </h6><br>
                                    <div class="row">
                                        <div class="col-6 col-md-6">
                                            <div class="text-center mb-3 mb-md-5">
                                                <form action="{{URL::to('/approve-profile/'.$personProfile->id)}}" method="post" enctype="multipart/form-data">
                                                
                                                    {{ csrf_field() }}
                                                    <input type="hidden" id="gps" name="gps" value="">
                                                    <button type="submit" id="approved" class="btn btn-light btn-block rounded-0">
                                                        Aprobar Perfil
                                                    </button>
                                                   
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="text-center mb-3 mb-md-5">
                                                <button class="btn btn-light btn-block rounded-0 active text-white"
                                                        disabled
                                                        data-toggle="modal" data-target="#refuse-profile-modal">
                                                    Inhabilitar Perfil
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </section>
                        </div>
                    </div>
            </main>
        @endif
        
    @endif

    @if ($personProfile->approval_status != 4)                                                    
        <main style="margin-top:100px">
            <div class="container mt-md-n5">
                <div class="row">
                    <div class="col-12 mx-auto px-0 px-md-3">
                        <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                            <div class="card-body py-4 py-lg-4">
                                <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                    Bloqueo de usuario
                                </h6><br>
                                <div class="row">
                                    @if($personProfile->user->session_id === 'block')
                                        <div class="col-12 col-md-12">
                                            <div class="text-center mb-3 mb-md-5">
                                                <a href="{{URL::to('/unblock-user/'.$personProfile->user_id)}}"
                                                class="btn btn-warning btn-block rounded-0">
                                                    Desbloquear usuario
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 col-md-12">
                                            <div class="text-center mb-3 mb-md-5">
                                                <a href="{{URL::to('/block-user/'.$personProfile->user_id)}}"
                                                class="btn btn-danger btn-block rounded-0">
                                                    Bloquear usuario
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    @endif


    @if($personProfile !== null):
    <main style="margin-top:100px">
        <div class="container mt-md-n5">
            <div class="row">
                <div class="col-12 mx-auto px-0 px-md-3">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="exchanges-tab" data-toggle="tab" href="#exchanges" role="tab"
                               aria-controls="exchanges" aria-selected="true">Historial de Cambios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="wallets-tab" data-toggle="tab" href="#wallets" role="tab"
                               aria-controls="wallets" aria-selected="false">Historial de Billetera</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="exchanges" aria-selected="true">Historial de Perfil</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" id="support-tab" data-toggle="tab" href="#support" role="tab"
                               aria-controls="support" aria-selected="true">Historial de Soporte</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" id="tier-tab" data-toggle="tab" href="#tier" role="tab"
                               aria-controls="exchanges" aria-selected="true">Documentos Tier</a>
                        </li>
                        @if ($personProfile->approval_status === 2)
                            <li class="nav-item">
                                <a class="nav-link" id="due-diligence-tab" data-toggle="tab" href="#dueDiligence" role="tab"
                                    aria-controls="exchanges" aria-selected="true">
                                    Due Diligence
                                </a>
                            </li> 
                        @endif
                        
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="exchanges" role="tabpanel"
                             aria-labelledby="exchanges-tab">
                            <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                <div class="card-body py-4 py-lg-4">
                                    <section class="mt-5 px-md-3 px-xl-4">
                                        <div id="user-transactions-div"
                                             class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                            <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                                Historial de Cambios
                                            </h6>
                                            <form action="{{url()->current()}}" method="get"
                                                  class="form-inline flex-md-nowrap ml-md-3">
                                                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-search text-muted"></i>
                                                    </span>
                                                    </div>
                                                    <input type="text" class="form-control" name="dest-name"
                                                           value="{{request()['dest-name']}}"
                                                           placeholder="Buscar por nombre">
                                                </div>
                                                <div class="input-group mb-3 mb-md-0 mr-3" style="width: 180px">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-search text-muted"></i>
                                                    </span>
                                                    </div>
                                                    <input type="text" class="form-control" name="dest-lastname"
                                                           value="{{request()['dest-lastname']}}"
                                                           placeholder="Buscar por Apellido">
                                                </div>
                                                <div class="input-group mb-3 mb-md-0">
                                                    <input type="date" id="creation-date-filter" class="form-control"
                                                           aria-label="Creation date filter" name="transaction-date"
                                                           value="{{request()['transaction-date']}}"
                                                           aria-describedby="creation-date-filter">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text bg-white text-muted"
                                                          id="creation-date-filter"><i
                                                                class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-3 mb-md-0">
                                                    <input type="submit" class="ml-3 btn btn-primary" value="Filtrar">
                                                </div>
                                                @if (request()['dest-name'] || request()['dest-lastname'] ||
                                                request()['transaction-date'])
                                                    <div class="input-group mb-3 mb-md-0">
                                                        <a href="{{URL::to('/user-profile/'.$personProfile->user_id)}}"
                                                           class="ml-3 btn btn-primary">Limpiar</a>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                        <div id="history-table" class="mb-5">
                                            @foreach ($userExchangeTransactions as $transaction)
                                                <div class="row border-bottom p-3">
                                                    <div class="col-6 col-md-2 px-1 mb-3 mb-md-0">
                                                        <small class="d-block text-muted">En la fecha</small>
                                                        <div class="font-14">
                                                            {{$transaction->getHumanDate($transaction->created_at)}}
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                                        <small class="d-block text-muted">Enviaste a</small>
                                                        <div class="font-weight-bold font-14">
                                                            @if (isset($transaction->destinationAccount))
                                                                {{$transaction->destinationAccount->name}}
                                                                {{$transaction->destinationAccount->lastname}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                                        <small class="d-block text-muted">En el país</small>
                                                        <div class="font-14">
                                                            @if (isset($transaction->destinationAccount))
                                                                {{$transaction->destinationAccount->getCountry()[1]}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                                        <small class="d-block text-muted">Método</small>
                                                        <div class="font-14">{{$transaction->paymentMethod()}}</div>
                                                    </div>
                                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                                        <small class="d-block text-muted">Estado</small>
                                                        <div class="font-14">
                                                            @php
                                                                $status = '';
                                                                if($transaction->status == 0) {
                                                                $status = 'Abierta';
                                                                }
                                                                if($transaction->status == 1){
                                                                $status = 'Apobada';
                                                                }
                                                                if($transaction->status == 2){
                                                                $status = 'Rechazada';
                                                                }
                                                                if($transaction->status == 3){
                                                                $status = 'Fallida';
                                                                }
                                                                if($transaction->status == 4){
                                                                $status = 'En proceso';
                                                                }
                                                                if($transaction->status == 5){
                                                                $status = 'Reembolsada';
                                                                }

                                                                echo $status;
                                                            @endphp
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md px-1 mb-3 mb-md-0">
                                                        <small class="d-block text-muted">Enviados</small>
                                                        <div class="text-secondary font-weight-bold h5 mb-0">
                                                            <small>{{$transaction->sender_fiat}}</small>
                                                            {{number_format($transaction->sender_fiat_amount,2)}}
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-1 px-1 text-center my-auto">
                                                        <a href="/tools/gen-t-pdf?id={{$transaction->id}}"
                                                           target="_blank"
                                                           class="btn-transparent">
                                                            <img src="/img/landing/pdfDown.svg" class="img-fluid">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                {{$userExchangeTransactions->fragment('user-transactions-div')->links()}}
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="wallets" role="tabpanel" aria-labelledby="wallets-tab">
                            <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                <div class="card-body py-4 py-lg-4">
                                    <section class="mt-5 px-md-3 px-xl-4">
                                        <div id="user-transactions-div-no-vue"
                                             class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                            <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                                Historial de Billetera
                                            </h6>
                                        </div>
                                        <div id="history-table-no-vue" class="mb-5">
                                            @foreach ($userWallets as $wallet)
                                                <div class="col-3 col-md-3 col-xl-2 px-1 mb-2 mb-md-0px-1 mt-3">
                                                    <img src="/img/landing/wallet-{{$wallet['currency']}}-primary.svg"
                                                         alt="{{$wallet['currency']}} Icon" class="d-inline-block mb-2">
                                                    <h6 class="cardBank__title">Dólares Disponibles</h6>
                                                    <!--
                                                                        <h6 class="cardBank__title" v-if="wallet['currency'] === 'GBP'">Libras
                                                                            Esterlinas</h6>
                                                                        <h6 class="cardBank__title" v-if="wallet['currency'] === 'EUR'">Euros</h6>
                                                                    -->
                                                    <div class="cardBank__info">
                                                        {{number_format($wallet['numbers']['available'], 2)}}
                                                        {{$wallet['currency']}}
                                                    </div>
                                                </div>
                                                <hr>
                                                @if (!empty($wallet['customerTransactions']['inHold']))
                                                    <h6 class="text-primary font-weight-bold mt-4 mt-md-5 mb-2">En
                                                        espera</h6>
                                                @endif
                                                <ul class="walletHistory list-unstyled">
                                                @foreach ($wallet['customerTransactions']['inHold'] as $transaction)
                                                    @if ($transaction['exchange_related'] !== 1)
                                                        <!--In Hold-->
                                                            <li class="walletHistory__item mb-2">
                                                                <div class="walletHistory__item__body">
                                                                    <div class="media">
                                                                        @if ($transaction['purpose'] === 2)
                                                                            <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                                                                 title="Salida en espera">
                                                                                <svg width="25" height="21"
                                                                                     viewBox="0 0 25 21" fill="none"
                                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                                    <path d="M2 13L14 1M14 1H7.14286M14 1V7"
                                                                                          stroke="#FFC107"
                                                                                          stroke-width="2"
                                                                                          stroke-linecap="square"/>
                                                                                    <path
                                                                                            d="M12 19.7877C12 19.7877 15 17.1141 18 18.7877C21 20.4612 24 17.7877 24 17.7877"
                                                                                            stroke="#FFC107"
                                                                                            stroke-width="2"/>
                                                                                    <path
                                                                                            d="M12 15C12 15 15 12.3265 18 14C21 15.6735 24 13 24 13"
                                                                                            stroke="#FFC107"
                                                                                            stroke-width="2"/>
                                                                                </svg>
                                                                            </div>
                                                                        @else
                                                                            <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                                                                 title="Entrada en espera">
                                                                                <svg width="24" height="22"
                                                                                     viewBox="0 0 24 22" fill="none"
                                                                                     xmlns="http://www.w3.org/2000/svg">
                                                                                    <path d="M13 2L1 14M1 14H7.85714M1 14V8"
                                                                                          stroke="#FFC107"
                                                                                          stroke-width="2"
                                                                                          stroke-linecap="square"/>
                                                                                    <path
                                                                                            d="M11.0002 20.7877C11.0002 20.7877 14.0002 18.1141 17.0002 19.7877C20.0002 21.4612 23.0002 18.7877 23.0002 18.7877"
                                                                                            stroke="#FFC107"
                                                                                            stroke-width="2"/>
                                                                                    <path
                                                                                            d="M11.0002 16C11.0002 16 14.0002 13.3265 17.0002 15C20.0002 16.6736 23.0002 14 23.0002 14"
                                                                                            stroke="#FFC107"
                                                                                            stroke-width="2"/>
                                                                                </svg>
                                                                            </div>
                                                                        @endif
                                                                        <div class="media-body">
                                                                            @if ($transaction['purpose'] === 2)
                                                                                <div class="d-flex justify-content-between">
                                                                                    <h5 class="walletHistory__item__amount mb-0">
                                                                                        -
                                                                                        {{number_format($transaction['sender_fiat_amount'], 2)}}
                                                                                        {{$transaction['sender_fiat']}}
                                                                                        <small>
                                                                                            =>
                                                                                            {{number_format($transaction['receiver_fiat_amount'], 2)}}
                                                                                            {{$transaction['receiver_fiat']}}
                                                                                        </small>
                                                                                    </h5>
                                                                                    <div class="walletHistory__item__date text-muted">
                                                                                        @php
                                                                                            $dt = new Carbon($transaction['created_at']);
                                                                                            $dt = $dt->subMinutes(300);
                                                                                        @endphp
                                                                                        {{$dt}}
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <div class="d-flex justify-content-between">
                                                                                    <h5 class="walletHistory__item__amount mb-0">
                                                                                        +
                                                                                        {{number_format($transaction['sender_fiat_amount'], 2)}}
                                                                                        {{$transaction['sender_fiat']}}
                                                                                        <small>
                                                                                            =>
                                                                                            {{number_format($transaction['receiver_fiat_amount'], 2)}}
                                                                                            {{$transaction['receiver_fiat']}}
                                                                                        </small>
                                                                                    </h5>
                                                                                    <div class="walletHistory__item__date text-muted">
                                                                                        @php
                                                                                            $dt = new Carbon($transaction['created_at']);
                                                                                            $dt = $dt->subMinutes(300);
                                                                                        @endphp
                                                                                        {{$dt}}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                            <div class="d-flex flex-wrap justify-content-between">
                                                                                @if ($transaction['purpose'] === 2)
                                                                                    <div class="text-uppercase">
                                                                                        Retiro en espera a la cuenta de
                                                                                        <span class="font-weight-bold">
                                                                        {{$transaction['related_transaction']['destination_account']['name']}}
                                                                                            {{$transaction['related_transaction']['destination_account']['lastname']}}
                                                                    </span>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="text-uppercase">
                                                                                        Recarga en espera de aprobación
                                                                                    </div>
                                                                                @endif
                                                                                @if ($transaction['related_transaction'])
                                                                                    <div>
                                                                                        Tracking ID:
                                                                                        {{$transaction['related_transaction']['tracking_id']}}
                                                                                    </div>
                                                                                @endif
                                                                                @if ($transaction['purpose'] === 1)
                                                                                    <div>
                                                                                        Tracking ID:
                                                                                        {{$transaction['tracking_id']}}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                                <hr class="my-4 my-md-5">
                                                @foreach ($wallet['customerTransactions']['completed'] as $yearNumber =>
                                                $yearArray)
                                                    <div class="loader--wrapper">
                                                        @foreach ($yearArray as $monthNumber => $monthArray)
                                                            <ul class="walletHistory list-unstyled">
                                                                <!--End In Hold-->
                                                                <li class="font-13 text-muted mt-4 mt-md-5 mb-2"
                                                                    style="text-transform: capitalize">
                                                                    {{$monthNumber}} {{$yearNumber}}
                                                                </li>
                                                                @foreach ($monthArray as $transaction)
                                                                    <li class="walletHistory__item mb-2">
                                                                        <div class="walletHistory__item__body">
                                                                            <div class="media">
                                                                                <!--Conditional Icon-->
                                                                                @if (($transaction['type'] === 2 && $transaction['status']
                                                                                === 1) ||
                                                                                ($transaction['type'] === 4 && $transaction['purpose'] ===
                                                                                2))
                                                                                    <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                                                                         title="Envío">
                                                                                        <svg width="19" height="18"
                                                                                             viewBox="0 0 19 18"
                                                                                             fill="none"
                                                                                             xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                    d="M2 16.2727L17.2727 1M17.2727 1H8.54545M17.2727 1V8.63636"
                                                                                                    stroke="#38A1DC"
                                                                                                    stroke-width="2"
                                                                                                    stroke-linecap="square"/>
                                                                                        </svg>
                                                                                    </div>
                                                                                @endif
                                                                                @if (($transaction['type'] === 1 && $transaction['status']
                                                                                === 1) ||
                                                                                ($transaction['type'] === 4 && $transaction['purpose'] ===
                                                                                1))
                                                                                    <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                                                                         title="Recibido">
                                                                                        <svg width="19" height="19"
                                                                                             viewBox="0 0 19 19"
                                                                                             fill="none"
                                                                                             xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                    d="M16.6364 2.36362L1.36362 17.6364M1.36362 17.6364H10.0909M1.36362 17.6364V9.99999"
                                                                                                    stroke="#1DBA44"
                                                                                                    stroke-width="2"
                                                                                                    stroke-linecap="square"/>
                                                                                        </svg>
                                                                                    </div>
                                                                                @endif
                                                                                @if ($transaction['status'] === 3)
                                                                                    <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                                                                         title="Transacción rechazada">
                                                                                        <svg width="16" height="16"
                                                                                             viewBox="0 0 16 16"
                                                                                             fill="none"
                                                                                             xmlns="http://www.w3.org/2000/svg">
                                                                                            <path d="M1 15L15 1"
                                                                                                  stroke="#DC3545"
                                                                                                  stroke-width="2"/>
                                                                                            <path d="M15 15L1 1"
                                                                                                  stroke="#DC3545"
                                                                                                  stroke-width="2"/>
                                                                                        </svg>
                                                                                    </div>
                                                                            @endif
                                                                            <!--End Conditional Icon-->
                                                                                <div class="media-body">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <h5 class="walletHistory__item__amount mb-0
                                                                        {{$transaction['status'] === 3 ? 'text-strike' : ''}}">
                                                                                            <!--Out Symbol-->
                                                                                        {{($transaction['purpose'] !== null &&
                                                                                            $transaction['purpose'] === 2) ||
                                                                                        ($transaction['purpose'] === null &&
                                                                                        $transaction['type'] === 2) ? '-' : ''}}
                                                                                        <!--In Symbol-->
                                                                                        {{($transaction['purpose'] !== null &&
                                                                                            $transaction['purpose'] === 1) ||
                                                                                        ($transaction['purpose'] === null &&
                                                                                        $transaction['type'] === 1) ? '+' : ''}}
                                                                                        <!--Out-->
                                                                                            @if (($transaction['purpose'] !== null &&
                                                                                            $transaction['purpose'] === 2) ||
                                                                                            ($transaction['purpose'] === null &&
                                                                                            $transaction['type'] === 2))
                                                                                                <span>
                                                                            {{number_format($transaction['amount'], 2)}}
                                                                                                    {{$transaction['currency']}}
                                                                                                    @if ($transaction['receiver_fiat_amount'])
                                                                                                        <small>
                                                                                =>
                                                                                {{number_format($transaction['receiver_fiat_amount'], 2)}}
                                                                                                            {{$transaction['receiver_fiat']}}
                                                                            </small>
                                                                                                    @endif
                                                                        </span>
                                                                                            @endif
                                                                                        <!--In-->
                                                                                            @if (($transaction['purpose'] !== null &&
                                                                                            $transaction['purpose'] === 1) ||
                                                                                            ($transaction['purpose'] === null &&
                                                                                            $transaction['type'] === 1))
                                                                                                <span>
                                                                            @if ($transaction['sender_fiat_amount'])
                                                                                                        <span>
                                                                                {{number_format($transaction['sender_fiat_amount'],2)}}
                                                                                                            {{$transaction['sender_fiat']}}
                                                                            </span>
                                                                                                    @else
                                                                                                        <span>
                                                                                {{number_format($transaction['amount'],2)}}
                                                                                                            {{$transaction['currency']}}
                                                                            </span>
                                                                                                    @endif
                                                                                                    @if ($transaction['exchange_related'] !== 1)
                                                                                                        <small>
                                                                                =>
                                                                                {{number_format($transaction['receiver_fiat_amount'],2)}}
                                                                                                            {{$transaction['receiver_fiat']}}
                                                                            </small>
                                                                                                    @endif
                                                                        </span>
                                                                                            @endif
                                                                                        </h5>
                                                                                        <div class="walletHistory__item__date text-muted">
                                                                                            @php
                                                                                                $dt = new Carbon($transaction['created_at']);
                                                                                                $dt = $dt->subMinutes(300);
                                                                                            @endphp
                                                                                            {{$dt}}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="d-flex flex-wrap justify-content-between">
                                                                                        @if ($transaction['related_transaction'] &&
                                                                                        ($transaction['purpose'] === 2 ||
                                                                                        $transaction['type'] === 2))
                                                                                            <div class="text-uppercase">
                                                                                                Envío a
                                                                                                <span class="font-weight-bold">
                                                                            {{$transaction['related_transaction']['destination_account']['name']}}
                                                                                                    {{$transaction['related_transaction']['destination_account']['lastname']}}
                                                                        </span>
                                                                                            </div>
                                                                                        @elseif ($transaction['type'] === 4 &&
                                                                                        $transaction['purpose'] === 2)
                                                                                            <div class="text-uppercase">
                                                                                                Envío a
                                                                                                <span class="font-weight-bold">
                                                                            {{$transaction['metadata']['related_email']}}
                                                                        </span>
                                                                                            </div>
                                                                                        @elseif ($transaction['type'] === 4 &&
                                                                                        $transaction['purpose'] === 1)
                                                                                            <div class="text-uppercase">
                                                                                                Recibido desde
                                                                                                <span class="font-weight-bold">
                                                                            {{$transaction['metadata']['related_email']}}
                                                                        </span>
                                                                                            </div>
                                                                                        @elseif ($transaction['type'] === 1 &&
                                                                                        $transaction['exchange_related'] === 0)
                                                                                            <div class="text-uppercase">
                                                                                                Recarga a Billetera
                                                                                                personal.
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="text-uppercase"></div>
                                                                                        @endif
                                                                                        @if ($transaction['related_transaction'])
                                                                                            <div>
                                                                                                Tracking ID:
                                                                                                {{$transaction['related_transaction']['tracking_id']}}
                                                                                            </div>
                                                                                        @endif
                                                                                        @if ($transaction['type'] === 4)
                                                                                            <div>
                                                                                                Tracking ID:
                                                                                                {{$transaction['tracking_id']}}
                                                                                            </div>
                                                                                        @elseif (!$transaction['exchange_related'] &&
                                                                                        $transaction['tracking_id'])
                                                                                            <div>
                                                                                                Tracking ID:
                                                                                                {{$transaction['tracking_id']}}
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    @if ($transaction['status'] === 3)
                                                                                        <div class="text-danger font-weight-bold">
                                                                                            Su operación fue rechazada o
                                                                                            cancelada.
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                <div class="card-body py-4 py-lg-4">
                                    <section class="mt-5 px-md-3 px-xl-4">
                                        <div id="user-transactions-div"
                                             class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                            <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                                Historial de Perfil
                                            </h6>
                                        </div>
                                        <div id="history-table" class="mb-5">
                                            @foreach ($notes as $note)
                                                <div class="row border-bottom p-3">
                                                    <div class="container p-4 text-justify"
                                                         style="background: #fafafa;">
                                                        <h6>
                                                            <span class="text-primary font-weight-bold">{{$note->traderProfile->id}} - {{$note->traderProfile->name}}</span>
                                                        </h6>
                                                        <h6 style="white-space: pre-line;">
                                                            {{$note->msg}}
                                                        </h6>
                                                        <span class="text-muted">{{ $note->created_at->format('d/m/Y g:i A') }} - {{ $note->ip }} </span>
                                                        <hr>
                                                        @php
                                                            $files = explode(" , ", $note->file);
                                                            foreach ($files as $key) {
                                                                $fileArray = explode('.', $note->file);
                                                                $endType = end($fileArray);
                                                            }
                                                            $cont = 1;
                                                        @endphp
                                                        <h6>
                                                            <span class="text-primary font-weight-bold">Archivos: {{ count($files)-1 }}</span>
                                                        </h6>
                                                        @foreach ($files as $item)
                                                            @php
                                                                $fileArray = explode('.', $item);
                                                                $endType = end($fileArray);
                                                            @endphp

                                                            @if($item != '')
                                                                @if ($endType === 'jpeg' || $endType === 'gif' || $endType === 'png' || $endType === 'jpg')
                                                                    <a href="{{$item}}" title="Ver archivo"
                                                                       target="_blank">
                                                                        <img src="{{$item}}" class="img-fluid"
                                                                             style="height: 200px !important"
                                                                             alt="Imagen">
                                                                    </a>
                                                                @else
                                                                    <a href="{{$item}}" title="Ver archivo {{$cont}}"
                                                                       target="_blank">
                                                                        Ver Archivo {{ $cont }}
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @php
                                                                $cont++
                                                            @endphp
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="tab-pane fade" id="support" role="tabpanel" aria-labelledby="support-tab">
                            <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                <div class="card-body py-4 py-lg-4">
                                    <section class="mt-5 px-md-3 px-xl-4">
                                        <div id="user-support-div"
                                             class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                            <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                                Historial de Soporte
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @if(!isset($tickets))
                                            <div class="py-4 py-lg-5">
                                                <div class="row">
                                                    <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                                        <img src="img/landing/empty-transactions.svg" alt="Transactions Empty"
                                                        class="img-fluid mb-4" style="max-height: 100px">
                                                        <h5 class="text-primary">Actualmente el historial de soporte esta vacío</h5>
                                                        <h6 class="text-muted">Todas los tickets se mostrarán aquí
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <ul class="walletHistory list-unstyled">
                                                @foreach($tickets as $ticket)
                                                <li class="walletHistory__item mb-2">
                                                    <a href="{{URL::to('/ticket/'.$ticket->ticket_number)}}">
                                                        <div class="walletHistory__item__body">
                                                            <div class="media">
                                                                <div class="media-body">
                                                                    <div class="d-flex justify-content-between">
                                                                        <h5 class="walletHistory__item__amount">#{{$ticket->ticket_number}}</h5>
                                                                        <div class="walletHistory__item__date">
                                                                            {{date('m/d/Y H:i',strtotime($ticket->created_at))}}
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex flex-wrap justify-content-between">
                                                                        <div class="">
                                                                            {{$ticket->department}}
                                                                        </div>
                                                                        <div>
                                                                            {{$ticket->status}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div> --}}
                        <div class="tab-pane fade" id="tier" role="tabpanel" aria-labelledby="tier-tab">
                            <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                <div class="card-body py-4 py-lg-4">
                                    <section class="mt-5 px-md-3 px-xl-4">
                                        <div id="user-transactions-div"
                                             class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                            <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                                Documentos Tier
                                            </h6>
                                        </div>
                                        <div id="history-table" class="mb-5">
                                            <main style="margin-top:100px">
                                                <div class="container mt-md-n5">
                                                    <div class="row">
                                                        <div class="col-12 mx-auto px-0 px-md-3">
                                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                @foreach($tiers as $tier)
                                                                    <li class="nav-item">
                                                                        <a class="nav-link" id="tier-{{$tier->id}}-tab" data-toggle="tab" href="#tier-{{$tier->id}}" role="tab"
                                                                        aria-controls="tier" aria-selected="true">{{$tier->name}}</a>
                                                                    </li>
                                                                @endforeach
                                                                      
                                                            </ul>
                                                            <div class="tab-content" id="myTabContent">
                                                                @foreach($tiers as $tier)
                                                                    <div class="tab-pane fade" id="tier-{{$tier->id}}" role="tabpanel" aria-labelledby="tier-{{$tier->id}}-tab">
                                                                        <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                                                            <div class="card-body py-4 py-lg-4">
                                                                                <section class="mt-5 px-md-3 px-xl-4">
                                                                                    <div id="user-transactions-div-no-vue"
                                                                                        class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                                                                        <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                                                                            {{$tier->name}}
                                                                                        </h6>
                                                                                        <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                                                                        @if( $personProfile->approval_status === 4 )
                                                                                        <a href="{{URL::to('/unblock-tier-user/'.$personProfile->user_id )}}"
                                                                                            class="btn btn-danger btn-block rounded-0">
                                                                                            Aprobar {{$tier->name}}
                                                                                        </a>
                                                                                            
                                                                                        @endif
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div id="history-table-no-vue" class="mb-5">
                                                                                        @foreach ($tierFiles as $tierFile)
                                                                                            @if($tierFile->tier_id == $tier->id)
                                                                                                <div class="row border-bottom p-3">
                                                                                                    <div class="container p-4 text-justify"
                                                                                                        style="background: #fafafa;">
                                                                                                        <span class="text-muted">{{ $tierFile->created_at->format('d/m/Y g:i A') }} </span>
                                                                                                        <hr>
                                                                                                        @php
                                                                                                            $files = explode(" , ", $tierFile->requeriments_tier);
                                                                                                            foreach ($files as $key) {
                                                                                                                $fileArray = explode('.', $tierFile->requeriments_tier);
                                                                                                                $endType = end($fileArray);
                                                                                                            }
                                                                                                            $cont = 1;
                                                                                                        @endphp
                                                                                                        <h6>
                                                                                                            <span class="text-primary font-weight-bold">Archivos: {{ count($files)-1 }}</span>
                                                                                                        </h6>
                                                                                                        @foreach ($files as $item)
                                                                                                            @php
                                                                                                                $fileArray = explode('.', $item);
                                                                                                                $endType = end($fileArray);
                                                                                                            @endphp

                                                                                                            @if($item != '')
                                                                                                                @if ($endType === 'jpeg' || $endType === 'gif' || $endType === 'png' || $endType === 'jpg')
                                                                                                                    <a href="{{$item}}" title="Ver archivo"
                                                                                                                    target="_blank">
                                                                                                                        <img src="{{$item}}" class="img-fluid"
                                                                                                                            style="height: 200px !important"
                                                                                                                            alt="Imagen">
                                                                                                                    </a>
                                                                                                                    <hr/>
                                                                                                                @else
                                                                                                                    <a href="{{$item}}" title="Ver archivo {{$cont}}"
                                                                                                                    target="_blank">
                                                                                                                        Ver Archivo {{ $cont }}
                                                                                                                    </a>
                                                                                                                    <hr/>
                                                                                                                @endif
                                                                                                            @endif
                                                                                                            @php
                                                                                                                $cont++
                                                                                                            @endphp
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                            
                                                                                        @endforeach
                                                                                    </div>
                                                                                </section>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </main>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                        @if ($personProfile->approval_status === 2)
                            <div class="tab-pane fade" id="dueDiligence" role="tabpanel" aria-labelledby="due-diligence-tab">
                                <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                                    <div class="card-body py-4 py-lg-4">
                                        <section class="mt-5 px-md-3 px-xl-4">
                                            <div id="user-transactions-div"
                                                class="d-flex justify-content-between align-items-end border-bottom flex-wrap pb-3">
                                                <h6 class="text-primary font-weight-bold text-truncate mb-md-0">
                                                    Due Diligence
                                                </h6>
                                            </div>
                                            <div id="history-table" class="mb-5">
                                                <div class="col-12 col-md-12 my-auto">
                                                    <div id="history-table-no-vue">
                                                        <div class="col-12 col-md-12 col-xl-12 px-1 mb-2 mb-md-0px-1 mt-3">
                                                            <a target="_blank" href="/tools/due-diligence-pdf?lang=ES&id={{$personProfile->user_id}}&trader_id={{$personProfile->id_approved}}">
                                                                <h6 class="cardBank__title pt-2 px-2">Due Dilingece (ES) </h6>
                                                            </a>
                                                        </div>
                                                        <div class="col-12 col-md-12 col-xl-12 px-1 mb-2 mb-md-0px-1 mt-3">
                                                            <a target="_blank" href="/tools/due-diligence-pdf?lang=EN&id={{$personProfile->user_id}}&trader_id={{$personProfile->id_approved}}">
                                                                <h6 class="cardBank__title pt-2 px-2">Due Dilingece (EN) </h6>
                                                            </a>
                                                        </div>
                                                        <hr>                                                        
                                                    </div>
                                                    {{-- <div class="container">
                                                        <a href="/tools/gen-t-pdf"
                                                    target="_blank"
                                                    class="btn-transparent">
                                                        <img src="/img/landing/pdfDown.svg" class="img-fluid">
                                                    </a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- Verify phone number modal -->
    <div class="modal fade" id="verify-phone" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
         aria-labelledby="verify-phone-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary" id="verify-phone-modal">Verificación de tu celular</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="bg-primary text-center text-white p-4">
                    <svg class="mb-3" width="135" height="94" viewBox="0 0 135 94" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                                d="M51.6611 41.4677C48.9655 35.4133 51.6884 28.32 57.7428 25.6243L104.334 4.88078C110.388 2.18517 117.481 4.90804 120.177 10.9625L129.125 31.0605C131.821 37.1149 129.098 44.2083 123.044 46.9039L76.4527 67.6474C70.3983 70.3431 63.3049 67.6202 60.6093 61.5657L51.6611 41.4677Z"
                                fill="#FFE37B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M105.554 7.62142L58.963 28.365C54.4222 30.3867 52.38 35.7067 54.4017 40.2475L63.35 60.3455C65.3717 64.8864 70.6917 66.9285 75.2325 64.9068L121.823 44.1632C126.364 42.1415 128.406 36.8215 126.385 32.2807L117.436 12.1827C115.415 7.64186 110.095 5.59971 105.554 7.62142ZM57.7428 25.6243C51.6884 28.32 48.9655 35.4133 51.6611 41.4677L60.6093 61.5657C63.3049 67.6202 70.3983 70.3431 76.4527 67.6474L123.044 46.9039C129.098 44.2083 131.821 37.1149 129.125 31.0605L120.177 10.9625C117.481 4.90804 110.388 2.18517 104.334 4.88078L57.7428 25.6243Z"
                              fill="#FFBE1B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M62.419 34.1252C62.5585 33.3086 63.3335 32.7597 64.1501 32.8991L90.9309 37.4721L105.452 14.5102C105.895 13.81 106.822 13.6014 107.522 14.0442C108.222 14.487 108.431 15.4135 107.988 16.1137L92.3974 40.7659L63.6451 35.8563C62.8285 35.7169 62.2796 34.9419 62.419 34.1252Z"
                              fill="#FFBE1B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M85.0623 37.049C85.8357 37.3459 86.222 38.2135 85.9251 38.9869L79.59 55.4905C79.2931 56.2639 78.4255 56.6502 77.6521 56.3533C76.8787 56.0564 76.4924 55.1888 76.7893 54.4154L83.1244 37.9118C83.4213 37.1384 84.2889 36.7521 85.0623 37.049Z"
                              fill="#FFBE1B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M94.5436 32.8276C94.2468 33.601 94.633 34.4687 95.4065 34.7655L111.91 41.1006C112.683 41.3975 113.551 41.0112 113.848 40.2378C114.145 39.4644 113.758 38.5968 112.985 38.2999L96.4816 31.9648C95.7082 31.6679 94.8405 32.0542 94.5436 32.8276Z"
                              fill="#FFBE1B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M3.8501 59.4707C3.62546 58.9661 3.85237 58.375 4.3569 58.1504L39.0716 42.6944C39.5762 42.4698 40.1673 42.6967 40.3919 43.2012C40.6165 43.7057 40.3896 44.2969 39.8851 44.5215L5.17038 59.9775C4.66584 60.2021 4.07473 59.9752 3.8501 59.4707Z"
                              fill="#6857E5"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M17.8662 66.3659C17.6416 65.8613 17.8685 65.2702 18.373 65.0456L43.9523 53.657C44.4568 53.4323 45.0479 53.6592 45.2726 54.1638C45.4972 54.6683 45.2703 55.2594 44.7658 55.4841L19.1865 66.8727C18.682 67.0973 18.0908 66.8704 17.8662 66.3659Z"
                              fill="#6857E5"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M30.0557 74.0745C29.831 73.57 30.0579 72.9788 30.5625 72.7542L48.8334 64.6195C49.3379 64.3948 49.929 64.6217 50.1537 65.1263C50.3783 65.6308 50.1514 66.2219 49.6469 66.4466L31.3759 74.5813C30.8714 74.8059 30.2803 74.579 30.0557 74.0745Z"
                              fill="#6857E5"></path>
                    </svg>
                    <p class="mb-1">En unos segundos reicibirás un mensaje de texto con un código</p>
                </div>
                <div class="modal-body pt-4">
                    <!-- <p class="text-primary">Ingresa el código que te hemos enviado</p> -->

                    <!-- show this alert after phone verification -->
                    <div class="alert alert-success lh-125 mb-3" id="__successful_verify" style="display: none">
                        <div class="media">
                            <img src="/img/landing/success-icon.svg" class="alert-icon mr-3">
                            <div class="media-body">¡Tu número telofónico ha sido verificado exitosamente!</div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="__verification_code_input">Ingresa el código que te hemos enviado</label>
                        <div class="d-flex flex-column">
                            <input id="__verification_code_input" type="text"
                                   class="form-control form-control-lg text-center font-weight-bold"
                                   style="letter-spacing: 5px">
                            <button class="btn btn-outline-success px-4 mt-2" id="__verify_code">
                                Verificar
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-info font-14 lh-125 mb-0">
                        <div class="media">
                            <img src="/img/landing/info-icon.svg" class="alert-icon mr-3">
                            <div class="media-body">
                                Recuerda que este número de celular se usará en tu cuenta personal, para hacer cambios
                                deberás <a href="/contact" class="alert-link">contactar</a> con nuestro equipo.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-pill btn-block btn-sm px-3" data-dismiss="modal">
                        Listo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- refuse profile modal -->
    <div class="modal fade" id="refuse-profile-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary font-weight-bold">Rechazar Perfil</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <form class="" action="{{URL::to('/refuse-profile/'.$personProfile->id)}}" method="post"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="container">
                            <div class="form-group">
                                <label for="subjects">Motivo del Rechazo</label>
                                <select class="subjects-multiple" name="subjects[]" multiple="multiple" style="width: 100%;" required>
                                    @foreach($subjetcs as $subjetc)
                                        <option value="{{ $subjetc->id }}"> {{ $subjetc->subject }} </option>
                                    @endforeach
                                </select>
                                @if( Auth::user()->role_id === 6 || Auth::user()->role_id === 1)
                                    <div class="mt-2 button-ud">
                                        <button onclick="event.preventDefault();" data-toggle="modal" data-target="#subjects-modal"
                                                class="btn-edit btn btn-light rounded-0">
                                            Crear Nuevo Motivo
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="sm-monto" class="text-primary">Escriba las razones por las cuales rechaza el
                                    perfil.</label>
                                <textarea name="reasons" class="form-control" rows="8" required>
                                    Queremos agradecer tu interés en suscribirte a American Kryptos Bank (AKB), nuestro único objetivo es enseñarte a utilizar las finanzas descentralizadas y que tu seas quién decida donde y cuando puedes tener tu dinero en tan solo minutos.
                                    ⠀
                                    Para lograr con éxito  la verificación de tu perfil deberás realizar las siguientes modificaciones para dar cumplimiento con las normas internacionales AML, KYC, BSA.                                    ⠀
                                </textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" id="" class="btn btn-light btn-block rounded-0">
                                    Rechazar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="subjects-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="container align-content-center">
                        <h5>Crear Motivo</h5>
                        <div class="container py-3">
                            <table class="table text-center table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Motivo</th>
                                    <th>Detalles</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody id="list_subjects">
                                @foreach($subjetcs as $subjetc)
                                    <tr>
                                        <td>{{ $subjetc->id }}</td>
                                        <td>{{ $subjetc->subject }}</td>
                                        <td>{{ $subjetc->details }}</td>

                                        <th>
                                            <a data-toggle="modal" data-target="#edit-modal-{{ $subjetc->id }}"
                                               href="#">Editar</a>
                                            -
                                            <a href="{{URL::to('/delete-subject-user/'.$subjetc->id)}}">Borrar</a>
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                            <button data-toggle="modal" data-target="#new-modal"
                                    class="btn-edit btn btn-light rounded-0">
                                Nuevo Motivo
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @foreach($subjetcs as $subjetc)
        <div class="modal fade" id="edit-modal-{{ $subjetc->id }}" tabindex="-1"
            role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title text-primary font-weight-bold">Editar</h6>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="container align-content-center">
                            <h5>Editar</h5>
                            <div class="container py-3">
                                <form action="{{URL::to('/edit-subject-user/'.$subjetc->id)}}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input type="hidden" name="status"
                                            value="{{ $subjetc->status }}"/>
                                        <input class="form-control" type="text"
                                            name="subject"
                                            value="{{ $subjetc->subject }}"/>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="detail_subject" cols="30" rows="10" placeholder="Detalles">{{ $subjetc->details }}</textarea>
                                    </div>
                                    <button type="submit"
                                            class="btn btn-success rounded-0">Guardar
                                    </button>
                                    <button class="btn btn-light rounded-0"
                                            data-dismiss="modal">Cancelar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="new-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary font-weight-bold">Nuevo Motivo</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="container align-content-center">
                        <h5>Agregar un nuevo motivo</h5>
                        <div class="container py-3">
                            <form action="{{URL::to('new-subject-user')}}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input class="form-control" type="text" name="subject" placeholder="Nuevo Motivo"/>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="detail_subject" cols="30" rows="10" placeholder="Detalles"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success rounded-0">Guardar</button>
                                <button class="btn btn-light rounded-0" data-dismiss="modal">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($personProfile->approval_status != 2)
        <script>

                fetch('https://api.ipdata.co/?api-key=4f99dbb6713485897836c32f388d0bed8d128a6b6ec98997ba7e312b')
                    .then(data => data.json())
                    .then(data => {
                        var datos = 'ip: ' + data.ip + ', Ciudad: ' + data.city + 
                                    ', Pais: ' + data.country_name + ', Region: ' + 
                                    data.region + ', Latitud: ' + data.latitude + 
                                    ', Longitud: ' + data.longitude + 
                                    ', Fecha: ' + data.time_zone.current_time;
                        console.log(datos);
                        
                        var gps = document.getElementById('gps');
                        gps.value = datos;
                })
                .catch(error => {
                    console.log('Error en la peticion');
                });
            
        </script>
    @endif
@endsection
