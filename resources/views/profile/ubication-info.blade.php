@extends('layouts.mvp-layout-internal')

@section('content')

  <main>
      <div class="container mt-md-n5">
          <div class="row">
              <div class="col-xl-10 mx-auto px-0 px-md-3">
                  <div class="card shadow-none mb-4 wow fadeInUp" style="z-index: 10">
                      <!-- navigation mobile -->
                      <div class="btn-group flex-wrap profileSettingsNav--mobile d-md-none sticky-top" role="group" aria-label="Profile Navigation Tabs">
                          <a href="{{URL::to('/user-info')}}" class="btn btn-primary d-flex align-items-center justify-content-center py-sm-2 rounded-0">
                              <span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Info. General</span>
                          </a>
                          <a href="{{URL::to('/ubication-info')}}" class="btn btn-primary d-flex align-items-center justify-content-center py-sm-2 rounded-0 active">
                              <span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Info. Ubicación</span>
                          </a>
                          <a href="{{URL::to('/business-info')}}" class="btn btn-primary d-flex align-items-center justify-content-center py-sm-2 rounded-0">
                            <span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Perfil Empresa
                              <span class="badge badge-danger badge-pill">
                                @if($personProfile->selfie === null || $personProfile->street === null)
                                  <i class="fa fa-lock"></i>
                                @endif
                              </span>
                            </span>
                          </a>
                          <a href="{{URL::to('/change-password')}}" class="btn btn-primary d-flex align-items-center justify-content-center py-sm-2 rounded-0">
                              <span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Contraseña</span>
                          </a>
                          <a href="{{URL::to('/auth-config')}}" class="btn btn-primary d-flex align-items-center justify-content-center py-sm-2 rounded-0">
                              <span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">2F Auth.</span>
                          </a>
                          <a href="{{URL::to('/delete-account')}}" class="btn btn-danger d-flex align-items-center justify-content-center py-sm-2 rounded-0">
                              <span class="d-block d-md-inline font-mobile-12 lh-125 va-middle">Eliminar cuenta</span>
                          </a>
                      </div>

                      <div class="py-4">
                          <div class="row no-gutters">
                              <div class="col-md-4">
                                <ul class="nav flex-column profileSettingsNav d-none d-md-flex">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{URL::to('/user-info')}}">Información General</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="{{URL::to('/ubication-info')}}">Información de Ubicación</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{URL::to('/business-info')}}">Perfil de Empresa
                                          @if($personProfile->selfie === null || $personProfile->street === null)
                                            <span class="badge badge-danger badge-pill float-right mt-1" data-toggle="tooltip" title="Para registrar un perfil de empresa primero debe haber llenado todos los campos en Informacion General e Información de Ubicación."><i class="fa fa-lock"></i>
                                            </span>
                                          @endif
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{URL::to('/change-password')}}">Contraseña</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{URL::to('/auth-config')}}">Two Factor Authentication</a>
                                    </li>
                                    <!--  <li class="nav-item">
                                          <a class="nav-link text-danger" href="{{URL::to('/delete-account')}}">Eliminar cuenta</a>
                                      </li> -->
                                </ul>

                                  <hr class="my-4 d-none d-md-block">

                                  @include('profile.user-profile-progression')

                              </div>
                              <div class="col-md-8 px-3 px-md-4 px-xl-5">
                                  <div class="card-body py-2 px-0 animated fadeIn">
                                      <form action="{{URL::to('/ubication-info')}}"
                                            method="post">
                                          {{ csrf_field() }}
                                          <input type="hidden" class="form-control" value="{{$personProfile->id}}"
                                                 name="UserPersonProfile[id]">
                                          <input type="hidden" class="form-control" value="{{Auth::user()->id}}"
                                                 name="User[id]">
                                          <h5 class="text-primary font-weight-bold mb-3 mb-md-4">Información de Ubicación</h5>
                                          <div class="row">
                                              <div class="col-sm-6">
                                                  <div class="form-group">
                                                      <label for="pais" class="text-primary">País</label>
                                                      <select name="UserPersonProfile[country]"
                                                              id="person-select-country"
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
                                          </div>
                                          <div class="row">
                                              <div class="col-sm-12">
                                                  <div class="form-group">
                                                      <label for="" class="text-primary">Dirección</label>
                                                      <input id="person-direccion"
                                                             name="UserPersonProfile[street]"
                                                             value="{{$personProfile->street}}"
                                                             type="text" class="form-control" required>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-sm-4">
                                                  <div class="form-group">
                                                      <label for="" class="text-primary">
                                                          Estado / Departamento Geográfico
                                                      </label>
                                                      <input type="text" class="form-control"
                                                             value="{{$personProfile->state}}"
                                                             name="UserPersonProfile[state]" required>
                                                  </div>
                                              </div>
                                              <div class="col-sm-4">
                                                  <div class="form-group">
                                                      <label for="" class="text-primary">Ciudad</label>
                                                      <input id="person-ciudad" type="text" class="form-control"
                                                             value="{{$personProfile->city}}"
                                                             name="UserPersonProfile[city]" required>
                                                  </div>
                                              </div>
                                              <div class="col-sm-4">
                                                  <div class="form-group">
                                                      <label for="" class="text-primary">Código Postal</label>
                                                      <input id="person-codigo-postal"
                                                             type="text"
                                                             class="form-control"
                                                             value="{{$personProfile->zip_code}}"
                                                             name="UserPersonProfile[zip_code]" required>
                                                  </div>
                                              </div>
                                          </div>

                                          <hr class="my-4">

                                          <div class="text-center text-md-left">
                                              <button class="btn btn-secondary btn-pill" type="submit">Guardar cambios</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </main>

@endsection
