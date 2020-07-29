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
                    <a class="nav-link active" href="{{URL::to('/business-info')}}">
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
                    <a class="nav-link"
                       href="{{URL::to('/change-password')}}">
                        <i class="fa fa-key" aria-hidden="true"></i> Contraseña
                    </a>
                </li>
            </ul>
            <div class="card shadow-none rounded-lg mb-4">
                <div class="card-header pt-4 pb-1">
                    <ul class="nav justify-content-center flex-nowrap mb-4 __rounded_pills">
                        <li class="nav-item">
                            <a id="__btn_company_data_tab"
                               class="btn btn-outline-primary btn-pill px-4 mx-1 mx-md-2 font-mobile-14 lh-125 active"
                               href="javascript:void(0);">
                                Datos empresariales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="__btn_company_id_data_tab"
                               class="btn btn-outline-primary btn-pill px-4 mx-1 mx-md-2 font-mobile-14 lh-125"
                               href="javascript:void(0);">
                                Documentos legales
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-md-12 px-lg-5">
                        @if($personProfile->approval_status === 0)
                            <div class="alert alert-danger mb-4">
                                <div class="media">
                                    <img src="img/landing/danger-icon.svg" class="alert-icon mr-3">
                                    <div class="media-body">
                                        <h5 class="alert-heading">¡Atención!</h5>
                                        Para utilizar nuestros servicios primero debe completar su perfil de
                                        usuario. Por favor llene este formulario.
                                    </div>
                                </div>
                            </div>
                        @elseif ($personProfile->approval_status === 1)
                            <div class="alert alert-warning mb-4">
                                <div class="media">
                                    <img src="img/landing/warning-icon.svg" class="alert-icon mr-3">
                                    <div class="media-body">
                                        <h5 class="alert-heading">¡Atención!</h5>
                                        Su solicitud de aprobación de
                                        perfil está siendo revisada por nuestro departamento de
                                        cumplimiento de políticas AML y KYC.
                                    </div>
                                </div>
                            </div>
                        @elseif ($personProfile->approval_status === 3)
                            <div class="alert alert-danger mb-4">
                                <div class="media">
                                    <img src="img/landing/danger-icon.svg" class="alert-icon mr-3">
                                    <div class="media-body">
                                        <h5 class="alert-heading">¡Atención!</h5>
                                        Su perfil ha sido rechazado, por favor revise y actualice sus datos
                                        o pongase en contacto con soporte.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <form id="__company_data_tab"
                      action="{{URL::to('/business-info')}}"
                      enctype="multipart/form-data"
                      method="post">
                    {{ csrf_field() }}
                    @if($personProfile->selfie === null || $personProfile->street === null)
                        <div class="alert alert-danger mb-4">
                            <div class="media">
                                <img src="img/landing/danger-icon.svg" class="alert-icon mr-3">
                                <div class="media-body">
                                    <h5 class="alert-heading">¡Atención!</h5>
                                    Para registrar un perfil de empresa primero debe haber llenado todos
                                    los campos en <a href="" class="alert-link">Informacion General</a>
                                    e <a href="" class="alert-link">Información de Ubicación</a>.
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="text-primary">Nombre de la empresa</label>
                                            <input id="company-nombre"
                                                   type="text"
                                                   class="form-control"
                                                   name="UserCompanyProfile[name]"
                                                   value="{{$companyProfile->name}}"
                                                   {{ $companyProfile->name !== null ? 'disabled' : '' }}
                                                   required
                                                   placeholder="Escribe...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="text-primary">Número de ID tributario</label>
                                            <input id="company-id-number"
                                                   type="text"
                                                   class="form-control"
                                                   name="UserCompanyProfile[tax_number]"
                                                   value="{{$companyProfile->tax_number}}"
                                                   {{ $companyProfile->tax_number !== null ? 'disabled' : '' }}
                                                   required
                                                   placeholder="Escribe...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="text-primary">Sitio web</label>
                                            <input d="company-website"
                                                   type="url"
                                                   class="form-control"
                                                   name="UserCompanyProfile[website]"
                                                   value="{{$companyProfile->website}}"
                                                   required
                                                   {{ $companyProfile->website !== null ? 'disabled' : '' }}
                                                   placeholder="Escribe...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="text-primary">Email corporativo</label>
                                            <input id="company-email" type="email" class="form-control"
                                                   name="UserCompanyProfile[email]"
                                                   value="{{$companyProfile->email}}"
                                                   {{ $companyProfile->email !== null ? 'disabled' : '' }}
                                                   required
                                                   placeholder="Escribe...">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="text-primary">Teléfono Célular</label>
                                            <div class="input-group">
                                                @php
                                                    $companyMobileNumber = explode(' ', $companyProfile->mobile);
                                                @endphp
                                                <input name="UserCompanyProfile[pre-mobile]"
                                                       id="__c_mobile_prefix"
                                                       type="hidden"
                                                       value="{{$companyMobileNumber[0] !== '' ? $companyMobileNumber[0] : '+1'}}"
                                                       required
                                                    {{ $companyProfile->mobile !== null ? 'readonly disabled' : '' }}>
                                                <input type="text"
                                                       class="form-control"
                                                       id="__c_mobile_main"
                                                       value="{{$companyMobileNumber[1] ?? ''}}"
                                                       {{ $companyProfile->mobile !== null ? 'readonly disabled' : '' }}
                                                       name="UserCompanyProfile[main-mobile]"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="text-primary">Teléfono Fijo</label>
                                            <div class="input-group">
                                                @php
                                                    $companyOfficeNumber = explode(' ', $companyProfile->office_phone);
                                                @endphp
                                                <input name="UserCompanyProfile[pre-office-phone]"
                                                       id="__c_office_prefix"
                                                       type="hidden"
                                                       value="{{$companyOfficeNumber[0] !== '' ? $companyOfficeNumber[0] : '+1'}}"
                                                       {{ $companyProfile->office_phone !== null ? 'readonly disabled' : '' }}
                                                       required>
                                                <input name="UserCompanyProfile[main-office-phone]"
                                                       type="text"
                                                       class="form-control"
                                                       id="__c_office_main"
                                                       value="{{$companyOfficeNumber[1] ?? ''}}"
                                                       {{ $companyProfile->office_phone !== null ? 'readonly disabled' : '' }}
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="pais" class="text-primary">País</label>
                                            <select name="UserCompanyProfile[country]"
                                                    id="person-select-country"
                                                    required
                                                {{ $companyProfile->country !== null ? 'disabled' : '' }}>
                                                <option value="" selected></option>
                                                <option
                                                    {{ $companyProfile->country === 'AD' ? 'selected':'' }}
                                                    value="AD">Andorra
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AE' ? 'selected':'' }}
                                                    value="AE">Emiratos Árabes Unidos
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AF' ? 'selected':'' }}
                                                    value="AF">Afganistán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AG' ? 'selected':'' }}
                                                    value="AG">Antigua y Barbuda
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AI' ? 'selected':'' }}
                                                    value="AI">Anguila
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AL' ? 'selected':'' }}
                                                    value="AL">Albania
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AM' ? 'selected':'' }}
                                                    value="AM">Armenia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AO' ? 'selected':'' }}
                                                    value="AO">Angola
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AQ' ? 'selected':'' }}
                                                    value="AQ">Antártida
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AR' ? 'selected':'' }}
                                                    value="AR">Argentina
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AS' ? 'selected':'' }}
                                                    value="AS">Samoa Americana
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AT' ? 'selected':'' }}
                                                    value="AT">Austria
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AU' ? 'selected':'' }}
                                                    value="AU">Australia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AW' ? 'selected':'' }}
                                                    value="AW">Aruba
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AX' ? 'selected':'' }}
                                                    value="AX">Islas Áland
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'AZ' ? 'selected':'' }}
                                                    value="AZ">Azerbaiyán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BA' ? 'selected':'' }}
                                                    value="BA">Bosnia y Herzegovina
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BB' ? 'selected':'' }}
                                                    value="BB">Barbados
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BD' ? 'selected':'' }}
                                                    value="BD">Bangladesh
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BE' ? 'selected':'' }}
                                                    value="BE">Bélgica
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BF' ? 'selected':'' }}
                                                    value="BF">Burkina Faso
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BG' ? 'selected':'' }}
                                                    value="BG">Bulgaria
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BH' ? 'selected':'' }}
                                                    value="BH">Bahréin
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BI' ? 'selected':'' }}
                                                    value="BI">Burundi
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BJ' ? 'selected':'' }}
                                                    value="BJ">Benin
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BL' ? 'selected':'' }}
                                                    value="BL">San Bartolomé
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BM' ? 'selected':'' }}
                                                    value="BM">Bermudas
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BN' ? 'selected':'' }}
                                                    value="BN">Brunéi
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BO' ? 'selected':'' }}
                                                    value="BO">Bolivia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BR' ? 'selected':'' }}
                                                    value="BR">Brasil
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BS' ? 'selected':'' }}
                                                    value="BS">Bahamas
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BT' ? 'selected':'' }}
                                                    value="BT">Bhután
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BV' ? 'selected':'' }}
                                                    value="BV">Isla Bouvet
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BW' ? 'selected':'' }}
                                                    value="BW">Botsuana
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BY' ? 'selected':'' }}
                                                    value="BY">Belarús
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'BZ' ? 'selected':'' }}
                                                    value="BZ">Belice
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CA' ? 'selected':'' }}
                                                    value="CA">Canadá
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CC' ? 'selected':'' }}
                                                    value="CC">Islas Cocos
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CF' ? 'selected':'' }}
                                                    value="CF">República Centro-Africana
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CG' ? 'selected':'' }}
                                                    value="CG">Congo
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CH' ? 'selected':'' }}
                                                    value="CH">Suiza
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CI' ? 'selected':'' }}
                                                    value="CI">Costa de Marfil
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CK' ? 'selected':'' }}
                                                    value="CK">Islas Cook
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CL' ? 'selected':'' }}
                                                    value="CL">Chile
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CM' ? 'selected':'' }}
                                                    value="CM">Camerún
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CN' ? 'selected':'' }}
                                                    value="CN">China
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CO' ? 'selected':'' }}
                                                    value="CO">Colombia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CR' ? 'selected':'' }}
                                                    value="CR">Costa Rica
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CU' ? 'selected':'' }}
                                                    value="CU">Cuba
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CV' ? 'selected':'' }}
                                                    value="CV">Cabo Verde
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CX' ? 'selected':'' }}
                                                    value="CX">Islas Christmas
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CY' ? 'selected':'' }}
                                                    value="CY">Chipre
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'CZ' ? 'selected':'' }}
                                                    value="CZ">República Checa
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'DE' ? 'selected':'' }}
                                                    value="DE">Alemania
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'DJ' ? 'selected':'' }}
                                                    value="DJ">Yibuti
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'DK' ? 'selected':'' }}
                                                    value="DK">Dinamarca
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'DM' ? 'selected':'' }}
                                                    value="DM">Domínica
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'DO' ? 'selected':'' }}
                                                    value="DO">República Dominicana
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'DZ' ? 'selected':'' }}
                                                    value="DZ">Argel
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'EC' ? 'selected':'' }}
                                                    value="EC">Ecuador
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'EE' ? 'selected':'' }}
                                                    value="EE">Estonia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'EG' ? 'selected':'' }}
                                                    value="EG">Egipto
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'EH' ? 'selected':'' }}
                                                    value="EH">Sahara Occidental
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'ER' ? 'selected':'' }}
                                                    value="ER">Eritrea
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'ES' ? 'selected':'' }}
                                                    value="ES">España
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'ET' ? 'selected':'' }}
                                                    value="ET">Etiopía
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'FI' ? 'selected':'' }}
                                                    value="FI">Finlandia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'FJ' ? 'selected':'' }}
                                                    value="FJ">Fiji
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'FK' ? 'selected':'' }}
                                                    value="FK">Islas Malvinas
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'FM' ? 'selected':'' }}
                                                    value="FM">Micronesia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'FO' ? 'selected':'' }}
                                                    value="FO">Islas Faroe
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'FR' ? 'selected':'' }}
                                                    value="FR">Francia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GA' ? 'selected':'' }}
                                                    value="GA">Gabón
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GB' ? 'selected':'' }}
                                                    value="GB">Reino Unido
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GD' ? 'selected':'' }}
                                                    value="GD">Granada
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GE' ? 'selected':'' }}
                                                    value="GE">Georgia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GF' ? 'selected':'' }}
                                                    value="GF">Guayana Francesa
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GG' ? 'selected':'' }}
                                                    value="GG">Guernsey
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GH' ? 'selected':'' }}
                                                    value="GH">Ghana
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GI' ? 'selected':'' }}
                                                    value="GI">Gibraltar
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GL' ? 'selected':'' }}
                                                    value="GL">Groenlandia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GM' ? 'selected':'' }}
                                                    value="GM">Gambia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GN' ? 'selected':'' }}
                                                    value="GN">Guinea
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GP' ? 'selected':'' }}
                                                    value="GP">Guadalupe
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GQ' ? 'selected':'' }}
                                                    value="GQ">Guinea Ecuatorial
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GR' ? 'selected':'' }}
                                                    value="GR">Grecia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GS' ? 'selected':'' }}
                                                    value="GS">Georgia del Sur e Islas Sandwich del Sur
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GT' ? 'selected':'' }}
                                                    value="GT">Guatemala
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GU' ? 'selected':'' }}
                                                    value="GU">Guam
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GW' ? 'selected':'' }}
                                                    value="GW">Guinea-Bissau
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'GY' ? 'selected':'' }}
                                                    value="GY">Guayana
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'HK' ? 'selected':'' }}
                                                    value="HK">Hong Kong
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'HM' ? 'selected':'' }}
                                                    value="HM">Islas Heard y McDonald
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'HN' ? 'selected':'' }}
                                                    value="HN">Honduras
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'HR' ? 'selected':'' }}
                                                    value="HR">Croacia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'HT' ? 'selected':'' }}
                                                    value="HT">Haití
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'HU' ? 'selected':'' }}
                                                    value="HU">Hungría
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'ID' ? 'selected':'' }}
                                                    value="ID">Indonesia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IE' ? 'selected':'' }}
                                                    value="IE">Irlanda
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IL' ? 'selected':'' }}
                                                    value="IL">Israel
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IM' ? 'selected':'' }}
                                                    value="IM">Isla de Man
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IN' ? 'selected':'' }}
                                                    value="IN">India
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IO' ? 'selected':'' }}
                                                    value="IO">Territorio Británico del Océano Índico
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IQ' ? 'selected':'' }}
                                                    value="IQ">Irak
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IR' ? 'selected':'' }}
                                                    value="IR">Irán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IS' ? 'selected':'' }}
                                                    value="IS">Islandia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'IT' ? 'selected':'' }}
                                                    value="IT">Italia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'JE' ? 'selected':'' }}
                                                    value="JE">Jersey
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'JM' ? 'selected':'' }}
                                                    value="JM">Jamaica
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'JO' ? 'selected':'' }}
                                                    value="JO">Jordania
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'JP' ? 'selected':'' }}
                                                    value="JP">Japón
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KE' ? 'selected':'' }}
                                                    value="KE">Kenia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KG' ? 'selected':'' }}
                                                    value="KG">Kirguistán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KH' ? 'selected':'' }}
                                                    value="KH">Camboya
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KI' ? 'selected':'' }}
                                                    value="KI">Kiribati
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KM' ? 'selected':'' }}
                                                    value="KM">Comoros
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KN' ? 'selected':'' }}
                                                    value="KN">San Cristóbal y Nieves
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KP' ? 'selected':'' }}
                                                    value="KP">Corea del Norte
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KR' ? 'selected':'' }}
                                                    value="KR">Corea del Sur
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KW' ? 'selected':'' }}
                                                    value="KW">Kuwait
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KY' ? 'selected':'' }}
                                                    value="KY">Islas Caimán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'KZ' ? 'selected':'' }}
                                                    value="KZ">Kazajstán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LA' ? 'selected':'' }}
                                                    value="LA">Laos
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LB' ? 'selected':'' }}
                                                    value="LB">Líbano
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LC' ? 'selected':'' }}
                                                    value="LC">Santa Lucía
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LI' ? 'selected':'' }}
                                                    value="LI">Liechtenstein
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LK' ? 'selected':'' }}
                                                    value="LK">Sri Lanka
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LR' ? 'selected':'' }}
                                                    value="LR">Liberia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LS' ? 'selected':'' }}
                                                    value="LS">Lesotho
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LT' ? 'selected':'' }}
                                                    value="LT">Lituania
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LU' ? 'selected':'' }}
                                                    value="LU">Luxemburgo
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LV' ? 'selected':'' }}
                                                    value="LV">Letonia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'LY' ? 'selected':'' }}
                                                    value="LY">Libia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MA' ? 'selected':'' }}
                                                    value="MA">Marruecos
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MC' ? 'selected':'' }}
                                                    value="MC">Mónaco
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MD' ? 'selected':'' }}
                                                    value="MD">Moldova
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'ME' ? 'selected':'' }}
                                                    value="ME">Montenegro
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MG' ? 'selected':'' }}
                                                    value="MG">Madagascar
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MH' ? 'selected':'' }}
                                                    value="MH">Islas Marshall
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MK' ? 'selected':'' }}
                                                    value="MK">Macedonia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'ML' ? 'selected':'' }}
                                                    value="ML">Mali
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MM' ? 'selected':'' }}
                                                    value="MM">Myanmar
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MN' ? 'selected':'' }}
                                                    value="MN">Mongolia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MO' ? 'selected':'' }}
                                                    value="MO">Macao
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MQ' ? 'selected':'' }}
                                                    value="MQ">Martinica
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MR' ? 'selected':'' }}
                                                    value="MR">Mauritania
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MS' ? 'selected':'' }}
                                                    value="MS">Montserrat
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MT' ? 'selected':'' }}
                                                    value="MT">Malta
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MU' ? 'selected':'' }}
                                                    value="MU">Mauricio
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MV' ? 'selected':'' }}
                                                    value="MV">Maldivas
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MW' ? 'selected':'' }}
                                                    value="MW">Malawi
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MX' ? 'selected':'' }}
                                                    value="MX">México
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MY' ? 'selected':'' }}
                                                    value="MY">Malasia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'MZ' ? 'selected':'' }}
                                                    value="MZ">Mozambique
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NA' ? 'selected':'' }}
                                                    value="NA">Namibia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NC' ? 'selected':'' }}
                                                    value="NC">Nueva Caledonia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NE' ? 'selected':'' }}
                                                    value="NE">Níger
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NF' ? 'selected':'' }}
                                                    value="NF">Islas Norkfolk
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NG' ? 'selected':'' }}
                                                    value="NG">Nigeria
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NI' ? 'selected':'' }}
                                                    value="NI">Nicaragua
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NL' ? 'selected':'' }}
                                                    value="NL">Países Bajos
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NO' ? 'selected':'' }}
                                                    value="NO">Noruega
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NP' ? 'selected':'' }}
                                                    value="NP">Nepal
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NR' ? 'selected':'' }}
                                                    value="NR">Nauru
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NU' ? 'selected':'' }}
                                                    value="NU">Niue
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'NZ' ? 'selected':'' }}
                                                    value="NZ">Nueva Zelanda
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'OM' ? 'selected':'' }}
                                                    value="OM">Omán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PA' ? 'selected':'' }}
                                                    value="PA">Panamá
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PE' ? 'selected':'' }}
                                                    value="PE">Perú
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PF' ? 'selected':'' }}
                                                    value="PF">Polinesia Francesa
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PG' ? 'selected':'' }}
                                                    value="PG">Papúa Nueva Guinea
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PH' ? 'selected':'' }}
                                                    value="PH">Filipinas
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PK' ? 'selected':'' }}
                                                    value="PK">Pakistán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PL' ? 'selected':'' }}
                                                    value="PL">Polonia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PM' ? 'selected':'' }}
                                                    value="PM">San Pedro y Miquelón
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PN' ? 'selected':'' }}
                                                    value="PN">Islas Pitcairn
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PR' ? 'selected':'' }}
                                                    value="PR">Puerto Rico
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PS' ? 'selected':'' }}
                                                    value="PS">Palestina
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PT' ? 'selected':'' }}
                                                    value="PT">Portugal
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PW' ? 'selected':'' }}
                                                    value="PW">Islas Palaos
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'PY' ? 'selected':'' }}
                                                    value="PY">Paraguay
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'QA' ? 'selected':'' }}
                                                    value="QA">Qatar
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'RE' ? 'selected':'' }}
                                                    value="RE">Reunión
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'RO' ? 'selected':'' }}
                                                    value="RO">Rumanía
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'RS' ? 'selected':'' }}
                                                    value="RS">Serbia y Montenegro
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'RU' ? 'selected':'' }}
                                                    value="RU">Rusia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'RW' ? 'selected':'' }}
                                                    value="RW">Ruanda
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SA' ? 'selected':'' }}
                                                    value="SA">Arabia Saudita
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SB' ? 'selected':'' }}
                                                    value="SB">Islas Solomón
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SC' ? 'selected':'' }}
                                                    value="SC">Seychelles
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SD' ? 'selected':'' }}
                                                    value="SD">Sudán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SE' ? 'selected':'' }}
                                                    value="SE">Suecia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SG' ? 'selected':'' }}
                                                    value="SG">Singapur
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SH' ? 'selected':'' }}
                                                    value="SH">Santa Elena
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SI' ? 'selected':'' }}
                                                    value="SI">Eslovenia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SJ' ? 'selected':'' }}
                                                    value="SJ">Islas Svalbard y Jan Mayen
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SK' ? 'selected':'' }}
                                                    value="SK">Eslovaquia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SL' ? 'selected':'' }}
                                                    value="SL">Sierra Leona
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SM' ? 'selected':'' }}
                                                    value="SM">San Marino
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SN' ? 'selected':'' }}
                                                    value="SN">Senegal
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SO' ? 'selected':'' }}
                                                    value="SO">Somalia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SR' ? 'selected':'' }}
                                                    value="SR">Surinam
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'ST' ? 'selected':'' }}
                                                    value="ST">Santo Tomé y Príncipe
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SV' ? 'selected':'' }}
                                                    value="SV">El Salvador
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SY' ? 'selected':'' }}
                                                    value="SY">Siria
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'SZ' ? 'selected':'' }}
                                                    value="SZ">Suazilandia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TC' ? 'selected':'' }}
                                                    value="TC">Islas Turcas y Caicos
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TD' ? 'selected':'' }}
                                                    value="TD">Chad
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TF' ? 'selected':'' }}
                                                    value="TF">Territorios Australes Franceses
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TG' ? 'selected':'' }}
                                                    value="TG">Togo
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TH' ? 'selected':'' }}
                                                    value="TH">Tailandia
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TH' ? 'selected':'' }}
                                                    value="TH">Tanzania
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TJ' ? 'selected':'' }}
                                                    value="TJ">Tayikistán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TK' ? 'selected':'' }}
                                                    value="TK">Tokelau
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TL' ? 'selected':'' }}
                                                    value="TL">Timor-Leste
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TM' ? 'selected':'' }}
                                                    value="TM">Turkmenistán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TN' ? 'selected':'' }}
                                                    value="TN">Túnez
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TO' ? 'selected':'' }}
                                                    value="TO">Tonga
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TR' ? 'selected':'' }}
                                                    value="TR">Turquía
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TT' ? 'selected':'' }}
                                                    value="TT">Trinidad y Tobago
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TV' ? 'selected':'' }}
                                                    value="TV">Tuvalu
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'TW' ? 'selected':'' }}
                                                    value="TW">Taiwán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'UA' ? 'selected':'' }}
                                                    value="UA">Ucrania
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'UG' ? 'selected':'' }}
                                                    value="UG">Uganda
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'US' ? 'selected':'' }}
                                                    value="US">Estados Unidos de América
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'UY' ? 'selected':'' }}
                                                    value="UY">Uruguay
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'UZ' ? 'selected':'' }}
                                                    value="UZ">Uzbekistán
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'VA' ? 'selected':'' }}
                                                    value="VA">Ciudad del Vaticano
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'VC' ? 'selected':'' }}
                                                    value="VC">San Vicente y las Granadinas
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'VE' ? 'selected':'' }}
                                                    value="VE">Venezuela
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'VG' ? 'selected':'' }}
                                                    value="VG">Islas Vírgenes Británicas
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'VI' ? 'selected':'' }}
                                                    value="VI">Islas Vírgenes de los Estados Unidos de América
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'VN' ? 'selected':'' }}
                                                    value="VN">Vietnam
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'VU' ? 'selected':'' }}
                                                    value="VU">Vanuatu
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'WF' ? 'selected':'' }}
                                                    value="WF">Wallis y Futuna
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'WS' ? 'selected':'' }}
                                                    value="WS">Samoa
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'YE' ? 'selected':'' }}
                                                    value="YE">Yemen
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'YT' ? 'selected':'' }}
                                                    value="YT">Mayotte
                                                </option>
                                                <option
                                                    {{ $companyProfile->country === 'ZA' ? 'selected':'' }}
                                                    value="ZA">Sudáfrica
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="text-primary">Estado / Departamento</label>
                                            <input type="text" class="form-control"
                                                   value="{{$companyProfile->state}}"
                                                   name="UserCompanyProfile[state]"
                                                   {{ $companyProfile->state !== null ? 'disabled' : '' }}
                                                   required
                                                   placeholder="Escribe...">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="text-primary">Ciudad</label>
                                            <input id="person-ciudad" type="text" class="form-control"
                                                   value="{{$companyProfile->city}}"
                                                   name="UserCompanyProfile[city]"
                                                   {{ $companyProfile->city !== null ? 'disabled' : '' }}
                                                   required
                                                   placeholder="Escribe...">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="text-primary">Código Postal</label>
                                            <input id="person-codigo-postal"
                                                   type="text"
                                                   class="form-control"
                                                   value="{{$companyProfile->zip_code}}"
                                                   name="UserCompanyProfile[zip_code]" required
                                                   {{ $companyProfile->zip_code !== null ? 'disabled' : '' }}
                                                   placeholder="Escribe...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="text-primary">Dirección de la empresa</label>
                                            <input id="person-direccion"
                                                   name="UserCompanyProfile[street]"
                                                   value="{{$companyProfile->street}}"
                                                   type="text" class="form-control"
                                                   required
                                                   {{ $companyProfile->street !== null ? 'disabled' : '' }}
                                                   placeholder="Escribe...">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="text-primary">Tipo</label>
                                            <select name="UserCompanyProfile[address_place_type]"
                                                    required
                                                    class="custom-select {{ $companyProfile->address_place_type !== null ?
                                                                    '__disabled_select' : '' }}">
                                                <option value="Almacen"
                                                    {{ $companyProfile->address_place_type === 'Almacen' ? 'selected':''}}
                                                >Almacen
                                                </option>
                                                <option value="Local"
                                                    {{ $companyProfile->address_place_type === 'Local' ? 'selected':''}}
                                                >Local
                                                </option>
                                                <option value="Oficina"
                                                    {{ $companyProfile->address_place_type === 'Oficina' ? 'selected':''}}
                                                >Oficina
                                                </option>
                                                <option value="Casa"
                                                    {{ $companyProfile->address_place_type === 'Casa' ? 'selected':''}}
                                                >Casa
                                                </option>
                                                <option value="Edificio"
                                                    {{ $companyProfile->address_place_type === 'Edificio' ? 'selected':''}}
                                                >Edificio
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="text-primary">Planta / Piso</label>
                                            <input type="text"
                                                   required
                                                   value="{{$companyProfile->address_floor}}"
                                                   name="UserCompanyProfile[address_floor]"
                                                   class="form-control"
                                                   placeholder="Escribe..."
                                                {{ $companyProfile->address_floor !== null ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="text-primary">Número</label>
                                            <input type="text"
                                                   required
                                                   value="{{$companyProfile->address_place_number}}"
                                                   name="UserCompanyProfile[address_place_number]"
                                                   class="form-control"
                                                   placeholder="Escribe..."
                                                {{ $companyProfile->address_place_number !== null ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right mb-5">
                                    <button class="btn btn-secondary btn-pill">Guardar cambios</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
                <form action="{{URL::to('/business-info')}}"
                      enctype="multipart/form-data"
                      method="post"
                      id="__company_id_data_tab"
                      style="display: none;">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div
                                        class="__file_container d-flex flex-column justify-content-between h-100 body-bg-color p-4 rounded-lg text-center">
                                        <div>
                                            @if ($companyProfile->id_confirmation === null)
                                                <img src="/img/landing/company_reg.svg"
                                                     alt="Upload id Confirmation"
                                                     class="mb-3"
                                                     style="max-height: 150px"
                                                     id="__id_comp_img">
                                            @else
                                                @if (substr($companyProfile->id_confirmation, -3, 3) === 'pdf')
                                                    <a href="{{$companyProfile->id_confirmation}}"
                                                       target="_blank"
                                                       style="height: 150px; display: flex; flex-direction: column; justify-content: center">
                                                        <i class="fa-file-pdf-o fa" style="font-size: 30px"></i>
                                                        <br>
                                                        Verificar archivo
                                                    </a>
                                                @else
                                                    <img src="{{$companyProfile->id_confirmation}}"
                                                         alt="Upload id Confirmation"
                                                         class="img-fluid"
                                                         style="max-height: 150px"
                                                         id="__id_comp_img">
                                                @endif
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-4">Fotografía o PDF del Registro de Empresa</h6>
                                            @if($companyProfile->id_confirmation === null)
                                                <a href="#"
                                                   type="button"
                                                   class="btn btn-light btn-sm"
                                                   data-toggle="modal"
                                                   data-target="#upload-company-doc">
                                                    Cargar foto
                                                </a>
                                                <!-- Upload company doc modal -->
                                                <div class="modal fade" id="upload-company-doc" tabindex="-1"
                                                     role="dialog"
                                                     aria-labelledby="upload-companyDoc-modal" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered"
                                                         role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title text-primary"
                                                                    id="upload-companyDoc-modal">Cargar registro de
                                                                    empresa</h6>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body pb-4">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <div class="body-bg-color p-3 rounded-lg mb-3">
                                                                            <h6 class="text-primary">
                                                                                Ten en cuenta esto al cargar el registro
                                                                                de la empresa.
                                                                            </h6>
                                                                            <ul class="text-primary text-left font-14 lh-125 mb-0">
                                                                                <li>Debe estar vigente.</li>
                                                                                <li>No debe estar pixelada.</li>
                                                                                <li>No debe estar borrosa.</li>
                                                                                <li>Debe ser legible.</li>
                                                                                <li>Si tiene más de una página debe ser
                                                                                    PDF.
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div
                                                                            class="alert alert-warning font-14 lh-125 mb-0">
                                                                            <div class="media">
                                                                                <img src="/img/landing/warning-icon.svg"
                                                                                     class="alert-icon mr-3">
                                                                                <div class="media-body">
                                                                                    Estos aspectos serán tomados en
                                                                                    cuenta
                                                                                    por el equipo de revisión y
                                                                                    verificación
                                                                                    a la hora de abrir tu cuenta con
                                                                                    nosotros.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div
                                                                            class="d-flex flex-row flex-md-column justify-content-start align-items-md-center text-md-center mt-4 mt-md-0">
                                                                            <div
                                                                                class="avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3"
                                                                                style="min-height: 238px; padding: 0">
                                                                                <img src="/img/landing/company_reg.svg"
                                                                                     alt="Upload id Confirmation"
                                                                                     class="mb-3"
                                                                                     style="max-height: 238px; max-width: 100%"
                                                                                     id="__id_comp_img_too">
                                                                            </div>
                                                                            <div class="flex-grow-1 mt-2">
                                                                                <h6 class="font-14">
                                                                                    Fotografía o PDF del Registro de
                                                                                    Empresa
                                                                                </h6>
                                                                                <div class="font-12 text-muted">
                                                                                    Tamaño máximo 4mb.
                                                                                </div>
                                                                                <label
                                                                                    class="btn btn-light btn-sm rounded-0">
                                                                                    Seleccionar foto
                                                                                    <input type="file"
                                                                                           class="form-control"
                                                                                           id="__id_comp_input"
                                                                                           accept="image/*,application/pdf"
                                                                                           style="width: 0; height: 0; padding: 0;"
                                                                                           value=""
                                                                                           name="UserCompanyProfile[id_confirmation]"
                                                                                        {{$companyProfile->id_confirmation !== null ? 'disabled' : ''}}
                                                                                        {{$companyProfile->id_confirmation === null ? 'required' : ''}}>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="modal-footer justify-content-between justify-content-md-end">
                                                                <a href="#" type="button"
                                                                   class="btn btn-link text-muted btn-pill btn-sm px-3"
                                                                   data-dismiss="modal"
                                                                   style="color: #b3b3b3 !important;">
                                                                    Cancelar
                                                                </a>
                                                                <a href="#" type="button"
                                                                   class="btn btn-primary btn-pill btn-sm px-3"
                                                                   data-dismiss="modal">
                                                                    Guardar cambios
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div
                                        class="__file_container d-flex flex-column justify-content-between h-100 body-bg-color p-4 rounded-lg text-center">
                                        <div>
                                            @if ($companyProfile->public_service_doc === null)
                                                <img src="/img/landing/company_service.svg"
                                                     alt="Servicio público"
                                                     class="mb-3"
                                                     style="max-height: 150px"
                                                     id="__public_service_img">
                                            @else
                                                @if (substr($companyProfile->public_service_doc, -3, 3) === 'pdf')
                                                    <a href="{{$companyProfile->public_service_doc}}"
                                                       target="_blank"
                                                       style="height: 150px; display: flex; flex-direction: column; justify-content: center">
                                                        <i class="fa-file-pdf-o fa" style="font-size: 30px"></i>
                                                        <br>
                                                        Verificar archivo
                                                    </a>
                                                @else
                                                    <img src="{{$companyProfile->public_service_doc}}"
                                                         alt="Upload id Confirmation"
                                                         class="img-fluid"
                                                         style="max-height: 150px"
                                                         id="__public_service_img">
                                                @endif
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-4">Fotografía o PDF de un servicio público</h6>
                                            @if ($companyProfile->public_service_doc === null)
                                                <a href="#"
                                                   class="btn btn-light btn-sm"
                                                   data-toggle="modal"
                                                   data-target="#upload-publicService">
                                                    Cargar foto
                                                </a>
                                                <!-- Upload public service modal -->
                                                <div class="modal fade" id="upload-publicService" tabindex="-1"
                                                     role="dialog" aria-labelledby="upload-publicService-modal"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered"
                                                         role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title text-primary"
                                                                    id="upload-publicService-modal">Cargar servicio
                                                                    público</h6>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body pb-4">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <div class="body-bg-color p-3 rounded-lg mb-3">
                                                                            <h6 class="text-primary">Ten en cuenta esto
                                                                                cuando cargues tu servicio público</h6>
                                                                            <ul class="text-primary text-left font-14 lh-125 mb-0">
                                                                                <li>Debe estar vigente.</li>
                                                                                <li>No debe estar pixelada.</li>
                                                                                <li>No debe estar borrosa.</li>
                                                                                <li>Debe ser legible.</li>
                                                                                <li>Si tiene más de una página debe ser
                                                                                    PDF.
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div
                                                                            class="alert alert-warning font-14 lh-125 mb-0">
                                                                            <div class="media">
                                                                                <img src="/img/landing/warning-icon.svg"
                                                                                     class="alert-icon mr-3">
                                                                                <div class="media-body">
                                                                                    Estos aspectos serán tomados en
                                                                                    cuenta por el equipo de revisión y
                                                                                    verificación a la hora de abrir tu
                                                                                    cuenta con nosotros.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div
                                                                            class="d-flex flex-row flex-md-column justify-content-start align-items-md-center text-md-center mt-4 mt-md-0">
                                                                            <div
                                                                                class="avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3"
                                                                                style="padding: 0; min-height: 238px">
                                                                                <img
                                                                                    src="/img/landing/company_service.svg"
                                                                                    alt="Servicio público"
                                                                                    class="mb-3"
                                                                                    style="max-height: 238px; max-width: 100%"
                                                                                    id="__public_service_img_too">
                                                                            </div>
                                                                            <div class="flex-grow-1 mt-2">
                                                                                <div class="font-12 text-muted">
                                                                                    Tamaño máximo 4mb.
                                                                                </div>
                                                                                <h6 class="font-14">
                                                                                    Fotografía o PDF de un servicio
                                                                                    público
                                                                                </h6>
                                                                                <label
                                                                                    class="btn btn-light btn-sm rounded-0">
                                                                                    Cargar foto
                                                                                    <input type="file"
                                                                                           class="form-control"
                                                                                           id="__public_service_input"
                                                                                           accept="image/*,application/pdf"
                                                                                           style="width: 0; height: 0; padding: 0;"
                                                                                           value=""
                                                                                           name="UserCompanyProfile[public_service_doc]"
                                                                                        {{$companyProfile->public_service_doc !== null ? 'disabled' : ''}}
                                                                                        {{$companyProfile->public_service_doc === null ? 'required' : ''}}>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="modal-footer justify-content-between justify-content-md-end">
                                                                <a href="#" type="button"
                                                                   class="btn btn-link text-muted btn-pill btn-sm px-3"
                                                                   data-dismiss="modal"
                                                                   style="color: #b3b3b3 !important;">
                                                                    Cancelar
                                                                </a>
                                                                <a href="#" type="button"
                                                                   class="btn btn-primary btn-pill btn-sm px-3"
                                                                   data-dismiss="modal">
                                                                    Guardar cambios
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div
                                        class="__file_container d-flex flex-column justify-content-between h-100 body-bg-color p-4 rounded-lg text-center">
                                        <div>
                                            @if ($companyProfile->tax_id_doc === null)
                                                <img src="/img/landing/company_id_doc.svg"
                                                     alt="Documento tributario"
                                                     class="mb-3"
                                                     style="max-height: 150px"
                                                     id="__tax_id_img">
                                            @else
                                                @if (substr($companyProfile->tax_id_doc, -3, 3) === 'pdf')
                                                    <a href="{{$companyProfile->tax_id_doc}}"
                                                       target="_blank"
                                                       style="height: 150px; display: flex; flex-direction: column; justify-content: center">
                                                        <i class="fa-file-pdf-o fa" style="font-size: 30px"></i>
                                                        <br>
                                                        Verificar archivo
                                                    </a>
                                                @else
                                                    <img src="{{$companyProfile->tax_id_doc}}"
                                                         alt="Upload id Confirmation"
                                                         class="img-fluid"
                                                         style="max-height: 150px"
                                                         id="__tax_id_img">
                                                @endif
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-4">Fotografía o PDF del ID tributario</h6>
                                            @if ($companyProfile->tax_id_doc === null)
                                                <a href="#"
                                                   class="btn btn-light btn-sm"
                                                   data-toggle="modal"
                                                   data-target="#upload-tributaryId">
                                                    Cargar foto
                                                </a>
                                                <div class="modal fade" id="upload-tributaryId" tabindex="-1"
                                                     role="dialog" aria-labelledby="upload-tributaryId-modal"
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered"
                                                         role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title text-primary"
                                                                    id="upload-tributaryId-modal">Cargar ID
                                                                    tributario</h6>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body pb-4">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <div class="body-bg-color p-3 rounded-lg mb-3">
                                                                            <h6 class="text-primary">Ten en cuenta esto
                                                                                cuando cargues tu documento</h6>
                                                                            <ul class="text-primary text-left font-14 lh-125 mb-0">
                                                                                <li>Debe estar vigente.</li>
                                                                                <li>No debe estar pixelada.</li>
                                                                                <li>No debe estar borrosa.</li>
                                                                                <li>Debe ser legible.</li>
                                                                                <li>Si tiene más de una página debe ser
                                                                                    PDF.
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div
                                                                            class="alert alert-warning font-14 lh-125 mb-0">
                                                                            <div class="media">
                                                                                <img src="/img/landing/warning-icon.svg"
                                                                                     class="alert-icon mr-3">
                                                                                <div class="media-body">
                                                                                    Estos aspectos serán tomados en
                                                                                    cuenta por el equipo de revisión y
                                                                                    verificación a la hora de abrir tu
                                                                                    cuenta con nosotros.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div
                                                                            class="d-flex flex-row flex-md-column justify-content-start align-items-md-center text-md-center mt-4 mt-md-0">
                                                                            <div
                                                                                class="avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3"
                                                                                style="padding: 0; min-height: 238px;">
                                                                                <img
                                                                                    src="/img/landing/company_id_doc.svg"
                                                                                    alt="Documento tributario"
                                                                                    class="mb-3"
                                                                                    style="max-height: 238px"
                                                                                    id="__tax_id_img_too">
                                                                            </div>
                                                                            <div class="flex-grow-1 mt-2">
                                                                                <h6 class="font-14">
                                                                                    Fotografía o PDF del ID tributario
                                                                                </h6>
                                                                                <div class="font-12 text-muted">
                                                                                    Tamaño máximo 4mb.
                                                                                </div>
                                                                                <label
                                                                                    class="btn btn-light btn-sm rounded-0">
                                                                                    Cargar foto
                                                                                    <input type="file"
                                                                                           class="form-control"
                                                                                           id="__tax_id_input"
                                                                                           accept="image/*,application/pdf"
                                                                                           style="width: 0; height: 0; padding: 0;"
                                                                                           value=""
                                                                                           name="UserCompanyProfile[tax_id_doc]"
                                                                                        {{$companyProfile->tax_id_doc !== null ? 'disabled' : ''}}
                                                                                        {{$companyProfile->tax_id_doc === null ? 'required' : ''}}>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="modal-footer justify-content-between justify-content-md-end">
                                                                <a href="#" type="button"
                                                                   class="btn btn-link text-muted btn-pill btn-sm px-3"
                                                                   data-dismiss="modal"
                                                                   style="color: #b3b3b3 !important;">
                                                                    Cancelar
                                                                </a>
                                                                <a href="#" type="button"
                                                                   class="btn btn-primary btn-pill btn-sm px-3"
                                                                   data-dismiss="modal">
                                                                    Guardar cambios
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mb-5">
                                <button class="btn btn-secondary btn-pill">Guardar cambios</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection
