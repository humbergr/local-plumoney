@php use Carbon\Carbon; @endphp @extends('layouts.mvp-layout-internal') @section('content')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/css/countrySelect.min.css" integrity="sha256-nEi3429617679e6HunQ6KpCztvItMxIOkEW5u88qSdM=" crossorigin="anonymous" />

 

{{-- expr --}}
@endsection
<main class="dashboard__main" style="  background: #f4f4f9">
    <div class="container mt-2">
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
                    <div class="text-primary lh-125 font-13">Completa tu perfil para que puedas seguir haciendo uso de nuestros servicios.
                    </div>
                </div>
            </div>
        </div>

{{-- Visible Escritorio --}}
        <div class="d-none d-sm-block">
        <ul class="nav profileSettingsNav d-flex flex-row justify-content-center flex-nowrap mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="{{URL::to('/user-info')}}">
                    <i class="fa fa-user" aria-hidden="true"></i> Información General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{URL::to('/business-info')}}">
                    <i class="fa fa-building-o" aria-hidden="true"></i> Perfil de Empresa @if($personProfile->selfie === null || $personProfile->street === null)
                    <span class="badge badge-danger badge-pill float-right ml-2" data-toggle="tooltip" title="Para registrar un perfil de empresa primero debe haber llenado todos los campos en Informacion General e Información de Ubicación.">
                        <i class="fa fa-lock"></i>
                    </span> @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{URL::to('/auth-config')}}">
                    <i class="fa fa-qrcode" aria-hidden="true"></i> Two Factor Authentication
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{URL::to('/change-password')}}">
                    <i class="fa fa-key" aria-hidden="true"></i> Contraseña
                </a>
            </li>
        </ul>
        </div>
{{-- Visible Escritorio --}}

{{-- Visible mobil --}}
        <div class="d-block d-sm-none mobil">
        <ul class="nav profileSettingsNav d-flex flex-column justify-content-center flex-nowrap mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="{{URL::to('/user-info')}}">
                    <i class="fa fa-user" aria-hidden="true"></i> Información General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{URL::to('/business-info')}}">
                    <i class="fa fa-building-o" aria-hidden="true"></i> Perfil de Empresa @if($personProfile->selfie === null || $personProfile->street === null)
                    <span class="badge badge-danger badge-pill float-right ml-2" data-toggle="tooltip" title="Para registrar un perfil de empresa primero debe haber llenado todos los campos en Informacion General e Información de Ubicación.">
                        <i class="fa fa-lock"></i>
                    </span> @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{URL::to('/auth-config')}}">
                    <i class="fa fa-qrcode" aria-hidden="true"></i> Two Factor Authentication
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{URL::to('/change-password')}}">
                    <i class="fa fa-key" aria-hidden="true"></i> Contraseña
                </a>
            </li>
        </ul>
        </div>
{{-- Visible mobil --}}




        <div class="card shadow-none rounded-lg mb-4">
            <div class="card-header pt-4 pb-1">



                <ul class="nav justify-content-center flex-nowrap mb-4" id="profileTab" role="tablist">
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-pill px-4 mx-1 mx-md-2 font-mobile-14 lh-125 active" id="personal-info" data-toggle="tab" href="#tab_general-info" role="tab" aria-controls="general-info" aria-selected="true">PASO 1 <span class="d-none d-sm-block"> Datos personales <span></a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-pill px-4 mx-1 mx-md-2 font-mobile-14 lh-125" id="identity-info-tab" data-toggle="tab" href="#tab_identity" role="tab" aria-controls="identity" aria-selected="false">PASO 2 <span class="d-none d-sm-block">Verificación de identidad</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-pill px-4 mx-1 mx-md-2 font-mobile-14 lh-125" id="identity-info-tab" data-toggle="tab" href="#tab_telefono" role="tab" aria-controls="identity" aria-selected="false">PASO 3 <span class="d-none d-sm-block">Verificación de celular</span></a>
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
                                <h5 class="alert-heading">¡Atención!</h5> Para utilizar nuestros servicios primero debe completar su perfil de usuario. Por favor llene este formulario.
                            </div>
                        </div>
                    </div>
                    @elseif ($personProfile->approval_status === 1)
                    <div class="alert alert-warning mb-4">
                        <div class="media">
                            <img src="img/landing/warning-icon.svg" class="alert-icon mr-3">
                            <div class="media-body">
                                <h5 class="alert-heading">¡Atención!</h5> Su solicitud de aprobación de perfil está siendo revisada por nuestro departamento de cumplimiento de políticas AML y KYC.
                            </div>
                        </div>
                    </div>
                    @elseif ($personProfile->approval_status === 3)
                    <div class="alert alert-danger mb-4">
                        <div class="media">
                            <img src="img/landing/danger-icon.svg" class="alert-icon mr-3">
                            <div class="media-body">
                                <h5 class="alert-heading">¡Atención!</h5> Su perfil ha sido rechazado, por favor revise y actualice sus datos o pongase en contacto con soporte.
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="tab-content" id="profileTabContent">

                <div class="tab-pane fade show" id="tab_telefono" role="tabpanel" aria-labelledby="personal-info">

                    <form action="{{ url('user-verify-phone') }}" onsubmit="return  checkPhone()"  method="get" accept-charset="utf-8" class="px-lg-5 p-2">

                     @csrf

                     <h6 class="text-primary font-weight-bold mb-3">Verificación de celular</h6>

                     {{-- $personProfile->mobile --}}


                     @if($personProfile->mobile_verified === 0)

                     <div class="form-wrap form-wrap-icon">

                        <div class="">
                         <div class="form-wrap form-wrap-icon  " >
                            <label for="pais" class=""> <div class="mdi mdi-cellphone"></div></label>
                            <input id="phone" class="form-input form-control" type="tel" name="phone_temp">

                            <div id="btn-msn" class=" m-3">
                                <button class="btn btn-primary" type="submit">Continuar</button>
                            </div>  






                        </div>

                    </div>
                </div>

                @else 


                <div class="mb-4">

                    <img src="{{ asset('img/landing/success-icon.svg') }}" alt=""> <span class="text-success">Verificado ****-****-{{substr($personProfile->mobile, -4)}}</span>
                </div>



                @endif



            </form>

        </div>




        <div class="tab-pane fade show active" id="tab_general-info" role="tabpanel" aria-labelledby="personal-info">




            <form action="{{URL::to('/user-info')}}" onsubmit="return  checkLocal()" style="" enctype="multipart/form-data" method="post" class="px-lg-5 p-2" id="">


              @if($personProfile->datos_verified === 1)

              <div class="container">
               <h6 class="text-primary font-weight-bold mb-3">Verificación de perfil</h6>
               <div class="mb-4">
                <img src="{{ asset('img/landing/success-icon.svg') }}" alt=""> <span class="text-success">Verificado y completado por favor continua con el siguiente paso </span>
            </div>
        </div>
        @else

        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-3 col-lg-3 col-xl-2">
                <div class="body-bg-color p-4 p-md-3 rounded-lg d-flex flex-row flex-md-column justify-content-start align-items-center text-md-center mb-4" style="padding: 0; min-height: 180px">
                    <div class="__image_label mb-2" @if ($personProfile->selfie !== null) style="align-items: center" @endif > @if ($personProfile->selfie === null)
                        <img src="/img/landing/selfie-icon-2.svg" alt="Upload selfie" class="img-fluid" style="max-height: 180px" id="__selfie_img"> @else
                        <img src="{{$personProfile->selfie}}" alt="Upload selfie" style="max-height: 180px" class="img-fluid" id="__selfie_img"> @endif
                    </div>
                    <h6 class="text-primary font-18 font-weight-bold">Foto tipo selfie</h6> @if ($errors->has('selfie'))

                    <p class="text-danger font-weight-bold">{{ $errors->first('selfie') }}</p>

                    @endif @if((($personProfile->approval_status !== 1 && $personProfile->approval_status !== 2) || $personProfile->selfie === null))
                    <a href="#" class="btn btn-light btn-sm" data-toggle="modal" data-target="#upload-avatar">
                        Cargar foto
                    </a> @endif
                </div>
            </div>
            @if((($personProfile->approval_status !== 1 && $personProfile->approval_status !== 2) || $personProfile->selfie === null))
            <div class="modal fade" id="upload-avatar" tabindex="-1" role="dialog" aria-labelledby="upload-avatar-modal" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-primary" id="upload-avatar-modal">Cargar
                                fotografía
                            tipo selfie</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body pb-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="body-bg-color p-3 rounded-lg mb-3">
                                        <h6 class="text-primary">Ten en cuenta esto cuando cargues tu
                                            foto
                                        tipo selfie</h6>
                                        <ul class="text-primary font-14 lh-125 mb-0">
                                            <li>No usar sombrero ni nada que te cubra la cabeza.</li>
                                            <li>No usar lentes.</li>
                                            <li>Mostrar el rostro completo.</li>
                                            <li>Debe ser reciente.</li>
                                            <li>No debe estar pixelada.</li>
                                            <li>No debe estar borrosa.</li>
                                        </ul>
                                    </div>
                                    <div class="alert alert-warning font-14 lh-125 mb-0">
                                        <div class="media">
                                            <img src="/img/landing/warning-icon.svg" class="alert-icon mr-3">
                                            <div class="media-body">
                                                Estos aspectos serán tomados en cuenta por el equipo de revisión y verificación a la hora de abrir tu cuenta con nosotros.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-row flex-md-column justify-content-start align-items-md-center text-md-center mt-4 mt-md-0">
                                        <div class="avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3" style="padding: 0; min-height: 238px">
                                            <img src="/img/landing/selfie-icon-2.svg" alt="Upload selfie" class="img-fluid " style="height: 100%; max-height: 238px" id="__selfie_img_too">
                                        </div>
                                        <div class="flex-grow-1 mt-2">
                                            <h6 class="font-14">Foto tipo selfie</h6> @if ($errors->has('selfie'))

                                            <p class="text-danger font-weight-bold">{{ $errors->first('selfie') }}</p>

                                            @endif
                                            <div class="font-12 text-muted">Max. 4mb.</div>
                                            <label class="btn btn-light btn-sm rounded-0">
                                                Seleccionar foto
                                                <input type="file" class="form-control" id="__selfie_input" accept="image/*" style="width: 0; height: 0; padding: 0;" value="{{$personProfile->selfie}}" name="selfie" @if ($personProfile->selfie === null) @endif>

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between justify-content-md-end">
                            <a href="#" type="button" class="btn btn-link text-muted btn-pill btn-sm px-3" data-dismiss="modal" style="color: #b3b3b3 !important;">
                                Cancelar
                            </a>
                            <a href="#" type="button" class="btn btn-primary btn-pill btn-sm px-3" data-dismiss="modal">
                                Guardar cambios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-10">
                <div class="row">
                    <div class="col-sm-3">

                        <input type="hidden" id="gps" name="gps" value="">

                        <div class="form-group{{ $errors->has('primer_nombre') ? ' has-danger' : '' }}">
                            <label class="form-control-label text-primary" for="input-primer_nombre">{{ __('Primer Nombre') }}</label>
                            <input type="text" name="primer_nombre" id="input-primer_nombre" class="form-control  form-control-alternative{{ $errors->has('primer_nombre') ? ' is-invalid' : '' }}" placeholder="{{ __('Primer Nombre') }}" value="{{ old('primer_nombre', @$personProfile->first_name) }}"> @if ($errors->has('primer_nombre'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('primer_nombre') }}</strong>
                            </span> @endif
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group{{ $errors->has('segundo_nombre') ? ' has-danger' : '' }}">
                            <label class="form-control-label text-primary" for="input-segundo_nombre">{{ __('Segundo Nombre') }}</label>
                            <input type="text" name="segundo_nombre" id="segundo_nombre" class="form-control  form-control-alternative{{ $errors->has('segundo_nombre') ? ' is-invalid' : '' }}" placeholder="{{ __('Segundo Nombre') }}" value="{{ old('segundo_nombre', $personProfile->second_name) }}"> @if ($errors->has('segundo_nombre'))
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('segundo_nombre') }}</strong>
                          </span> 
                          <span id="__no_second_name"
                          class="text-danger d-block"
                          style="cursor: pointer; font-size: 11px">
                          ¿Sin segundo nombre? Haz click aquí.

                          <input type="hidden" id="check_segundo_nombre" name="check_segundo_nombre" value="{{old('check_segundo_nombre')}}">
                      </span>
                      @endif
                  </div>
              </div>

              <div class="col-sm-3">
                <div class="form-group{{ $errors->has('primer_apellido') ? ' has-danger' : '' }}">
                    <label class="form-control-label text-primary" for="input-primer_apellido">{{ __('Primer Apellido') }}</label>
                    <input type="text" name="primer_apellido" id="input-primer_apellido" class="form-control  form-control-alternative{{ $errors->has('primer_apellido') ? ' is-invalid' : '' }}" placeholder="{{ __('Primer Apellido') }}" value="{{ old('primer_apellido', @$personProfile->last_name) }}"> @if ($errors->has('primer_apellido'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('primer_apellido') }}</strong>
                    </span> @endif
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group{{ $errors->has('segundo_apellido') ? ' has-danger' : '' }}">
                    <label class="form-control-label text-primary" for="input-segundo_apellido">{{ __('Segundo Apellido') }}</label>
                    <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control  form-control-alternative{{ $errors->has('segundo_apellido') ? ' is-invalid' : '' }}" placeholder="{{ __('Segundo Apellido') }}" value="{{ old('segundo_apellido', @$personProfile->second_name) }}"> @if ($errors->has('segundo_apellido'))
                    <span class="invalid-feedback" role="alert">
                     <strong>{{ $errors->first('segundo_apellido') }}</strong>
                 </span> 

                   <span id="__no_second_apellido"
                          class="text-danger d-block"
                          style="cursor: pointer; font-size: 11px">
                          ¿Sin segundo apellido? Haz click aquí.

                          <input type="hidden" id="check_segundo_apellido" name="check_segundo_apellido" value="{{old('check_segundo_apellido')}}">
                      </span>



                 @endif
             </div>
         </div>
     </div>
     <div class="row">
        <div class="col-md-4 col-lg-3">
            @php $birthValue = $personProfile->birth_year . '-' . $personProfile->birth_month . '-' . $personProfile->birth_day; @endphp
            <div class="form-group{{ $errors->has('fecha_de_nacimiento') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-fecha_de_nacimiento">{{ __('Fecha de nacimiento') }}</label>
                <input type="date" name="fecha_de_nacimiento" id="input-fecha_de_nacimiento" class="form-control  form-control-alternative{{ $errors->has('fecha_de_nacimiento') ? ' is-invalid' : '' }}" placeholder="{{ __('Fecha de nacimiento') }}" value="{{ old('fecha_de_nacimiento',@$birthValue) }}"> @if ($errors->has('fecha_de_nacimiento'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('fecha_de_nacimiento') }}</strong>
                </span> @endif
            </div>
        </div>
        <div class="col-md-8 col-lg-6">

            <div class="form-group{{ $errors->has('correo_electronico') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-correo_electronico">{{ __('Correo Electrónico') }}</label>
                <input disabled="" type="email" name="correo_electronico" id="input-correo_electronico" class="form-control  form-control-alternative{{ $errors->has('correo_electronico') ? ' is-invalid' : '' }}" placeholder="{{ __('Correo Electrónico') }}" value="{{ old('correo_electronico',  @$personProfile->email) }}"> @if ($errors->has('correo_electronico'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('correo_electronico') }}</strong>
                </span> @endif
            </div>
        </div>


        <div class="col-md-4 col-lg-3">
            <div class="form-group{{ $errors->has('local') ? ' has-danger' : '' }}">
                <label class="form-control-label text-primary" for="input-local">{{ __('Telefono Fijo') }}</label>
                <label for="pais" class=""> <div class="mdi mdi-cellphone"></div></label>
                <input id="local" value="{{ old('local', $personProfile->local_phone) }}"  class="form-input form-control" type="tel" name="phone_temp">




                @if ($errors->has('local'))



                <span class="invalid-feedback" role="alert">
                   <strong>{{ $errors->first('local') }}</strong>
               </span> @endif
           </div>
       </div>
   </div>
   <div class="row">
    <div class="col-md-4 col-lg-3">
        <div class="form-group">
            <label class="text-primary">Pais </label>

            <select name="pais" id="person-select-country" class="form-control">


                <option value="">option</option>


                <option value="" selected>Pais</option>
                <option {{ $personProfile->country === 'AD' ? 'selected':'' }} value="AD">Andorra
                </option>
                <option {{ $personProfile->country === 'AE' ? 'selected':'' }} value="AE">Emiratos Árabes Unidos
                </option>
                <option {{ $personProfile->country === 'AF' ? 'selected':'' }} value="AF">Afganistán
                </option>
                <option {{ $personProfile->country === 'AG' ? 'selected':'' }} value="AG">Antigua y Barbuda
                </option>
                <option {{ $personProfile->country === 'AI' ? 'selected':'' }} value="AI">Anguila
                </option>
                <option {{ $personProfile->country === 'AL' ? 'selected':'' }} value="AL">Albania
                </option>
                <option {{ $personProfile->country === 'AM' ? 'selected':'' }} value="AM">Armenia
                </option>
                <option {{ $personProfile->country === 'AO' ? 'selected':'' }} value="AO">Angola
                </option>
                <option {{ $personProfile->country === 'AQ' ? 'selected':'' }} value="AQ">Antártida
                </option>
                <option {{ $personProfile->country === 'AR' ? 'selected':'' }} value="AR">Argentina
                </option>
                <option {{ $personProfile->country === 'AS' ? 'selected':'' }} value="AS">Samoa Americana
                </option>
                <option {{ $personProfile->country === 'AT' ? 'selected':'' }} value="AT">Austria
                </option>
                <option {{ $personProfile->country === 'AU' ? 'selected':'' }} value="AU">Australia
                </option>
                <option {{ $personProfile->country === 'AW' ? 'selected':'' }} value="AW">Aruba
                </option>
                <option {{ $personProfile->country === 'AX' ? 'selected':'' }} value="AX">Islas Áland
                </option>
                <option {{ $personProfile->country === 'AZ' ? 'selected':'' }} value="AZ">Azerbaiyán
                </option>
                <option {{ $personProfile->country === 'BA' ? 'selected':'' }} value="BA">Bosnia y Herzegovina
                </option>
                <option {{ $personProfile->country === 'BB' ? 'selected':'' }} value="BB">Barbados
                </option>
                <option {{ $personProfile->country === 'BD' ? 'selected':'' }} value="BD">Bangladesh
                </option>
                <option {{ $personProfile->country === 'BE' ? 'selected':'' }} value="BE">Bélgica
                </option>
                <option {{ $personProfile->country === 'BF' ? 'selected':'' }} value="BF">Burkina Faso
                </option>
                <option {{ $personProfile->country === 'BG' ? 'selected':'' }} value="BG">Bulgaria
                </option>
                <option {{ $personProfile->country === 'BH' ? 'selected':'' }} value="BH">Bahréin
                </option>
                <option {{ $personProfile->country === 'BI' ? 'selected':'' }} value="BI">Burundi
                </option>
                <option {{ $personProfile->country === 'BJ' ? 'selected':'' }} value="BJ">Benin
                </option>
                <option {{ $personProfile->country === 'BL' ? 'selected':'' }} value="BL">San Bartolomé
                </option>
                <option {{ $personProfile->country === 'BM' ? 'selected':'' }} value="BM">Bermudas
                </option>
                <option {{ $personProfile->country === 'BN' ? 'selected':'' }} value="BN">Brunéi
                </option>
                <option {{ $personProfile->country === 'BO' ? 'selected':'' }} value="BO">Bolivia
                </option>
                <option {{ $personProfile->country === 'BR' ? 'selected':'' }} value="BR">Brasil
                </option>
                <option {{ $personProfile->country === 'BS' ? 'selected':'' }} value="BS">Bahamas
                </option>
                <option {{ $personProfile->country === 'BT' ? 'selected':'' }} value="BT">Bhután
                </option>
                <option {{ $personProfile->country === 'BV' ? 'selected':'' }} value="BV">Isla Bouvet
                </option>
                <option {{ $personProfile->country === 'BW' ? 'selected':'' }} value="BW">Botsuana
                </option>
                <option {{ $personProfile->country === 'BY' ? 'selected':'' }} value="BY">Belarús
                </option>
                <option {{ $personProfile->country === 'BZ' ? 'selected':'' }} value="BZ">Belice
                </option>
                <option {{ $personProfile->country === 'CA' ? 'selected':'' }} value="CA">Canadá
                </option>
                <option {{ $personProfile->country === 'CC' ? 'selected':'' }} value="CC">Islas Cocos
                </option>
                <option {{ $personProfile->country === 'CF' ? 'selected':'' }} value="CF">República Centro-Africana
                </option>
                <option {{ $personProfile->country === 'CG' ? 'selected':'' }} value="CG">Congo
                </option>
                <option {{ $personProfile->country === 'CH' ? 'selected':'' }} value="CH">Suiza
                </option>
                <option {{ $personProfile->country === 'CI' ? 'selected':'' }} value="CI">Costa de Marfil
                </option>
                <option {{ $personProfile->country === 'CK' ? 'selected':'' }} value="CK">Islas Cook
                </option>
                <option {{ $personProfile->country === 'CL' ? 'selected':'' }} value="CL">Chile
                </option>
                <option {{ $personProfile->country === 'CM' ? 'selected':'' }} value="CM">Camerún
                </option>
                <option {{ $personProfile->country === 'CN' ? 'selected':'' }} value="CN">China
                </option>
                <option {{ $personProfile->country === 'CO' ? 'selected':'' }} value="CO">Colombia
                </option>
                <option {{ $personProfile->country === 'CR' ? 'selected':'' }} value="CR">Costa Rica
                </option>
                <option {{ $personProfile->country === 'CU' ? 'selected':'' }} value="CU">Cuba
                </option>
                <option {{ $personProfile->country === 'CV' ? 'selected':'' }} value="CV">Cabo Verde
                </option>
                <option {{ $personProfile->country === 'CX' ? 'selected':'' }} value="CX">Islas Christmas
                </option>
                <option {{ $personProfile->country === 'CY' ? 'selected':'' }} value="CY">Chipre
                </option>
                <option {{ $personProfile->country === 'CZ' ? 'selected':'' }} value="CZ">República Checa
                </option>
                <option {{ $personProfile->country === 'DE' ? 'selected':'' }} value="DE">Alemania
                </option>
                <option {{ $personProfile->country === 'DJ' ? 'selected':'' }} value="DJ">Yibuti
                </option>
                <option {{ $personProfile->country === 'DK' ? 'selected':'' }} value="DK">Dinamarca
                </option>
                <option {{ $personProfile->country === 'DM' ? 'selected':'' }} value="DM">Domínica
                </option>
                <option {{ $personProfile->country === 'DO' ? 'selected':'' }} value="DO">República Dominicana
                </option>
                <option {{ $personProfile->country === 'DZ' ? 'selected':'' }} value="DZ">Argel
                </option>
                <option {{ $personProfile->country === 'EC' ? 'selected':'' }} value="EC">Ecuador
                </option>
                <option {{ $personProfile->country === 'EE' ? 'selected':'' }} value="EE">Estonia
                </option>
                <option {{ $personProfile->country === 'EG' ? 'selected':'' }} value="EG">Egipto
                </option>
                <option {{ $personProfile->country === 'EH' ? 'selected':'' }} value="EH">Sahara Occidental
                </option>
                <option {{ $personProfile->country === 'ER' ? 'selected':'' }} value="ER">Eritrea
                </option>
                <option {{ $personProfile->country === 'ES' ? 'selected':'' }} value="ES">España
                </option>
                <option {{ $personProfile->country === 'ET' ? 'selected':'' }} value="ET">Etiopía
                </option>
                <option {{ $personProfile->country === 'FI' ? 'selected':'' }} value="FI">Finlandia
                </option>
                <option {{ $personProfile->country === 'FJ' ? 'selected':'' }} value="FJ">Fiji
                </option>
                <option {{ $personProfile->country === 'FK' ? 'selected':'' }} value="FK">Islas Malvinas
                </option>
                <option {{ $personProfile->country === 'FM' ? 'selected':'' }} value="FM">Micronesia
                </option>
                <option {{ $personProfile->country === 'FO' ? 'selected':'' }} value="FO">Islas Faroe
                </option>
                <option {{ $personProfile->country === 'FR' ? 'selected':'' }} value="FR">Francia
                </option>
                <option {{ $personProfile->country === 'GA' ? 'selected':'' }} value="GA">Gabón
                </option>
                <option {{ $personProfile->country === 'GB' ? 'selected':'' }} value="GB">Reino Unido
                </option>
                <option {{ $personProfile->country === 'GD' ? 'selected':'' }} value="GD">Granada
                </option>
                <option {{ $personProfile->country === 'GE' ? 'selected':'' }} value="GE">Georgia
                </option>
                <option {{ $personProfile->country === 'GF' ? 'selected':'' }} value="GF">Guayana Francesa
                </option>
                <option {{ $personProfile->country === 'GG' ? 'selected':'' }} value="GG">Guernsey
                </option>
                <option {{ $personProfile->country === 'GH' ? 'selected':'' }} value="GH">Ghana
                </option>
                <option {{ $personProfile->country === 'GI' ? 'selected':'' }} value="GI">Gibraltar
                </option>
                <option {{ $personProfile->country === 'GL' ? 'selected':'' }} value="GL">Groenlandia
                </option>
                <option {{ $personProfile->country === 'GM' ? 'selected':'' }} value="GM">Gambia
                </option>
                <option {{ $personProfile->country === 'GN' ? 'selected':'' }} value="GN">Guinea
                </option>
                <option {{ $personProfile->country === 'GP' ? 'selected':'' }} value="GP">Guadalupe
                </option>
                <option {{ $personProfile->country === 'GQ' ? 'selected':'' }} value="GQ">Guinea Ecuatorial
                </option>
                <option {{ $personProfile->country === 'GR' ? 'selected':'' }} value="GR">Grecia
                </option>
                <option {{ $personProfile->country === 'GS' ? 'selected':'' }} value="GS">Georgia del Sur e Islas Sandwich del Sur
                </option>
                <option {{ $personProfile->country === 'GT' ? 'selected':'' }} value="GT">Guatemala
                </option>
                <option {{ $personProfile->country === 'GU' ? 'selected':'' }} value="GU">Guam
                </option>
                <option {{ $personProfile->country === 'GW' ? 'selected':'' }} value="GW">Guinea-Bissau
                </option>
                <option {{ $personProfile->country === 'GY' ? 'selected':'' }} value="GY">Guayana
                </option>
                <option {{ $personProfile->country === 'HK' ? 'selected':'' }} value="HK">Hong Kong
                </option>
                <option {{ $personProfile->country === 'HM' ? 'selected':'' }} value="HM">Islas Heard y McDonald
                </option>
                <option {{ $personProfile->country === 'HN' ? 'selected':'' }} value="HN">Honduras
                </option>
                <option {{ $personProfile->country === 'HR' ? 'selected':'' }} value="HR">Croacia
                </option>
                <option {{ $personProfile->country === 'HT' ? 'selected':'' }} value="HT">Haití
                </option>
                <option {{ $personProfile->country === 'HU' ? 'selected':'' }} value="HU">Hungría
                </option>
                <option {{ $personProfile->country === 'ID' ? 'selected':'' }} value="ID">Indonesia
                </option>
                <option {{ $personProfile->country === 'IE' ? 'selected':'' }} value="IE">Irlanda
                </option>
                <option {{ $personProfile->country === 'IL' ? 'selected':'' }} value="IL">Israel
                </option>
                <option {{ $personProfile->country === 'IM' ? 'selected':'' }} value="IM">Isla de Man
                </option>
                <option {{ $personProfile->country === 'IN' ? 'selected':'' }} value="IN">India
                </option>
                <option {{ $personProfile->country === 'IO' ? 'selected':'' }} value="IO">Territorio Británico del Océano Índico
                </option>
                <option {{ $personProfile->country === 'IQ' ? 'selected':'' }} value="IQ">Irak
                </option>
                <option {{ $personProfile->country === 'IR' ? 'selected':'' }} value="IR">Irán
                </option>
                <option {{ $personProfile->country === 'IS' ? 'selected':'' }} value="IS">Islandia
                </option>
                <option {{ $personProfile->country === 'IT' ? 'selected':'' }} value="IT">Italia
                </option>
                <option {{ $personProfile->country === 'JE' ? 'selected':'' }} value="JE">Jersey
                </option>
                <option {{ $personProfile->country === 'JM' ? 'selected':'' }} value="JM">Jamaica
                </option>
                <option {{ $personProfile->country === 'JO' ? 'selected':'' }} value="JO">Jordania
                </option>
                <option {{ $personProfile->country === 'JP' ? 'selected':'' }} value="JP">Japón
                </option>
                <option {{ $personProfile->country === 'KE' ? 'selected':'' }} value="KE">Kenia
                </option>
                <option {{ $personProfile->country === 'KG' ? 'selected':'' }} value="KG">Kirguistán
                </option>
                <option {{ $personProfile->country === 'KH' ? 'selected':'' }} value="KH">Camboya
                </option>
                <option {{ $personProfile->country === 'KI' ? 'selected':'' }} value="KI">Kiribati
                </option>
                <option {{ $personProfile->country === 'KM' ? 'selected':'' }} value="KM">Comoros
                </option>
                <option {{ $personProfile->country === 'KN' ? 'selected':'' }} value="KN">San Cristóbal y Nieves
                </option>
                <option {{ $personProfile->country === 'KP' ? 'selected':'' }} value="KP">Corea del Norte
                </option>
                <option {{ $personProfile->country === 'KR' ? 'selected':'' }} value="KR">Corea del Sur
                </option>
                <option {{ $personProfile->country === 'KW' ? 'selected':'' }} value="KW">Kuwait
                </option>
                <option {{ $personProfile->country === 'KY' ? 'selected':'' }} value="KY">Islas Caimán
                </option>
                <option {{ $personProfile->country === 'KZ' ? 'selected':'' }} value="KZ">Kazajstán
                </option>
                <option {{ $personProfile->country === 'LA' ? 'selected':'' }} value="LA">Laos
                </option>
                <option {{ $personProfile->country === 'LB' ? 'selected':'' }} value="LB">Líbano
                </option>
                <option {{ $personProfile->country === 'LC' ? 'selected':'' }} value="LC">Santa Lucía
                </option>
                <option {{ $personProfile->country === 'LI' ? 'selected':'' }} value="LI">Liechtenstein
                </option>
                <option {{ $personProfile->country === 'LK' ? 'selected':'' }} value="LK">Sri Lanka
                </option>
                <option {{ $personProfile->country === 'LR' ? 'selected':'' }} value="LR">Liberia
                </option>
                <option {{ $personProfile->country === 'LS' ? 'selected':'' }} value="LS">Lesotho
                </option>
                <option {{ $personProfile->country === 'LT' ? 'selected':'' }} value="LT">Lituania
                </option>
                <option {{ $personProfile->country === 'LU' ? 'selected':'' }} value="LU">Luxemburgo
                </option>
                <option {{ $personProfile->country === 'LV' ? 'selected':'' }} value="LV">Letonia
                </option>
                <option {{ $personProfile->country === 'LY' ? 'selected':'' }} value="LY">Libia
                </option>
                <option {{ $personProfile->country === 'MA' ? 'selected':'' }} value="MA">Marruecos
                </option>
                <option {{ $personProfile->country === 'MC' ? 'selected':'' }} value="MC">Mónaco
                </option>
                <option {{ $personProfile->country === 'MD' ? 'selected':'' }} value="MD">Moldova
                </option>
                <option {{ $personProfile->country === 'ME' ? 'selected':'' }} value="ME">Montenegro
                </option>
                <option {{ $personProfile->country === 'MG' ? 'selected':'' }} value="MG">Madagascar
                </option>
                <option {{ $personProfile->country === 'MH' ? 'selected':'' }} value="MH">Islas Marshall
                </option>
                <option {{ $personProfile->country === 'MK' ? 'selected':'' }} value="MK">Macedonia
                </option>
                <option {{ $personProfile->country === 'ML' ? 'selected':'' }} value="ML">Mali
                </option>
                <option {{ $personProfile->country === 'MM' ? 'selected':'' }} value="MM">Myanmar
                </option>
                <option {{ $personProfile->country === 'MN' ? 'selected':'' }} value="MN">Mongolia
                </option>
                <option {{ $personProfile->country === 'MO' ? 'selected':'' }} value="MO">Macao
                </option>
                <option {{ $personProfile->country === 'MQ' ? 'selected':'' }} value="MQ">Martinica
                </option>
                <option {{ $personProfile->country === 'MR' ? 'selected':'' }} value="MR">Mauritania
                </option>
                <option {{ $personProfile->country === 'MS' ? 'selected':'' }} value="MS">Montserrat
                </option>
                <option {{ $personProfile->country === 'MT' ? 'selected':'' }} value="MT">Malta
                </option>
                <option {{ $personProfile->country === 'MU' ? 'selected':'' }} value="MU">Mauricio
                </option>
                <option {{ $personProfile->country === 'MV' ? 'selected':'' }} value="MV">Maldivas
                </option>
                <option {{ $personProfile->country === 'MW' ? 'selected':'' }} value="MW">Malawi
                </option>
                <option {{ $personProfile->country === 'MX' ? 'selected':'' }} value="MX">México
                </option>
                <option {{ $personProfile->country === 'MY' ? 'selected':'' }} value="MY">Malasia
                </option>
                <option {{ $personProfile->country === 'MZ' ? 'selected':'' }} value="MZ">Mozambique
                </option>
                <option {{ $personProfile->country === 'NA' ? 'selected':'' }} value="NA">Namibia
                </option>
                <option {{ $personProfile->country === 'NC' ? 'selected':'' }} value="NC">Nueva Caledonia
                </option>
                <option {{ $personProfile->country === 'NE' ? 'selected':'' }} value="NE">Níger
                </option>
                <option {{ $personProfile->country === 'NF' ? 'selected':'' }} value="NF">Islas Norkfolk
                </option>
                <option {{ $personProfile->country === 'NG' ? 'selected':'' }} value="NG">Nigeria
                </option>
                <option {{ $personProfile->country === 'NI' ? 'selected':'' }} value="NI">Nicaragua
                </option>
                <option {{ $personProfile->country === 'NL' ? 'selected':'' }} value="NL">Países Bajos
                </option>
                <option {{ $personProfile->country === 'NO' ? 'selected':'' }} value="NO">Noruega
                </option>
                <option {{ $personProfile->country === 'NP' ? 'selected':'' }} value="NP">Nepal
                </option>
                <option {{ $personProfile->country === 'NR' ? 'selected':'' }} value="NR">Nauru
                </option>
                <option {{ $personProfile->country === 'NU' ? 'selected':'' }} value="NU">Niue
                </option>
                <option {{ $personProfile->country === 'NZ' ? 'selected':'' }} value="NZ">Nueva Zelanda
                </option>
                <option {{ $personProfile->country === 'OM' ? 'selected':'' }} value="OM">Omán
                </option>
                <option {{ $personProfile->country === 'PA' ? 'selected':'' }} value="PA">Panamá
                </option>
                <option {{ $personProfile->country === 'PE' ? 'selected':'' }} value="PE">Perú
                </option>
                <option {{ $personProfile->country === 'PF' ? 'selected':'' }} value="PF">Polinesia Francesa
                </option>
                <option {{ $personProfile->country === 'PG' ? 'selected':'' }} value="PG">Papúa Nueva Guinea
                </option>
                <option {{ $personProfile->country === 'PH' ? 'selected':'' }} value="PH">Filipinas
                </option>
                <option {{ $personProfile->country === 'PK' ? 'selected':'' }} value="PK">Pakistán
                </option>
                <option {{ $personProfile->country === 'PL' ? 'selected':'' }} value="PL">Polonia
                </option>
                <option {{ $personProfile->country === 'PM' ? 'selected':'' }} value="PM">San Pedro y Miquelón
                </option>
                <option {{ $personProfile->country === 'PN' ? 'selected':'' }} value="PN">Islas Pitcairn
                </option>
                <option {{ $personProfile->country === 'PR' ? 'selected':'' }} value="PR">Puerto Rico
                </option>
                <option {{ $personProfile->country === 'PS' ? 'selected':'' }} value="PS">Palestina
                </option>
                <option {{ $personProfile->country === 'PT' ? 'selected':'' }} value="PT">Portugal
                </option>
                <option {{ $personProfile->country === 'PW' ? 'selected':'' }} value="PW">Islas Palaos
                </option>
                <option {{ $personProfile->country === 'PY' ? 'selected':'' }} value="PY">Paraguay
                </option>
                <option {{ $personProfile->country === 'QA' ? 'selected':'' }} value="QA">Qatar
                </option>
                <option {{ $personProfile->country === 'RE' ? 'selected':'' }} value="RE">Reunión
                </option>
                <option {{ $personProfile->country === 'RO' ? 'selected':'' }} value="RO">Rumanía
                </option>
                <option {{ $personProfile->country === 'RS' ? 'selected':'' }} value="RS">Serbia y Montenegro
                </option>
                <option {{ $personProfile->country === 'RU' ? 'selected':'' }} value="RU">Rusia
                </option>
                <option {{ $personProfile->country === 'RW' ? 'selected':'' }} value="RW">Ruanda
                </option>
                <option {{ $personProfile->country === 'SA' ? 'selected':'' }} value="SA">Arabia Saudita
                </option>
                <option {{ $personProfile->country === 'SB' ? 'selected':'' }} value="SB">Islas Solomón
                </option>
                <option {{ $personProfile->country === 'SC' ? 'selected':'' }} value="SC">Seychelles
                </option>
                <option {{ $personProfile->country === 'SD' ? 'selected':'' }} value="SD">Sudán
                </option>
                <option {{ $personProfile->country === 'SE' ? 'selected':'' }} value="SE">Suecia
                </option>
                <option {{ $personProfile->country === 'SG' ? 'selected':'' }} value="SG">Singapur
                </option>
                <option {{ $personProfile->country === 'SH' ? 'selected':'' }} value="SH">Santa Elena
                </option>
                <option {{ $personProfile->country === 'SI' ? 'selected':'' }} value="SI">Eslovenia
                </option>
                <option {{ $personProfile->country === 'SJ' ? 'selected':'' }} value="SJ">Islas Svalbard y Jan Mayen
                </option>
                <option {{ $personProfile->country === 'SK' ? 'selected':'' }} value="SK">Eslovaquia
                </option>
                <option {{ $personProfile->country === 'SL' ? 'selected':'' }} value="SL">Sierra Leona
                </option>
                <option {{ $personProfile->country === 'SM' ? 'selected':'' }} value="SM">San Marino
                </option>
                <option {{ $personProfile->country === 'SN' ? 'selected':'' }} value="SN">Senegal
                </option>
                <option {{ $personProfile->country === 'SO' ? 'selected':'' }} value="SO">Somalia
                </option>
                <option {{ $personProfile->country === 'SR' ? 'selected':'' }} value="SR">Surinam
                </option>
                <option {{ $personProfile->country === 'ST' ? 'selected':'' }} value="ST">Santo Tomé y Príncipe
                </option>
                <option {{ $personProfile->country === 'SV' ? 'selected':'' }} value="SV">El Salvador
                </option>
                <option {{ $personProfile->country === 'SY' ? 'selected':'' }} value="SY">Siria
                </option>
                <option {{ $personProfile->country === 'SZ' ? 'selected':'' }} value="SZ">Suazilandia
                </option>
                <option {{ $personProfile->country === 'TC' ? 'selected':'' }} value="TC">Islas Turcas y Caicos
                </option>
                <option {{ $personProfile->country === 'TD' ? 'selected':'' }} value="TD">Chad
                </option>
                <option {{ $personProfile->country === 'TF' ? 'selected':'' }} value="TF">Territorios Australes Franceses
                </option>
                <option {{ $personProfile->country === 'TG' ? 'selected':'' }} value="TG">Togo
                </option>
                <option {{ $personProfile->country === 'TH' ? 'selected':'' }} value="TH">Tailandia
                </option>
                <option {{ $personProfile->country === 'TH' ? 'selected':'' }} value="TH">Tanzania
                </option>
                <option {{ $personProfile->country === 'TJ' ? 'selected':'' }} value="TJ">Tayikistán
                </option>
                <option {{ $personProfile->country === 'TK' ? 'selected':'' }} value="TK">Tokelau
                </option>
                <option {{ $personProfile->country === 'TL' ? 'selected':'' }} value="TL">Timor-Leste
                </option>
                <option {{ $personProfile->country === 'TM' ? 'selected':'' }} value="TM">Turkmenistán
                </option>
                <option {{ $personProfile->country === 'TN' ? 'selected':'' }} value="TN">Túnez
                </option>
                <option {{ $personProfile->country === 'TO' ? 'selected':'' }} value="TO">Tonga
                </option>
                <option {{ $personProfile->country === 'TR' ? 'selected':'' }} value="TR">Turquía
                </option>
                <option {{ $personProfile->country === 'TT' ? 'selected':'' }} value="TT">Trinidad y Tobago
                </option>
                <option {{ $personProfile->country === 'TV' ? 'selected':'' }} value="TV">Tuvalu
                </option>
                <option {{ $personProfile->country === 'TW' ? 'selected':'' }} value="TW">Taiwán
                </option>
                <option {{ $personProfile->country === 'UA' ? 'selected':'' }} value="UA">Ucrania
                </option>
                <option {{ $personProfile->country === 'UG' ? 'selected':'' }} value="UG">Uganda
                </option>
                <option {{ $personProfile->country === 'US' ? 'selected':'' }} value="US">Estados Unidos de América
                </option>
                <option {{ $personProfile->country === 'UY' ? 'selected':'' }} value="UY">Uruguay
                </option>
                <option {{ $personProfile->country === 'UZ' ? 'selected':'' }} value="UZ">Uzbekistán
                </option>
                <option {{ $personProfile->country === 'VA' ? 'selected':'' }} value="VA">Ciudad del Vaticano
                </option>
                <option {{ $personProfile->country === 'VC' ? 'selected':'' }} value="VC">San Vicente y las Granadinas
                </option>
                <option {{ $personProfile->country === 'VE' ? 'selected':'' }} value="VE">Venezuela
                </option>
                <option {{ $personProfile->country === 'VG' ? 'selected':'' }} value="VG">Islas Vírgenes Británicas
                </option>
                <option {{ $personProfile->country === 'VI' ? 'selected':'' }} value="VI">Islas Vírgenes de los Estados Unidos de América
                </option>
                <option {{ $personProfile->country === 'VN' ? 'selected':'' }} value="VN">Vietnam
                </option>
                <option {{ $personProfile->country === 'VU' ? 'selected':'' }} value="VU">Vanuatu
                </option>
                <option {{ $personProfile->country === 'WF' ? 'selected':'' }} value="WF">Wallis y Futuna
                </option>
                <option {{ $personProfile->country === 'WS' ? 'selected':'' }} value="WS">Samoa
                </option>
                <option {{ $personProfile->country === 'YE' ? 'selected':'' }} value="YE">Yemen
                </option>
                <option {{ $personProfile->country === 'YT' ? 'selected':'' }} value="YT">Mayotte
                </option>
                <option {{ $personProfile->country === 'ZA' ? 'selected':'' }} value="ZA">Sudáfrica
                </option>
            </select>

            @if ($errors->has('pais'))
            <small class="text-danger" role="">
                <strong>{{ $errors->first('pais') }}</strong>
            </small> @endif
        </div>
    </div>
    <div class="col-md-4 col-lg-3">

        <div class="form-group{{ $errors->has('estado_departamento') ? ' has-danger' : '' }}">
            <label class="form-control-label text-primary" for="input-estado_departamento">{{ __('Estado / Departamento') }}</label>
            <input type="text" name="estado_departamento" id="input-estado_departamento" class="form-control  form-control-alternative{{ $errors->has('estado_departamento') ? ' is-invalid' : '' }}" placeholder="{{ __('Estado / Departamento') }}" value="{{ old('estado_departamento', @$personProfile->state) }}"> @if ($errors->has('estado_departamento'))
            <span class="invalid-feedback" role="alert">
               <strong>{{ $errors->first('estado_departamento') }}</strong>
           </span> @endif
       </div>
   </div>
   <div class="col-md-4 col-lg-3">

    <div class="form-group{{ $errors->has('ciudad') ? ' has-danger' : '' }}">
        <label class="form-control-label text-primary" for="input-ciudad">{{ __('Ciudad') }}</label>
        <input type="text" name="ciudad" id="input-ciudad" class="form-control  form-control-alternative{{ $errors->has('ciudad') ? ' is-invalid' : '' }}" placeholder="{{ __('Ciudad') }}" value="{{ old('ciudad', @$personProfile->city) }}"> @if ($errors->has('ciudad'))
        <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('ciudad') }}</strong>
      </span> @endif
  </div>
</div>
<div class="col-md-4 col-lg-3">

    <div class="form-group{{ $errors->has('codigo_postal') ? ' has-danger' : '' }}">
        <label class="form-control-label text-primary " for="input-codigo_postal">{{ __('Código Postal') }}</label>
        <input type="text" name="codigo_postal" id="input-codigo_postal" class="form-control  form-control-alternative {{ $errors->has('codigo_postal') ? ' is-invalid' : '' }}" placeholder="{{ __('Código Postal') }}" value="{{ old('codigo_postal', @$personProfile->zip_code) }}"> @if ($errors->has('codigo_postal'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('codigo_postal') }}</strong>
        </span> @endif
    </div>
</div>
</div>
<div class="row">
    <div class="col-md-8 col-lg-5">
        <div class="form-group{{ $errors->has('direccion') ? ' has-danger' : '' }}">
            <label class="form-control-label text-primary" for="input-direccion">{{ __('Dirección de Domicilio, Avenida, Calle, Edif o Casa') }}</label>
            <input type="text" name="direccion" id="input-direccion" class="form-control  form-control-alternative{{ $errors->has('direccion') ? ' is-invalid' : '' }}" placeholder="{{ __('Dirección de Domicilio, Avenida, Calle, Edif o Casa') }}" value="{{ old('direccion', @$personProfile->street) }}"> @if ($errors->has('direccion'))
            <span class="invalid-feedback" role="alert">
               <strong>{{ $errors->first('direccion') }}</strong>
           </span> @endif
       </div>
   </div>
   <div class="col-md-4 col-lg-3">
    <div class="form-group">
        <label class="text-primary">Tipo</label>
        <select name="tipo" class="custom-select {{ $errors->has('tipo') ? ' is-invalid' : '' }} {{ $personProfile->approval_status === 1 ||
            $personProfile->approval_status === 2 ? '__disabled_select' : '' }}">
            <strong>{{ $errors->first('tipo') }}</strong>
            <option value="Casa" {{ $personProfile->address_place_type === 'Casa' ? 'selected':''}} >Casa
            </option>
            <option value="Edificio" {{ $personProfile->address_place_type === 'Edificio' ? 'selected':''}} >Edificio
            </option>
        </select>
    </div>
</div>
<div class="col-md-4 col-lg-2">

    <div class="form-group{{ $errors->has('piso') ? ' has-danger' : '' }}">
        <label class="form-control-label text-primary" for="input-piso">{{ __('Planta / Piso') }}</label>
        <input type="text" name="piso" id="input-piso" class="form-control  form-control-alternative{{ $errors->has('piso') ? ' is-invalid' : '' }}" placeholder="{{ __('Planta / Piso') }}" value="{{ old('piso', @$personProfile->address_floor) }}"> @if ($errors->has('piso'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('piso') }}</strong>
        </span> @endif
    </div>
</div>
<div class="col-md-4 col-lg-2">

    <div class="form-group{{ $errors->has('habitacion') ? ' has-danger' : '' }}">
        <label class="form-control-label text-primary" for="input-habitacion">{{ __('Habitación') }}</label>
        <input type="text" name="habitacion" id="input-habitacion" class="form-control  form-control-alternative{{ $errors->has('habitacion') ? ' is-invalid' : '' }}" placeholder="{{ __('Habitación') }}" value="{{ old('habitacion', @$personProfile->address_place_number) }}"> @if ($errors->has('habitacion'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('habitacion') }}</strong>
        </span> @endif
    </div>
</div>
</div>
<div class="text-center text-md-right mt-4">
    <button class="btn btn-secondary btn-pill mb-3 px-3 px-md-4">Guardar cambios</button>
</div>
</div>
</div>
@endif {{-- perfil fin --}} 
</form>

</div>



<div class="tab-pane fade" id="tab_identity" role="tabpanel" aria-labelledby="identity-info-tab">

    <form action="{{URL::to('/user-verificacion-identidad')}}" enctype="multipart/form-data" method="post" class="px-lg-5 p-2" style="" id="">
        {{ csrf_field() }}

        @if($personProfile->identity_verified === 1)

        <div class="container">
           <h6 class="text-primary font-weight-bold mb-3">Verificación de indentidad</h6>
           <div class="mb-4">
            <img src="{{ asset('img/landing/success-icon.svg') }}" alt=""> <span class="text-success">Verificado y completado por favor continua con el siguiente paso  </span>
        </div>
    </div>
    @else
    <input type="hidden" class="form-control" value="{{$personProfile->id}}" name="UserPersonProfile[id]">
    <input type="hidden" class="form-control" value="{{Auth::user()->id}}" name="User[id]">
    <h6 class="text-primary font-weight-bold mb-3">Verificación de identidad</h6>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-primary">Tipo de documento</label>
                        <select id="tipo_de_documento" name="tipo_de_documento" class="custom-select">
                            <option value=""> -- Seleccione una opción --</option>
                            <option value="Cédula" {{ $personProfile->id_type === 'Cédula' ? 'selected':''}} >Cédula
                            </option>
                            <option value="Licencia de conducir" {{ $personProfile->id_type === 'Licencia de conducir' ? 'selected':''}} >Licencia de conducir
                            </option>
                            <option value="Pasaporte" {{ $personProfile->id_type === 'Pasaporte' ? 'selected':''}} >Pasaporte
                            </option>
                            <option value="Permiso de trabajo" {{ $personProfile->id_type === 'Permiso de trabajo' ? 'selected':''}} >Permiso de trabajo
                            </option>
                        </select>



                   @if ($errors->has('tipo_de_documento'))
                                                                          <div class="text-danger"  >
                                                                              <strong>{{ $errors->first('tipo_de_documento') }}</strong>
                                                                          </div>
                                                                          @endif

                    </div>
                </div>
                <div class="col-md-3">

                    <div class="form-group{{ $errors->has('numero_de_documento') ? ' has-danger' : '' }}">
                        <label class="form-control-label  text-primary" for="input-numero_de_documento">{{ __('Numero de documento') }}</label>
                        <input type="text" name="numero_de_documento" id="input-numero_de_documento" class="form-control  form-control-alternative{{ $errors->has('numero_de_documento') ? ' is-invalid' : '' }}" placeholder="{{ __('Numero de documento') }}" value="{{ old('numero_de_documento', @$personProfile->id_number) }}"> @if ($errors->has('numero_de_documento'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('numero_de_documento') }}</strong>
                        </span> @endif
                    </div>
                </div>
                <div class="col-md-3">
                    @php $idCreationDate = $personProfile->id_creation_date ? Carbon::createFromFormat( 'Y-m-d H:i:s', $personProfile->id_creation_date )->format('Y-m-d') : '' @endphp

                    <div class="form-group{{ $errors->has('fecha_de_emision') ? ' has-danger' : '' }}">
                        <label class="form-control-label  text-primary" for="input-fecha_de_emision">{{ __('Fecha de emisión') }}</label>
                        <input type="date" name="fecha_de_emision" id="input-fecha_de_emision" class="form-control  form-control-alternative{{ $errors->has('fecha_de_emision') ? ' is-invalid' : '' }}" placeholder="{{ __('Fecha de emisión') }}" value="{{ old('fecha_de_emision', @$idCreationDate) }}"> @if ($errors->has('fecha_de_emision'))
                        <span class="invalid-feedback" role="alert">
                         <strong>{{ $errors->first('fecha_de_emision') }}</strong>
                     </span> @endif
                 </div>
             </div>
             <div class="col-md-3">
                @php $idExpirationDate = $personProfile->id_expiration_date ? Carbon::createFromFormat( 'Y-m-d H:i:s', $personProfile->id_expiration_date )->format('Y-m-d') : '' @endphp

                <div class="form-group{{ $errors->has('fecha_de_caducidad') ? ' has-danger' : '' }}">
                    <label class="form-control-label text-primary" for="input-fecha_de_caducidad">{{ __('Fecha de caducidad') }}</label>
                    <input type="date" name="fecha_de_caducidad" id="input-fecha_de_caducidad" class="form-control  form-control-alternative{{ $errors->has('fecha_de_caducidad') ? ' is-invalid' : '' }}" placeholder="{{ __('Fecha de caducidad') }}" value="{{ old('fecha_de_caducidad', @$idExpirationDate) }}"> @if ($errors->has('fecha_de_caducidad'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('fecha_de_caducidad') }}</strong>
                    </span> @endif
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6 __big_id">
                <div class="body-bg-color p-4 rounded-lg mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex flex-row flex-md-column justify-content-center align-items-md-center text-md-center">
                                <div class="__image_label mb-2 avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3" style="padding: 0; min-height: 209px">
                                    @if ($personProfile->identity_verified  === 0)
                                    <img src="/img/landing/id_fact.svg" alt="Prueba de identidad" class="img-fluid" style="max-height: 209px" id="__selfie_id_img"> @else
                                    <img src="{{$personProfile->selfie_id}}" alt="Upload selfie with ID" class="img-fluid" style="max-height: 209px" style="max-height: 185px" id="__selfie_id_img"> @endif
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="text-center">
                                <span>Prueba de Identidad  </span> @if ($errors->has('selfie_id'))
                                <div class="text-danger" role=" ">
                                    <strong>{{ $errors->first('selfie_id') }}</strong>
                                </div>
                                @endif @if($personProfile->identity_verified === 0)
                                <br>
                                <a href="#" class="btn btn-light btn-sm" data-toggle="modal" data-target="#upload-identity">
                                    Cargar foto
                                </a> @endif
                            </div>
                        </div>


                        @if($personProfile->identity_verified === 0)
                        <!-- Identity proof modal -->
                        <div class="modal fade" id="upload-identity" tabindex="-1" role="dialog" aria-labelledby="verify-identity-modal" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title text-primary" id="verify-identity-modal">
                                            Cargar prueba de identidad

                                        </h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body pb-4">
                                        <div class="row">
                                            <div class="col-md-8">

                                                           <div class="alert alert-danger font-14 lh-125 mb-3">
                                                    <div class="media">
                                                        <img src="/img/landing/info-icon.svg" class="alert-icon mr-3">
                                                        <div class="media-body">
                                                            <h6 class="alert-heading font-weight-bold">
                                                                En la fotografía debe aparecer:
                                                            </h6>
                                                            Usted debe tomarse una Selfie sosteniendo su documento de Identidad original legible y debe realizar un escrito a mano que diga solo para ser usado por American Kryptos Bank, con la fecha del dia, Ejemplo Mes/Dia/Año.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="body-bg-color p-3 rounded-lg mb-3">
                                                    <h6 class="text-primary">Ten en cuenta esto
                                                    cuando cargues tu foto de identidad</h6>
                                                    <ul class="text-primary font-14 lh-125 mb-0">
                                                        <li>Imite la postura de la ilustración.
                                                        </li>
                                                        <li>No usar sombrero ni nada que te cubra la cabeza.
                                                        </li>
                                                        <li>No usar lentes.</li>
                                                        <li>Mostrar el rostro completo.</li>
                                                        <li>Debe ser reciente.</li>
                                                        <li>No debe estar pixelada.</li>
                                                        <li>No debe estar borrosa.</li>
                                                    </ul>
                                                </div>

                                     

                                                <div class="alert alert-warning font-14 lh-125 mb-0">
                                                    <div class="media">
                                                        <img src="/img/landing/warning-icon.svg" class="alert-icon mr-3">
                                                        <div class="media-body">
                                                            Estos aspectos serán tomados en cuenta por el equipo de revisión y verificación a la hora de abrir tu cuenta con nosotros.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="d-flex flex-row flex-md-column justify-content-start align-items-md-center text-md-center mt-4 mt-md-0">
                                                    <h6 class="font-14">Prueba de identidad</h6>

                                                    <div class="avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3" style="min-height: 238px; padding: 0">
                                                        <img src="/img/landing/selfie-demo.jpg" alt="Prueba de identidad" class="img-fluid" style="height: 100%; max-height: 238px" id="__selfie_id_img_too">
                                                    </div>
                                                    <div class="font-12 text-muted">
                                                        Tamaño máximo 4mb.
                                                    </div>
                                                    <div class="flex-grow-1 mt-2">
                                                        <label class="btn btn-light btn-sm">
                                                            Cargar foto
                                                            <input type="file" class="form-control" id="__selfie_id_input" accept="image/*" style="width: 0; height: 0; padding: 0;" value="{{$personProfile->selfie_id}}" name="selfie_id" @if ($personProfile->id_confirmation  === null) @endif>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between justify-content-md-end">
                                        <a href="#" type="button" class="btn btn-link text-muted btn-pill btn-sm px-3" style="color: #b3b3b3 !important" data-dismiss="modal">
                                            Cancelar
                                        </a>
                                        <a href="#" type="button" class="btn btn-primary btn-pill btn-sm px-3" data-dismiss="modal">
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
            <div class="col-md-6 __big_id">
                <div class="body-bg-color p-4 rounded-lg mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex flex-row flex-md-column justify-content-center align-items-md-center text-md-center">
                                <div class="avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3 __image_label __v_center mb-2" style="padding: 0; min-height: 209px">
                                    @if ($personProfile->identity_verified === 0)
                                    <img src="/img/landing/id_f_b.svg" alt="Documento de identidad" class="img-fluid" style="max-height: 209px" id="__id_img"> @else
                                    <img src="{{$personProfile->id_confirmation}}" alt="Upload ID" class="img-fluid" style="max-height: 209px" style="max-height: 185px" id="__id_img">  
                                    <img src="{{$personProfile->id_confirmation_back}}" alt="Upload ID" class="img-fluid" style="max-height: 209px" style="max-height: 185px" id="__id_img"> @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                            <span>Documento de Identidad</span> @if ($errors->has('id_confirmation'))
                            <div class="text-danger" role=" ">
                                <strong>{{ $errors->first('id_confirmation') }}</strong>
                            </div>
                            @endif 
                            @if(( $personProfile->identity_verified === 0))
                            <br>

                            <a href="#" class="btn btn-light btn-sm" data-toggle="modal" data-target="#upload-identity-doc">
                              Cargar foto
                          </a> @endif
                      </div>
                      </div>
                  </div>
              </div>
              @if( $personProfile->identity_verified === 0)
              <!-- Identity document modal -->
              <div class="modal fade" id="upload-identity-doc" tabindex="-1" role="dialog" aria-labelledby="verify-identityDoc-modal" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-primary" id="verify-identityDoc-modal">Cargar documento de
                            identidad</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body pb-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="body-bg-color p-3 rounded-lg mb-3">
                                        <h6 class="text-primary">
                                            Ten en cuenta esto cuando cargues tu foto de
                                            identidad
                                        </h6>
                                        <ul class="text-primary font-14 lh-125 mb-0">
                                            <li>No debe estar pixelada.</li>
                                            <li>No debe estar borrosa.</li>
                                            <li>El documento debe estar vigente.</li>
                                            <li>Debe estar relacionado con el país seleccionado
                                            </li>
                                            <li>Se admiten cédulas, pasaportes o licencias de conducir (Solo USA)
                                            </li>
                                        </ul>
                                                                    {{--
                                                                        <div--}} {{-- class="text-primary font-14 mt-3 font-weight-bold">--}} {{-- Se debe cargar la fotografía del frente y del--}} {{-- respaldo de documento de identificación.--}} {{-- </div>--}}
                                                                    </div>
                                                                    <div class="alert alert-warning font-14 lh-125 mb-0">
                                                                        <div class="media">
                                                                            <img src="/img/landing/warning-icon.svg" class="alert-icon mr-3">
                                                                            <div class="media-body">
                                                                                Estos aspectos serán tomados en cuenta por el equipo de revisión y verificación a la hora de abrir tu cuenta con nosotros.
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>




                                                                <div class="col-md-6">
                                                                    <div class="d-flex flex-row flex-md-column justify-content-start align-items-md-center text-md-center mt-4 mt-md-0">
                                                                        <div class="avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3" style="padding: 0; min-height: 238px">
                                                                            <img src="/img/landing/id-front.svg" alt="Documento de identidad" class="img-fluid" style="height: 100%; max-height: 238px" id="__id_img_too">
                                                                        </div>
                                                                        <div class="flex-grow-1 mt-2">
                                                                            <h6 class="font-14">Documento de identidad frontal</h6>
                                                                            <div class="font-12 text-muted">Tamaño máximo 4mb.
                                                                            </div>
                                                                            <label class="btn btn-light btn-sm rounded-0">
                                                                                Cargar foto
                                                                                <input type="file" class="form-control" id="__id_input" accept="image/*" style="display: none;" value="{{$personProfile->id_confirmation}}" name="id_confirmation" @if ($personProfile->id_confirmation === null) @endif>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>



                                                                <div class="col-md-6">
                                                                    <div class="d-flex flex-row flex-md-column justify-content-start align-items-md-center text-md-center mt-4 mt-md-0">
                                                                        <div class="avatar-square-fluid avatar-background mr-3 mr-md-0 mb-md-3" style="padding: 0; min-height: 238px">
                                                                            <img src="/img/landing/id-reverse.svg" alt="Documento de identidad reverso" class="img-fluid" style="height: 100%; max-height: 238px" id="__id_img_too2">
                                                                        </div>
                                                                        <div class="flex-grow-1 mt-2">
                                                                            <h6 class="font-14">Documento de identidad reverso</h6>
                                                                            <div class="font-12 text-muted">Tamaño máximo 4mb.
                                                                            </div>
                                                                            <label class="btn btn-light btn-sm rounded-0">
                                                                                Cargar foto
                                                                                <input type="file" class="form-control" id="__id_input2" accept="image/*" style="display: none;" value="{{$personProfile->id_confirmation}}" name="id_confirmation_back" @if ($personProfile->id_confirmation === null) @endif>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between justify-content-md-end">
                                                            <a href="#" type="button" class="btn btn-link text-muted btn-pill btn-sm px-3" style="color: #b3b3b3 !important" data-dismiss="modal">
                                                                Cancelar
                                                            </a>
                                                            <a href="#" type="button" class="btn btn-primary btn-pill btn-sm px-3" data-dismiss="modal">
                                                                Guardar cambios
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-center text-md-right mt-4">
                                          <div class="text-primary">   <input name="escrito_en_mano" type="checkbox" name="" value="1"> <a href="#" data-toggle="modal" data-target="#upload-identity"> Confirmo que cargue el escrito de la prueba de identidad</a></div>

                                             @if ($errors->has('escrito_en_mano'))
                                                                          <div class="text-danger font-weight-bold"  >
                                                                              <strong>{{ $errors->first('escrito_en_mano') }}</strong>
                                                                          </div>
                                                                          @endif
                                        <button id="guardar" class="btn btn-secondary btn-pill px-3 px-md-4 mt-2">Guardar cambios</button>
                                    </div>
                                </div>
                            </div>

                            @endif {{-- identity fin --}}
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Verify phone number modal -->
    <div class="modal fade" id="verify-phone" tabindex="-1" role="dialog" aria-labelledby="verify-phone-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-primary" id="verify-phone-modal">Verificación de tu celular</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="bg-primary text-center text-white p-4">
                    <svg class="mb-3" width="135" height="94" viewBox="0 0 135 94" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M51.6611 41.4677C48.9655 35.4133 51.6884 28.32 57.7428 25.6243L104.334 4.88078C110.388 2.18517 117.481 4.90804 120.177 10.9625L129.125 31.0605C131.821 37.1149 129.098 44.2083 123.044 46.9039L76.4527 67.6474C70.3983 70.3431 63.3049 67.6202 60.6093 61.5657L51.6611 41.4677Z" fill="#FFE37B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M105.554 7.62142L58.963 28.365C54.4222 30.3867 52.38 35.7067 54.4017 40.2475L63.35 60.3455C65.3717 64.8864 70.6917 66.9285 75.2325 64.9068L121.823 44.1632C126.364 42.1415 128.406 36.8215 126.385 32.2807L117.436 12.1827C115.415 7.64186 110.095 5.59971 105.554 7.62142ZM57.7428 25.6243C51.6884 28.32 48.9655 35.4133 51.6611 41.4677L60.6093 61.5657C63.3049 67.6202 70.3983 70.3431 76.4527 67.6474L123.044 46.9039C129.098 44.2083 131.821 37.1149 129.125 31.0605L120.177 10.9625C117.481 4.90804 110.388 2.18517 104.334 4.88078L57.7428 25.6243Z" fill="#FFBE1B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M62.419 34.1252C62.5585 33.3086 63.3335 32.7597 64.1501 32.8991L90.9309 37.4721L105.452 14.5102C105.895 13.81 106.822 13.6014 107.522 14.0442C108.222 14.487 108.431 15.4135 107.988 16.1137L92.3974 40.7659L63.6451 35.8563C62.8285 35.7169 62.2796 34.9419 62.419 34.1252Z" fill="#FFBE1B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M85.0623 37.049C85.8357 37.3459 86.222 38.2135 85.9251 38.9869L79.59 55.4905C79.2931 56.2639 78.4255 56.6502 77.6521 56.3533C76.8787 56.0564 76.4924 55.1888 76.7893 54.4154L83.1244 37.9118C83.4213 37.1384 84.2889 36.7521 85.0623 37.049Z" fill="#FFBE1B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M94.5436 32.8276C94.2468 33.601 94.633 34.4687 95.4065 34.7655L111.91 41.1006C112.683 41.3975 113.551 41.0112 113.848 40.2378C114.145 39.4644 113.758 38.5968 112.985 38.2999L96.4816 31.9648C95.7082 31.6679 94.8405 32.0542 94.5436 32.8276Z" fill="#FFBE1B"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.8501 59.4707C3.62546 58.9661 3.85237 58.375 4.3569 58.1504L39.0716 42.6944C39.5762 42.4698 40.1673 42.6967 40.3919 43.2012C40.6165 43.7057 40.3896 44.2969 39.8851 44.5215L5.17038 59.9775C4.66584 60.2021 4.07473 59.9752 3.8501 59.4707Z" fill="#6857E5"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.8662 66.3659C17.6416 65.8613 17.8685 65.2702 18.373 65.0456L43.9523 53.657C44.4568 53.4323 45.0479 53.6592 45.2726 54.1638C45.4972 54.6683 45.2703 55.2594 44.7658 55.4841L19.1865 66.8727C18.682 67.0973 18.0908 66.8704 17.8662 66.3659Z" fill="#6857E5"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M30.0557 74.0745C29.831 73.57 30.0579 72.9788 30.5625 72.7542L48.8334 64.6195C49.3379 64.3948 49.929 64.6217 50.1537 65.1263C50.3783 65.6308 50.1514 66.2219 49.6469 66.4466L31.3759 74.5813C30.8714 74.8059 30.2803 74.579 30.0557 74.0745Z" fill="#6857E5"></path>
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
                            <input id="__verification_code_input" type="text" class="form-control form-control-lg text-center font-weight-bold" style="letter-spacing: 5px">
                            <button class="btn btn-outline-success px-4 mt-2" id="__verify_code">
                                Verificar
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-info font-14 lh-125 mb-0">
                        <div class="media">
                            <img src="/img/landing/info-icon.svg" class="alert-icon mr-3">
                            <div class="media-body">
                                Recuerda que este número de celular se usará en tu cuenta personal, para hacer cambios deberás <a href="/contact" class="alert-link">contactar</a> con nuestro equipo.
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

    @endsection 

    @section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/js/countrySelect.min.js" integrity="sha256-CJtHxCZRQpS4Q4X7X4T8i/PcJC3ZKT0rnQ25bX4yM5Y=" crossorigin="anonymous"></script>


    <script>


        $('#__no_second_name').on('click', function(event) {
            event.preventDefault();

            $('#check_segundo_nombre').val('true');

            $('#segundo_nombre').prop( "disabled", true );;
            /* Act on the event */
        });

           $('#__no_second_apellido').on('click', function(event) {
            event.preventDefault();

            $('#check_segundo_apellido').val('true');

            $('#segundo_apellido').prop( "disabled", true );;
            /* Act on the event */
        });


        var hash = document.location.hash;
        var prefix = "tab_";

        if (hash) {

         if(window.location.hash != "") {
          $('a[href="' + window.location.hash + '"]').click()
      }
  } 



  var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

  var input = document.querySelector("#phone"),

  local = document.querySelector("#local"),
  errorMsg = document.querySelector("#error-msg"),
  validMsg = document.querySelector("#valid-msg");


  try {
      var iti_local = window.intlTelInput(local, {
        initialCountry: "auto",
        hiddenInput: "phone[]",

        geoIpLookup: function(success, failure) {
            $.get("https://ipinfo.io?token=b3be01b057532b", function() {}, "jsonp").always(function(resp) {
              var countryCode = (resp && resp.country) ? resp.country : "";
              success(countryCode);
              $('#gps').val(JSON.stringify(resp));
              console.log(resp);
          });
        },
           // onlyCountries: ['es', 'pt', 'us', 've','co','pe','ar','cl','ec','pa','de','fr'],
           preferredCountries: [ 'us', 'co', 've', 'es'],
           utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/utils.js"
       });

  }
  catch(error) {
      console.error(error);
  // expected output: ReferenceError: nonExistentFunction is not defined
  // Note - error messages will vary depending on browser
}




try {
  var iti = window.intlTelInput(input, {
    initialCountry: "auto",
    hiddenInput: "phone[]",

    geoIpLookup: function(success, failure) {
        $.get("https://ipinfo.io?token=b3be01b057532b", function() {}, "jsonp").always(function(resp) {
          var countryCode = (resp && resp.country) ? resp.country : "";
          success(countryCode);
          console.log(resp);



      });
    },
            //onlyCountries: ['es', 'pt', 'us', 've','co','pe','ar','cl','ec','pa','de','fr'],
            preferredCountries: [ 'us', 'co', 've', 'es'],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/utils.js"
        });
}
catch(error) {
  console.error(error);
  // expected output: ReferenceError: nonExistentFunction is not defined
  // Note - error messages will vary depending on browser
}


function checkLocal(){
 if (iti_local.isValidNumber()) {


    return true

} else {
    alert('Instroduce un teléfono fijo valido')
    return false;

}
}


function checkPhone(){
 if (iti.isValidNumber()) {


    return true

} else {
    alert('Instroduce un celular valido')
    return false;

}
}


@if($errors->has('selfie'))

$('#upload-avatar').modal({
    show: true
});

@endif




        // Javascript to enable link to tab




    </script>

    <script>

       @if (old('pais') )

       $("#person-select-country option[value='{{old('pais')}}']").attr("selected", true);
       @endif




       @if (old('tipo_de_documento') )
       $("#tipo_de_documento option[value='{{old('tipo_de_documento')}}']").attr("selected", true);
       @endif  


   </script>

   @endsection