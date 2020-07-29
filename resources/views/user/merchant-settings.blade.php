<?php

use App\UserPersonProfile;

/** @var UserPersonProfile $personProfile */
?>
@extends('layouts.mvp-layout-internal')

@section('content')

    <div class="container mt-md-n5">
        <div class="row">
            <div class="col-lg-9 col-xl-10 mx-auto px-0 px-md-3">
                <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                    {{--<div id="profile-tabs" class="btn-group" role="group" aria-label="Profile Navigation Tabs">
                        <a href="panel-control.html" class="btn btn-secondary py-md-3 rounded-0"><img src="img/landing/boxes-secondary.svg" class="img-fluid mb-1 mb-md-0 mr-md-2"><span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Panel de control</span></a>
                        <a href="perfil-comprar.html" class="btn btn-secondary py-md-3 rounded-0"><img src="img/landing/reload-secondary.svg" class="img-fluid mb-1 mb-md-0 mr-md-2"><span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Comprar y Vender</span></a>
                        <a href="perfil-billeteras.html" class="btn btn-secondary py-md-3 rounded-0"><img src="img/landing/simpleWallet-secondary.svg" class="img-fluid mb-1 mb-md-0 mr-md-2"><span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Mis Billeteras</span></a>
                        <a href="perfil-configuracion.html" class="btn btn-secondary py-md-3 rounded-0 active"><img src="img/landing/settings-secondary.svg" class="img-fluid mb-1 mb-md-0 mr-md-2"><span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Configuración</span></a>
                    </div>--}}

                    <div class="card-body py-4 py-lg-4">
                        <div class="row justify-content-center mt-4 mb-4">
                            <div class="col-6 col-md-3 col-xl-2 px-1">
                                <a href="javascript:void(0);" id="active-natural-form" class="cardBank mx-2 --active">
                                    <img src="img/landing/person-primary.svg" alt="Person Icon"
                                         class="d-inline-block mb-2">
                                    <h6 class="cardBank__title">Persona</h6>
                                </a>
                            </div>
                            <div class="col-6 col-md-3 col-xl-2 px-1">
                                <a href="javascript:void(0);" id="active-company-form" class="cardBank mx-2">
                                    <img src="img/landing/usd-rounded-primary.svg" alt="Person Icon"
                                         class="d-inline-block mb-2">
                                    <h6 class="cardBank__title">Empresa</h6>
                                </a>
                            </div>
                        </div>
                        {{--<div class="row mb-4">
                            <div class="col-md-8 mx-auto">
                                <nav class="subtabs nav justify-content-center flex-nowrap mt-md-4 mb-5">
                                    <a class="subtabs__item nav-link mx-4 text-center active" href="perfil-configuracion.html"><img src="img/landing/person-primary.svg" class="img-fluid mr-1"><span class="va-middle text-truncate">Perfil</span></a>
                                    <a class="subtabs__item nav-link mx-4 text-center" href="perfil-configuracion-pay.html"><img src="img/landing/payment-primary.svg" class="img-fluid mr-1"><span class="va-middle text-truncate">Métodos de Pago</span></a>
                                </nav>
                            </div>
                        </div>--}}

                        <form class="form-cotrol"
                              id="merchant-natural"
                              action="{{ URL('/settings') }}"
                              enctype="multipart/form-data"
                              method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-3 pl-lg-5">
                                    <div class="row">
                                        <div class="col-6 col-md-12">
                                            <div class="text-center mb-3 mb-md-5">
                                                <div class="setImg--wrapper mx-auto">
                                                    @if ($personProfile->selfie === null)
                                                        <img src="img/landing/avatar-placeholder.png"
                                                             class="object-cover"
                                                             id="__selfie_img">
                                                    @else
                                                        <img src="{{$personProfile->selfie}}"
                                                             class="object-cover"
                                                             id="__selfie_img">
                                                    @endif
                                                </div>
                                                <div class="my-1">Fotografía</div>
                                                <label class="btn btn-light btn-sm rounded-0">
                                                    Cargar foto
                                                    <input type="file"
                                                           class="form-control"
                                                           id="__selfie_input"
                                                           accept="image/*"
                                                           style="display: none;"
                                                           value="{{$personProfile->selfie}}"
                                                           name="UserPersonProfile[selfie]">
                                                </label>
                                                <p class="__selfie_photo_error __photo_error">
                                                    Tu imagen es muy pesada.
                                                </p>
                                                <small class="d-block text-muted mt-2">Tamaño máximo 4mb.</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            <div class="text-center mb-3 mb-md-5">
                                                <div class="setImg--wrapper mx-auto">
                                                    @if ($personProfile->id_confirmation === null)
                                                        <img src="img/landing/id-placeholder.png"
                                                             class="object-cover"
                                                             id="__id_img">
                                                    @else
                                                        <img src="{{$personProfile->id_confirmation}}"
                                                             class="object-cover"
                                                             id="__id_img">
                                                    @endif
                                                </div>
                                                <div class="my-1">Fotografía de ID</div>
                                                <label class="btn btn-light btn-sm rounded-0">
                                                    Cargar foto
                                                    <input type="file"
                                                           class="form-control"
                                                           id="__id_input"
                                                           accept="image/*"
                                                           style="display: none;"
                                                           value="{{$personProfile->id_confirmation}}"
                                                           name="UserPersonProfile[id_confirmation]">
                                                </label>
                                                <p class="__id_photo_error __photo_error">
                                                    Tu imagen es muy pesada.
                                                </p>
                                                <small class="d-block text-muted mt-2">Tamaño máximo 4mb.</small>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-12">
                                            <div class="text-center mb-3 mb-md-5">
                                                @if (!is_null(Auth::user()->google2fa_secret))
                                                <div class="setImg--wrapper mx-auto">
                                                        <img src="{{$QR_image}}"
                                                             class="object-cover">
                                                </div>
                                                @endif
                                                <div class="my-1">2F Authentication</div>
                                                @if (is_null(Auth::user()->google2fa_secret))
                                                <a href="{{URL::to('authentication')}}" class="btn btn-light btn-sm rounded-0">
                                                    Establecer
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 pr-lg-5">
                                    <h5 class="text-primary font-weight-bold mb-1">Datos Personales</h5>
                                    <p class="text-muted font-weight-light font-14 mb-3">Aquí puede ingresar y editar
                                        los datos asociados a su cuenta con American Kriptos Bank</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombres" class="text-primary">Nombre</label>
                                                <input type="text" class="form-control"
                                                       value="{{$personProfile->first_name}}"
                                                       disabled
                                                       name="UserPersonProfile[first_name]">
                                                <input type="hidden" class="form-control" value="{{$personProfile->id}}"
                                                       name="UserPersonProfile[id]">
                                                <input type="hidden" class="form-control" value="{{Auth::user()->id}}"
                                                       name="User[id]">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="apellidos" class="text-primary">Apellidos</label>
                                                <input type="text" class="form-control"
                                                       value="{{$personProfile->last_name}}"
                                                       disabled
                                                       name="UserPersonProfile[last_name]">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="text-primary">Email</label>
                                                <input type="email" class="form-control"
                                                       value="{{$personProfile->email}}"
                                                       name="UserPersonProfile[email]">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone" class="text-primary">Teléfono</label>
                                                @php
                                                    $mobileNumber = explode(' ', $personProfile->mobile);
                                                @endphp
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <select name="UserPersonProfile[pre-mobile]"
                                                                class="custom-select">
                                                            <option {{$mobileNumber[0] === '+58' ? 'selected': ''}}
                                                                    value="+58">+58
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+54' ? 'selected': ''}}
                                                                    value="+54">+54
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+591' ? 'selected': ''}}
                                                                    value="+591">+591
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+55' ? 'selected': ''}}
                                                                    value="+55">+55
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+56' ? 'selected': ''}}
                                                                    value="+56">+56
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+57' ? 'selected': ''}}
                                                                    value="+57">+57
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+51' ? 'selected': ''}}
                                                                    value="+51">+51
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+595' ? 'selected': ''}}
                                                                    value="+595">+595
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+598' ? 'selected': ''}}
                                                                    value="+598">+598
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+1' ? 'selected': ''}}
                                                                    value="+1">+1
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+34' ? 'selected': ''}}
                                                                    value="+34">+34
                                                            </option>
                                                            <option {{$mobileNumber[0] === '+351' ? 'selected': ''}}
                                                                    value="+351">+351
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <input type="text" class="form-control"
                                                           value="{{$mobileNumber[1] ?? ''}}"
                                                           name="UserPersonProfile[main-mobile]">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha-nacimiento" class="text-primary">
                                                    Fecha de nacimiento
                                                </label>
                                                <select name="UserPersonProfile[birth_month]"
                                                        id="fecha-nacimiento" class="custom-select">
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
                                                    while ($i < 31) {
                                                        $i++;
                                                        $days[] = $i;
                                                    }
                                                @endphp
                                                <label for="" class="text-primary">&nbsp;</label>
                                                <select name="UserPersonProfile[birth_day]" class="custom-select">
                                                    @foreach($days as $day)
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
                                                    while ($i < (int)date('Y')) {
                                                        $i++;
                                                        $years[] = $i;
                                                    }
                                                @endphp
                                                <label for="" class="text-primary">&nbsp;</label>
                                                <select name="UserPersonProfile[birth_year]" class="custom-select">
                                                    @foreach($years as $year)
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
                                                <label for="merchant-select-country" class="text-primary">País</label>
                                                <select name="UserPersonProfile[country]"
                                                        id="merchant-select-country"
                                                        class="custom-select flag-selector flag-selector--full"
                                                        {{ $personProfile->country  !== null ? 'disabled' : '' }}>
                                                    <optgroup label="Latino América">
                                                        <option {{ $personProfile->country === 'VE' ? 'selected':'' }}
                                                                value="VE" data-flag="img/landing/flags/ve.svg">
                                                            Venezuela
                                                        </option>
                                                        <option {{ $personProfile->country === 'AR' ? 'selected':'' }}
                                                                value="AR" data-flag="img/landing/flags/ar.svg">
                                                            Argentina
                                                        </option>
                                                        <option {{ $personProfile->country === 'BO' ? 'selected':'' }}
                                                                value="BO" data-flag="img/landing/flags/bo.svg">
                                                            Bolivia
                                                        </option>
                                                        <option {{ $personProfile->country === 'BR' ? 'selected':'' }}
                                                                value="BR" data-flag="img/landing/flags/br.svg">
                                                            Brasil
                                                        </option>
                                                        <option {{ $personProfile->country === 'CL' ? 'selected':'' }}
                                                                value="CL" data-flag="img/landing/flags/cl.svg">
                                                            Chile
                                                        </option>
                                                        <option {{ $personProfile->country === 'CO' ? 'selected':'' }}
                                                                value="CO" data-flag="img/landing/flags/co.svg">
                                                            Colombia
                                                        </option>
                                                        <option {{ $personProfile->country === 'PE' ? 'selected':'' }}
                                                                value="PE" data-flag="img/landing/flags/pe.svg">
                                                            Perú
                                                        </option>
                                                        <option {{ $personProfile->country === 'PY' ? 'selected':'' }}
                                                                value="PY" data-flag="img/landing/flags/py.svg">
                                                            Paraguay
                                                        </option>
                                                        <option {{ $personProfile->country === 'UY' ? 'selected':'' }}
                                                                value="UY" data-flag="img/landing/flags/uy.svg">
                                                            Uruguay
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Otros">
                                                        <option {{ $personProfile->country === 'US' ? 'selected':'' }}
                                                                value="US" data-flag="img/landing/flags/us.svg">
                                                            United States
                                                        </option>
                                                        <option {{ $personProfile->country === 'CA' ? 'selected':'' }}
                                                                value="CA" data-flag="img/landing/flags/ca.svg">
                                                            Canada
                                                        </option>
                                                        <option {{ $personProfile->country === 'ES' ? 'selected':'' }}
                                                                value="ES" data-flag="img/landing/flags/es.svg">
                                                            España
                                                        </option>
                                                        <option {{ $personProfile->country === 'PT' ? 'selected':'' }}
                                                                value="PT" data-flag="img/landing/flags/pt.svg">
                                                            Portugal
                                                        </option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="direccion-postal1" class="text-primary">Dirección</label>
                                                <input id="direccion-postal1"
                                                       name="UserPersonProfile[street]"
                                                       value="{{$personProfile->street}}"
                                                       type="text"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="codigo-postal" class="text-primary">Código Postal</label>
                                                <input id="codigo-postal"
                                                       type="text"
                                                       class="form-control"
                                                       value="{{$personProfile->zip_code}}"
                                                       name="UserPersonProfile[zip_code]">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ciudad" class="text-primary">Ciudad</label>
                                                <input id="ciudad" type="text" class="form-control"
                                                       value="{{$personProfile->city}}"
                                                       name="UserPersonProfile[city]"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group __dynamic_states"
                                                 data-user-state="{{$personProfile->state}}">
                                                <label for="merchant-select-state" class="text-primary">Estado/Departamento</label>
                                                <select id="merchant-select-state" class="custom-select"
                                                        name="UserPersonProfile[state]">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    {{--                                    <div class="row">--}}
                                    {{--                                        <div class="col-md-4">--}}
                                    {{--                                            <div class="form-group">--}}
                                    {{--                                                <label for="merchant-username" class="text-primary">Usuario</label>--}}
                                    {{--                                                <input id="merchant-username"--}}
                                    {{--                                                       type="text"--}}
                                    {{--                                                       class="form-control"--}}
                                    {{--                                                       name="User[email]"--}}
                                    {{--                                                       value="{{Auth::user()->email}}"--}}
                                    {{--                                                       disabled>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col-md-4">--}}
                                    {{--                                            <div class="form-group">--}}
                                    {{--                                                <label for="merchant-password" class="text-primary">Nueva--}}
                                    {{--                                                    Contraseña</label>--}}
                                    {{--                                                <input id="merchant-password"--}}
                                    {{--                                                       type="password"--}}
                                    {{--                                                       class="form-control"--}}
                                    {{--                                                       name="User[password]"--}}
                                    {{--                                                       value=""--}}
                                    {{--                                                       disabled>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col-md-4">--}}
                                    {{--                                            <label for="">&nbsp;</label>--}}
                                    {{--                                            <button type="button"--}}
                                    {{--                                                    id="merchant-settings-change-pass"--}}
                                    {{--                                                    class="btn btn-light btn-block rounded-0">--}}
                                    {{--                                                Cambiar contraseña--}}
                                    {{--                                            </button>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button class="btn btn-secondary btn-pill px-4">Guardar cambios</button>
                            </div>
                        </form>
                        <form class="form-cotrol"
                              id="merchant-company"
                              style="display:none;"
                              action="{{ URL('/settings') }}"
                              enctype="multipart/form-data"
                              method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-3 pl-lg-5">
                                    <div class="row">
                                      <!--  <div class="col-6 col-md-12">
                                            <div class="text-center mb-3 mb-md-5">
                                                <div class="setImg--wrapper mx-auto">
                                                    @if ($companyProfile->logo === null)
                                                        <img src="img/landing/avatar-placeholder.png"
                                                             class="object-cover"
                                                             id="__logo_img">
                                                    @else
                                                        <img src="{{$companyProfile->logo}}"
                                                             class="object-cover"
                                                             id="__logo_img">
                                                    @endif
                                                </div>
                                                <div class="my-1">Logotipo</div>
                                                <label class="btn btn-light btn-sm rounded-0">
                                                    Cargar logo
                                                    <input type="file"
                                                           class="form-control"
                                                           id="__logo_input"
                                                           accept="image/*"
                                                           style="display: none;"
                                                           value="{{$companyProfile->logo}}"
                                                           name="UserCompanyProfile[logo]">
                                                </label>
                                                <p class="__logo_photo_error __photo_error">
                                                    Tu imagen es muy pesada.
                                                </p>
                                                <small class="d-block text-muted mt-2">Tamaño máximo 4mb.</small>
                                            </div>
                                        </div> -->
                                        <div class="col-6 col-md-12">
                                            <div class="text-center mb-3 mb-md-5">
                                                <div class="setImg--wrapper mx-auto">
                                                    @if ($companyProfile->id_confirmation === null)
                                                        <img src="img/landing/tributary-placeholder.png"
                                                             class="object-cover"
                                                             id="__id_comp_img">
                                                    @else
                                                        <img src="{{$companyProfile->id_confirmation}}"
                                                             class="object-cover"
                                                             id="__id_comp_img">
                                                    @endif
                                                </div>
                                                <div class="my-1">Fotografía de ID Tributaria</div>
                                                <label class="btn btn-light btn-sm rounded-0">
                                                    Cargar foto
                                                    <input type="file"
                                                           class="form-control"
                                                           id="__id_comp_input"
                                                           accept="image/*"
                                                           style="display: none;"
                                                           value="{{$companyProfile->id_confirmation}}"
                                                           name="UserCompanyProfile[id_confirmation]">
                                                </label>
                                                <p class="__id_comp_photo_error __photo_error">
                                                    Tu imagen es muy pesada.
                                                </p>
                                                <small class="d-block text-muted mt-2">Tamaño máximo 4mb.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 pr-lg-5">
                                    <h5 class="text-primary font-weight-bold mb-1">Datos Empresariales</h5>
                                    <p class="text-muted font-weight-light font-14 mb-3">Aquí puede ingresar y editar
                                        los datos asociados a su cuenta con American Kriptos Bank</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company-nombre" class="text-primary">Nombre de la
                                                    empresa</label>
                                                <input id="company-nombre"
                                                       type="text"
                                                       class="form-control"
                                                       name="UserCompanyProfile[name]"
                                                       value="{{$companyProfile->name}}"
                                                        {{ $companyProfile->name  !== null ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="company-id-number" class="text-primary">
                                                    Número de Identificación Tributaria
                                                    <small>(RUT, RIF, EIN - Tax ID Number, etc.)</small>
                                                </label>
                                                <input id="company-id-number"
                                                       type="text"
                                                       class="form-control"
                                                       name="UserCompanyProfile[tax_number]"
                                                       value="{{$companyProfile->tax_number}}"
                                                        {{ $companyProfile->tax_number  !== null ? 'disabled' : '' }}>
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
                                                        <select id="company-mobile"
                                                                name="UserCompanyProfile[pre-mobile]"
                                                                class="custom-select">
                                                            <option {{$companyMobileNumber[0] === '+58' ? 'selected': ''}}
                                                                    value="+58">+58
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+54' ? 'selected': ''}}
                                                                    value="+54">+54
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+591' ? 'selected': ''}}
                                                                    value="+591">+591
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+55' ? 'selected': ''}}
                                                                    value="+55">+55
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+56' ? 'selected': ''}}
                                                                    value="+56">+56
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+57' ? 'selected': ''}}
                                                                    value="+57">+57
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+51' ? 'selected': ''}}
                                                                    value="+51">+51
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+595' ? 'selected': ''}}
                                                                    value="+595">+595
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+598' ? 'selected': ''}}
                                                                    value="+598">+598
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+1' ? 'selected': ''}}
                                                                    value="+1">+1
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+34' ? 'selected': ''}}
                                                                    value="+34">+34
                                                            </option>
                                                            <option {{$companyMobileNumber[0] === '+351' ? 'selected': ''}}
                                                                    value="+351">+351
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <input type="text" class="form-control"
                                                           value="{{$companyMobileNumber[1] ?? ''}}"
                                                           name="UserCompanyProfile[main-mobile]">
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
                                                        <select id="company-office_phone"
                                                                name="UserCompanyProfile[pre-office_phone]"
                                                                class="custom-select">
                                                            <option {{$companyOfficeNumber[0] === '+58' ? 'selected': ''}}
                                                                    value="+58">+58
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+54' ? 'selected': ''}}
                                                                    value="+54">+54
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+591' ? 'selected': ''}}
                                                                    value="+591">+591
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+55' ? 'selected': ''}}
                                                                    value="+55">+55
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+56' ? 'selected': ''}}
                                                                    value="+56">+56
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+57' ? 'selected': ''}}
                                                                    value="+57">+57
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+51' ? 'selected': ''}}
                                                                    value="+51">+51
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+595' ? 'selected': ''}}
                                                                    value="+595">+595
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+598' ? 'selected': ''}}
                                                                    value="+598">+598
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+1' ? 'selected': ''}}
                                                                    value="+1">+1
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+34' ? 'selected': ''}}
                                                                    value="+34">+34
                                                            </option>
                                                            <option {{$companyOfficeNumber[0] === '+351' ? 'selected': ''}}
                                                                    value="+351">+351
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <input type="text" class="form-control"
                                                           value="{{$companyOfficeNumber[1] ?? ''}}"
                                                           name="UserCompanyProfile[main-office_phone]">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company-website" class="text-primary">Website</label>
                                                <input id="company-website" type="text"
                                                       class="form-control"
                                                       name="UserCompanyProfile[website]"
                                                       value="{{$companyProfile->website}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company-email" class="text-primary">Email
                                                    corporativo</label>
                                                <input id="company-email" type="email" class="form-control"
                                                       name="UserCompanyProfile[email]"
                                                       value="{{$companyProfile->email}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-select-country" class="text-primary">País</label>
                                                <select name="UserCompanyProfile[country]"
                                                        id="company-select-country"
                                                        class="custom-select flag-selector flag-selector--full"
                                                        {{ $companyProfile->country  !== null ? 'disabled' : '' }}>
                                                    <optgroup label="Latino América">
                                                        <option {{ $companyProfile->country === 'VE' ? 'selected':'' }}
                                                                value="VE" data-flag="img/landing/flags/ve.svg">
                                                            Venezuela
                                                        </option>
                                                        <option {{ $companyProfile->country === 'AR' ? 'selected':'' }}
                                                                value="AR" data-flag="img/landing/flags/ar.svg">
                                                            Argentina
                                                        </option>
                                                        <option {{ $companyProfile->country === 'BO' ? 'selected':'' }}
                                                                value="BO" data-flag="img/landing/flags/bo.svg">
                                                            Bolivia
                                                        </option>
                                                        <option {{ $companyProfile->country === 'BR' ? 'selected':'' }}
                                                                value="BR" data-flag="img/landing/flags/br.svg">
                                                            Brasil
                                                        </option>
                                                        <option {{ $companyProfile->country === 'CL' ? 'selected':'' }}
                                                                value="CL" data-flag="img/landing/flags/cl.svg">
                                                            Chile
                                                        </option>
                                                        <option {{ $companyProfile->country === 'CO' ? 'selected':'' }}
                                                                value="CO" data-flag="img/landing/flags/co.svg">
                                                            Colombia
                                                        </option>
                                                        <option {{ $companyProfile->country === 'PE' ? 'selected':'' }}
                                                                value="PE" data-flag="img/landing/flags/pe.svg">
                                                            Perú
                                                        </option>
                                                        <option {{ $companyProfile->country === 'PY' ? 'selected':'' }}
                                                                value="PY" data-flag="img/landing/flags/py.svg">
                                                            Paraguay
                                                        </option>
                                                        <option {{ $companyProfile->country === 'UY' ? 'selected':'' }}
                                                                value="UY" data-flag="img/landing/flags/uy.svg">
                                                            Uruguay
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Otros">
                                                        <option {{ $companyProfile->country === 'US' ? 'selected':'' }}
                                                                value="US" data-flag="img/landing/flags/us.svg">
                                                            United States
                                                        </option>
                                                        <option {{ $companyProfile->country === 'CA' ? 'selected':'' }}
                                                                value="CA" data-flag="img/landing/flags/ca.svg">
                                                            Canada
                                                        </option>
                                                        <option {{ $companyProfile->country === 'ES' ? 'selected':'' }}
                                                                value="ES" data-flag="img/landing/flags/es.svg">
                                                            España
                                                        </option>
                                                        <option {{ $companyProfile->country === 'PT' ? 'selected':'' }}
                                                                value="PT" data-flag="img/landing/flags/pt.svg">
                                                            Portugal
                                                        </option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="company-direccion" class="text-primary">Dirección</label>
                                                <input id="company-direccion"
                                                       name="UserCompanyProfile[street]"
                                                       value="{{$companyProfile->street}}"
                                                       type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-codigo-postal" class="text-primary">Código
                                                    Postal</label>
                                                <input id="company-codigo-postal"
                                                       type="text"
                                                       class="form-control"
                                                       value="{{$companyProfile->zip_code}}"
                                                       name="UserCompanyProfile[zip_code]">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="company-ciudad" class="text-primary">Ciudad</label>
                                                <input id="company-ciudad" type="text" class="form-control"
                                                       value="{{$companyProfile->city}}"
                                                       name="UserCompanyProfile[city]"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group __company_dynamic_states"
                                                 data-user-state="{{$companyProfile->state}}">
                                                <label for="company-select-state" class="text-primary">Estado/Departamento</label>
                                                <select id="company-select-state" class="custom-select"
                                                        name="UserCompanyProfile[state]">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <stockholders
                                            @if ($companyProfile->shareholders !== null)
                                            v-bind:stockholders-list="{{json_encode($companyProfile->shareholders)}}"
                                            @else
                                            v-bind:stockholders-list="[]"
                                            @endif
                                    >
                                    </stockholders>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-secondary btn-pill px-4">Guardar cambios</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
