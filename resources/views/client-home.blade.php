@extends('layouts.akb-client-layout')

@section('content')

<div id="mobile-navbar" class="sidebar">
    <div id="clone-desktop-nav" class="sidebar__menu">
        <a class="sidebar__link" href="/login-landing.html">Home</a>
        <!-- nav-links will clone from #desktop-navbar -->
    </div>
    <div class="sidebar__menu pt-4 mt-auto">
        <div class="px-4 font-12" style="color: rgba(255, 255, 255, .5)">American Kryptos Bank &copy; 2019</div>
    </div>
</div>

<main class="dashboard__main">
    <div class="container">
        @if(Auth::user()->personProfile->approval_status !== 1 &&
        Auth::user()->personProfile->approval_status !== 2 &&
        Auth::user()->personProfile->approval_status !== 4)
        <div class="alert alert-warning alert-dismissible fade show">
            <div class="media">
                <img src="/img/landing/warning-icon.svg" class="alert-icon mr-3">
                <div class="media-body">Obten tu perfil verificado para que puedas sacarle el mayor provecho a
                    tu cuenta. <a href="{{URL::to('/user-info')}}" class="alert-link">¡Comenzar!</a></div>



                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            @endif

            @if (tarjetaPending())


            <div class="alert alert-warning" role="alert">
                <strong>¡ {{ Auth::user()->name }}, su tarjeta esta en proceso de verificación, en pocos minutos tendras una respuesta !</strong>
            </div>

            @endif

            @if(Auth::user()->personProfile->approval_status === 3 )
            <div class="alert alert-danger alert-dismissible fade show">
                <div class="media">
                    <img src="/img/landing/danger-icon.svg" class="alert-icon mr-3">
                    <div class="media-body">Su perfil ha sido rechazado, por favor revise y actualice sus datos o pongase en contacto con soporte.
                       <a href="{{URL::to('/user-info')}}" class="alert-link">¡Actualizar!</a></div>


                       
                   </div>
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            @endif

            @if(Auth::user()->personProfile->approval_status === 4 )
            <div class="alert alert-primary alert-dismissible fade show">
                <div class="media">
                    <img src="/img/landing/warning-icon.svg" class="alert-icon mr-3">
                    <div class="media-body">{{Auth::user()->name}}, por favor ingresa en el siguiente link para completar algunas preguntas que tenemos
                    para tí. 
                        <a href="#" class="alert-link " style="text-decoration: underline;" data-toggle="modal" data-target="#upload-identity-doc">
                           Haz click aqui para responder las preguntas
                        </a>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="upload-identity-doc" tabindex="-1" role="dialog" aria-labelledby="verify-identityDoc-modal" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="{{URL::to('/upload-tier-docs/'.Auth::user()->id)}}" enctype="multipart/form-data" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" class="form-control" name="tier_id" value="{{$tier->tier_id}}">
                            <div class="modal-header">
                                <h6 class="modal-title text-primary" id="verify-identityDoc-modal">Documentos requeridos para su verificación</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body pb-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="body-bg-color p-3 rounded-lg mb-3">
                                            <h6 class="text-primary">
                                                Ten en cuenta esto cuando cargues tus documentos
                                            </h6>
                                            <ul class="text-primary font-14 lh-125 mb-0">
                                                <li>No debe estar pixelada.</li>
                                                <li>No debe estar borrosa.</li>
                                                <li>El documento debe estar vigente.</li>
                                                <li>Debe estar relacionado con el país seleccionado</li>
                                            </ul>
                                        </div>
                                        <div class="alert alert-warning font-14 lh-125 mb-0">
                                            <div class="media">
                                                <img src="/img/landing/warning-icon.svg" class="alert-icon mr-3">
                                                <div class="media-body" style="color: #000 !important;" >
                                                    Completar los siguientes campos con detenimiento, por favor enviar los documentos que se solicitan a continuación:
                                                    <br/>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $tierArray = explode(' , ', $tier->tierLevel->requirements);
                                        array_pop($tierArray);
                                    @endphp
                                    @foreach($tierArray as $tierRequeriment)
                                    <div class="col-md-12">
                                        <div class="d-flex flex-row flex-md-column justify-content-start align-items-md-center text-md-center mt-4 mt-md-0">
                                            <div class="flex-grow-1 mt-2">
                                                <h6 class="font-14">{{$tierRequeriment}}</h6>
                                                <div class="font-12 text-muted">Tamaño máximo 4mb.
                                                </div>
                                                <label class="btn btn-light btn-sm rounded-0">
                                                    Cargar Documento
                                                    <input type="file" accept="image/*" multiple="multiple" class="form-control" name="tier_doc[{{$tierRequeriment}}][]">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between justify-content-md-end">
                                <a href="#" type="button" class="btn btn-link text-muted btn-pill btn-sm px-3" style="color: #b3b3b3 !important" data-dismiss="modal">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-pill btn-sm px-3">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif



            <section class="py-4 py-md-5">
                <div class="row align-items-center">
                    <div class="col-md-5 col-lg-6">
                        <h6 class="font-13 text-uppercase text-muted">HOME</h6>
                        <h3 class="text-primary">Hola, <span class="font-weight-bold">{{Auth::user()->name}}</span></h3>
                        <p class="mb-0">¿Qué te gustaria hacer con tu cuenta de <strong>American Kryptos Bank</strong>?
                        </p>
                    </div>
                    <div class="col-md-7 col-lg-6">
                        <div class="row form-row mt-4">
                            <div class="col-4">
                                <a href="{{URL::to('/send-money')}}"
                                class="btn btn-secondary btn-block text-center font-14 py-md-3 mb-3">
                                <svg class="mb-2" width="26" height="26" viewBox="0 0 26 26" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="13" cy="13" r="12.5" stroke="white"/>
                                <mask id="path-2-inside-1" fill="white">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.8133 7.42857H18.5716V13.4995H17.3116V9.57954L8.31967 18.5714L7.42871 17.6805L16.4206 8.68858H11.8133V7.42857Z"/>
                                </mask>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.8133 7.42857H18.5716V13.4995H17.3116V9.57954L8.31967 18.5714L7.42871 17.6805L16.4206 8.68858H11.8133V7.42857Z"
                                fill="white"/>
                                <path
                                d="M11.8133 7.42857V5.42857H9.81334V7.42857H11.8133ZM18.5716 7.42857H20.5716V5.42857H18.5716V7.42857ZM18.5716 13.4995V15.4995H20.5716V13.4995H18.5716ZM17.3116 13.4995H15.3116V15.4995H17.3116V13.4995ZM17.3116 9.57954H19.3116V4.75112L15.8973 8.16533L17.3116 9.57954ZM8.31967 18.5714L6.90546 19.9856L8.31967 21.3999L9.73389 19.9856L8.31967 18.5714ZM7.42871 17.6805L6.0145 16.2663L4.60028 17.6805L6.0145 19.0947L7.42871 17.6805ZM16.4206 8.68858L17.8348 10.1028L21.249 6.68858H16.4206V8.68858ZM11.8133 8.68858H9.81334V10.6886H11.8133V8.68858ZM17.3116 15.4995H18.5716V11.4995H17.3116V15.4995ZM6.0145 19.0947L6.90546 19.9856L9.73389 17.1572L8.84292 16.2663L6.0145 19.0947ZM9.81334 7.42857V8.68858H13.8133V7.42857H9.81334ZM18.5716 5.42857H11.8133V9.42857H18.5716V5.42857ZM20.5716 13.4995V7.42857H16.5716V13.4995H20.5716ZM15.3116 9.57954V13.4995H19.3116V9.57954H15.3116ZM9.73389 19.9856L18.7258 10.9938L15.8973 8.16533L6.90546 17.1572L9.73389 19.9856ZM15.0064 7.27437L6.0145 16.2663L8.84292 19.0947L17.8348 10.1028L15.0064 7.27437ZM11.8133 10.6886H16.4206V6.68858H11.8133V10.6886Z"
                                fill="white" mask="url(#path-2-inside-1)"/>
                            </svg>
                            <div class="text-truncate">Enviar dinero</div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{URL::to('/wallets')}}"
                        class="btn btn-secondary btn-block text-center font-14 py-md-3 mb-3">
                        <svg class="mb-2" width="30" height="26" viewBox="0 0 29 26" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <mask id="path-1-inside-1" fill="white">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2.66036 9.02575C1.95726 9.02575 1.38729 9.59572 1.38729 10.2988V23.8782C1.38729 24.5813 1.95726 25.1513 2.66036 25.1513H26.4243C27.1274 25.1513 27.6974 24.5813 27.6974 23.8782V10.2988C27.6974 9.59572 27.1274 9.02575 26.4243 9.02575H21.059V8.17703H26.4243C27.5961 8.17703 28.5461 9.12699 28.5461 10.2988V23.8782C28.5461 25.05 27.5961 26 26.4243 26H2.66036C1.48853 26 0.538574 25.05 0.538574 23.8782V10.2988C0.538574 9.12699 1.48853 8.17703 2.66036 8.17703H14.391V9.02575H2.66036Z"/>
                        </mask>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2.66036 9.02575C1.95726 9.02575 1.38729 9.59572 1.38729 10.2988V23.8782C1.38729 24.5813 1.95726 25.1513 2.66036 25.1513H26.4243C27.1274 25.1513 27.6974 24.5813 27.6974 23.8782V10.2988C27.6974 9.59572 27.1274 9.02575 26.4243 9.02575H21.059V8.17703H26.4243C27.5961 8.17703 28.5461 9.12699 28.5461 10.2988V23.8782C28.5461 25.05 27.5961 26 26.4243 26H2.66036C1.48853 26 0.538574 25.05 0.538574 23.8782V10.2988C0.538574 9.12699 1.48853 8.17703 2.66036 8.17703H14.391V9.02575H2.66036Z"
                        fill="white"/>
                        <path
                        d="M21.059 9.02575H20.059V10.0257H21.059V9.02575ZM21.059 8.17703V7.17703H20.059V8.17703H21.059ZM14.391 8.17703H15.391V7.17703H14.391V8.17703ZM14.391 9.02575V10.0257H15.391V9.02575H14.391ZM2.38729 10.2988C2.38729 10.148 2.50954 10.0257 2.66036 10.0257V8.02575C1.40497 8.02575 0.387287 9.04343 0.387287 10.2988H2.38729ZM2.38729 23.8782V10.2988H0.387287V23.8782H2.38729ZM2.66036 24.1513C2.50954 24.1513 2.38729 24.029 2.38729 23.8782H0.387287C0.387287 25.1336 1.40497 26.1513 2.66036 26.1513V24.1513ZM26.4243 24.1513H2.66036V26.1513H26.4243V24.1513ZM26.6974 23.8782C26.6974 24.029 26.5751 24.1513 26.4243 24.1513V26.1513C27.6797 26.1513 28.6974 25.1336 28.6974 23.8782H26.6974ZM26.6974 10.2988V23.8782H28.6974V10.2988H26.6974ZM26.4243 10.0257C26.5751 10.0257 26.6974 10.148 26.6974 10.2988H28.6974C28.6974 9.04343 27.6797 8.02575 26.4243 8.02575V10.0257ZM21.059 10.0257H26.4243V8.02575H21.059V10.0257ZM20.059 8.17703V9.02575H22.059V8.17703H20.059ZM26.4243 7.17703H21.059V9.17703H26.4243V7.17703ZM29.5461 10.2988C29.5461 8.5747 28.1484 7.17703 26.4243 7.17703V9.17703C27.0439 9.17703 27.5461 9.67927 27.5461 10.2988H29.5461ZM29.5461 23.8782V10.2988H27.5461V23.8782H29.5461ZM26.4243 27C28.1484 27 29.5461 25.6023 29.5461 23.8782H27.5461C27.5461 24.4978 27.0439 25 26.4243 25V27ZM2.66036 27H26.4243V25H2.66036V27ZM-0.461426 23.8782C-0.461426 25.6023 0.936243 27 2.66036 27V25C2.04081 25 1.53857 24.4978 1.53857 23.8782H-0.461426ZM-0.461426 10.2988V23.8782H1.53857V10.2988H-0.461426ZM2.66036 7.17703C0.936243 7.17703 -0.461426 8.5747 -0.461426 10.2988H1.53857C1.53857 9.67927 2.04081 9.17703 2.66036 9.17703V7.17703ZM14.391 7.17703H2.66036V9.17703H14.391V7.17703ZM15.391 9.02575V8.17703H13.391V9.02575H15.391ZM2.66036 10.0257H14.391V8.02575H2.66036V10.0257Z"
                        fill="white" mask="url(#path-1-inside-1)"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M28.4645 18.7778L0.538574 18.7778L0.538574 17.8148L28.4645 17.8148L28.4645 18.7778Z"
                        fill="white"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M28.4645 22.6296L0.538574 22.6296L0.538574 21.6667L28.4645 21.6667L28.4645 22.6296Z"
                        fill="white"/>
                        <mask id="path-5-inside-2" fill="white">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M13.7787 4.05582L17.8345 -2.45709e-06L21.4779 3.64336L20.7217 4.39953L18.3692 2.04703L18.3692 15.3858L17.2998 15.3858L17.2998 2.04703L14.5349 4.81199L13.7787 4.05582Z"/>
                        </mask>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.7787 4.05582L17.8345 -2.45709e-06L21.4779 3.64336L20.7217 4.39953L18.3692 2.04703L18.3692 15.3858L17.2998 15.3858L17.2998 2.04703L14.5349 4.81199L13.7787 4.05582Z"
                        fill="white"/>
                        <path
                        d="M13.7787 4.05582L12.3645 2.64161L10.9503 4.05582L12.3645 5.47003L13.7787 4.05582ZM17.8345 -2.45709e-06L19.2487 -1.41422L17.8345 -2.82843L16.4203 -1.41422L17.8345 -2.45709e-06ZM21.4779 3.64336L22.8921 5.05758L24.3063 3.64336L22.8921 2.22915L21.4779 3.64336ZM20.7217 4.39953L19.3075 5.81375L20.7217 7.22796L22.1359 5.81375L20.7217 4.39953ZM18.3692 2.04703L19.7834 0.632818L16.3692 -2.7814L16.3692 2.04703L18.3692 2.04703ZM18.3692 15.3858L18.3692 17.3858L20.3692 17.3858L20.3692 15.3858L18.3692 15.3858ZM17.2998 15.3858L15.2998 15.3858L15.2998 17.3858L17.2998 17.3858L17.2998 15.3858ZM17.2998 2.04703L19.2998 2.04703L19.2998 -2.7814L15.8856 0.632817L17.2998 2.04703ZM14.5349 4.81199L13.1206 6.2262L14.5349 7.64042L15.9491 6.2262L14.5349 4.81199ZM22.1359 5.81375L22.8921 5.05758L20.0637 2.22915L19.3075 2.98532L22.1359 5.81375ZM17.2998 17.3858L18.3692 17.3858L18.3692 13.3858L17.2998 13.3858L17.2998 17.3858ZM12.3645 5.47003L13.1206 6.2262L15.9491 3.39778L15.1929 2.64161L12.3645 5.47003ZM16.4203 -1.41422L12.3645 2.64161L15.1929 5.47003L19.2487 1.41421L16.4203 -1.41422ZM22.8921 2.22915L19.2487 -1.41422L16.4203 1.41421L20.0637 5.05758L22.8921 2.22915ZM16.955 3.46124L19.3075 5.81375L22.1359 2.98532L19.7834 0.632818L16.955 3.46124ZM20.3692 15.3858L20.3692 2.04703L16.3692 2.04703L16.3692 15.3858L20.3692 15.3858ZM15.2998 2.04703L15.2998 15.3858L19.2998 15.3858L19.2998 2.04703L15.2998 2.04703ZM15.9491 6.2262L18.714 3.46124L15.8856 0.632817L13.1206 3.39778L15.9491 6.2262Z"
                        fill="white" mask="url(#path-5-inside-2)"/>
                    </svg>
                    <div class="text-truncate">Gestionar Billeteras</div>
                </a>
            </div>
            <div class="col-4">
                <a href="{{URL::to('/user-info')}}"
                class="btn btn-secondary btn-block text-center font-14 py-md-3 mb-3">
                <svg class="mb-2" width="26" height="26" viewBox="0 0 26 26" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <circle cx="13" cy="13" r="12.5" stroke="white"/>
                <path fill-rule="evenodd" clip-rule="evenodd"
                d="M13.8394 7L11.985 7V8.11527C11.985 9.63774 10.3516 10.6019 9.01865 9.86632L7.89254 9.24486L7.00019 10.7222L7.9547 11.249C9.33291 12.0096 9.33291 13.9905 7.9547 14.7511L7.00032 15.2778L7.89267 16.7551L9.01865 16.1338C10.3516 15.3981 11.985 16.3623 11.985 17.8848V19H13.8394V17.8846C13.8394 16.3621 15.4727 15.3979 16.8057 16.1335L17.9321 16.7551L18.8245 15.2778L17.8701 14.7511C16.4919 13.9905 16.4919 12.0096 17.8701 11.249L18.8246 10.7222L17.9322 9.24486L16.8057 9.86655C15.4727 10.6022 13.8394 9.63797 13.8394 8.1155V7ZM11.985 6C11.4327 6 10.985 6.44772 10.985 7V8.11527C10.985 8.8765 10.1683 9.3586 9.50182 8.99079L8.37571 8.36933C7.90548 8.10983 7.31424 8.26811 7.03656 8.72784L6.14421 10.2052C5.85075 10.6911 6.02006 11.3235 6.51702 11.5977L7.47153 12.1245C8.16064 12.5048 8.16064 13.4953 7.47153 13.8756L6.51714 14.4023C6.02018 14.6765 5.85087 15.3089 6.14434 15.7948L7.03669 17.2722C7.31437 17.7319 7.90561 17.8902 8.37584 17.6307L9.50182 17.0093C10.1683 16.6415 10.985 17.1236 10.985 17.8848V19C10.985 19.5523 11.4327 20 11.985 20H13.8394C14.3916 20 14.8394 19.5523 14.8394 19V17.8846C14.8394 17.1233 15.656 16.6412 16.3225 17.009L17.4489 17.6307C17.9192 17.8902 18.5104 17.7319 18.7881 17.2722L19.6804 15.7948C19.9739 15.3089 19.8046 14.6765 19.3076 14.4023L18.3532 13.8756C17.6641 13.4953 17.6641 12.5048 18.3532 12.1245L19.3078 11.5978C19.8047 11.3235 19.974 10.6911 19.6806 10.2052L18.7882 8.72784C18.5105 8.26811 17.9193 8.10983 17.4491 8.36933L16.3225 8.99103C15.656 9.35883 14.8394 8.87674 14.8394 8.1155V7C14.8394 6.44772 14.3916 6 13.8394 6H11.985Z"
                fill="white"/>
            </svg>
            <div class="text-truncate">Configuración</div>
        </a>
    </div>
</div>
</div>
</div>
</section>

<div class="row flex-column-reverse flex-md-row">
    <div class="col-md-7 col-lg-8">
        <div class="card shadow-none rounded-lg mb-4">
            <div class="card-header pt-md-4 pb-1">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="card-title text-primary font-weight-bold mb-0">Transacciones
                        recientes</h6>
                    </div>
                    <div class="col-4">
                        <div class="text-right">
                            <a href="{{URL::to('/wallets')}}" class="font-14 ws-nowrap lh-125">Ver todos <i
                                class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(empty($transactions))
                    <div class="py-4 py-lg-5">
                        <div class="row">
                            <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                <img src="img/landing/empty-transactions.svg" alt="Transactions Empty"
                                class="img-fluid mb-4" style="max-height: 100px">
                                <h5 class="text-primary">Actualmente tu historial de transacciones esta
                                vacío</h5>
                                <h6 class="text-muted">Todas las transacciones que hagas se irán mostrando
                                    aquí.
                                </div>
                            </div>
                        </div>
                        @else
                        <ul class="walletHistory list-unstyled">
                            @foreach($transactions as $transaction)
                            <li class="walletHistory__item mb-2">
                                <div class="walletHistory__item__body">
                                    <div class="media">
                                        <div class="walletHistory__item__type my-auto mr-2 mr-md-3"
                                        data-toggle="tooltip" title="Recibido">
                                        <svg width="25" height="21" viewBox="0 0 25 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        @if($transaction['type'] === 3 && $transaction['purpose'] === 2)
                                        <path d="M2 13L14 1M14 1H7.14286M14 1V7"
                                        stroke="#FFC107" stroke-width="2"
                                        stroke-linecap="square"/>
                                        <path
                                        d="M12 19.7877C12 19.7877 15 17.1141 18 18.7877C21 20.4612 24 17.7877 24 17.7877"
                                        stroke="#FFC107" stroke-width="2"/>
                                        <path
                                        d="M12 15C12 15 15 12.3265 18 14C21 15.6735 24 13 24 13"
                                        stroke="#FFC107" stroke-width="2"/>
                                        @elseif($transaction['type'] === 3 && $transaction['purpose'] === 1)
                                        <path d="M13 2L1 14M1 14H7.85714M1 14V8"
                                        stroke="#FFC107" stroke-width="2"
                                        stroke-linecap="square"/>
                                        <path
                                        d="M11.0002 20.7877C11.0002 20.7877 14.0002 18.1141 17.0002 19.7877C20.0002 21.4612 23.0002 18.7877 23.0002 18.7877"
                                        stroke="#FFC107" stroke-width="2"/>
                                        <path
                                        d="M11.0002 16C11.0002 16 14.0002 13.3265 17.0002 15C20.0002 16.6736 23.0002 14 23.0002 14"
                                        stroke="#FFC107" stroke-width="2"/>
                                        @elseif(($transaction['type'] === 2 && $transaction['status'] === 1) ||
                                        ($transaction['type'] === 4 && $transaction['purpose'] === 2))
                                        <path
                                        d="M2 16.2727L17.2727 1M17.2727 1H8.54545M17.2727 1V8.63636"
                                        stroke="#38A1DC"
                                        stroke-width="2" stroke-linecap="square"/>
                                        @elseif(($transaction['type'] === 1 && $transaction['status'] === 1) ||
                                        ($transaction['type'] === 4 && $transaction['purpose'] === 1))
                                        <path
                                        d="M16.6364 2.36362L1.36362 17.6364M1.36362 17.6364H10.0909M1.36362 17.6364V9.99999"
                                        stroke="#1DBA44" stroke-width="2"
                                        stroke-linecap="square"/>
                                        @elseif($transaction['status'] === 3)
                                        <path d="M1 15L15 1" stroke="#DC3545" stroke-width="2"/>
                                        <path d="M15 15L1 1" stroke="#DC3545" stroke-width="2"/>
                                        @endif
                                    </svg>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex justify-content-between">
                                        @if($transaction['type'] === 2 || ($transaction['type'] === 3 && $transaction['purpose'] === 2))
                                        <h5 class="walletHistory__item__amount mb-0">
                                            - {{number_format($transaction['sender_fiat_amount'], 2)}} {{$transaction['sender_fiat']}}
                                            -> {{number_format($transaction['receiver_fiat_amount'],2)}} {{$transaction['receiver_fiat']}}</h5>
                                            @elseif($transaction['type'] === 1 || ($transaction['type'] === 3 && $transaction['purpose'] === 1))
                                            <h5 class="walletHistory__item__amount mb-0">
                                                + {{number_format($transaction['receiver_fiat_amount'],2)}} {{$transaction['receiver_fiat']}}
                                                -> {{number_format($transaction['sender_fiat_amount'],2)}} {{$transaction['sender_fiat']}}</h5>
                                                @endif
                                                <div
                                                class="walletHistory__item__date text-muted">{{$transaction['created_at']}}</div>
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-between">
                                                @if($transaction['type'] === 3 && $transaction['purpose'] === 2)
                                                <div class="text-uppercase">envio en espera</div>
                                                @elseif($transaction['type'] === 3 && $transaction['purpose'] === 1)
                                                <div class="text-uppercase">recarga en espera</div>
                                                @elseif($transaction['type'] === 2)
                                                <div class="text-uppercase">envio de dinero</div>
                                                @elseif($transaction['type'] === 1)
                                                <div class="text-uppercase">recarga a tu billetera</div>
                                                @endif

                                                @if(isset($transaction['tracking_id']))
                                                <div>Tracking ID: {{$transaction['tracking_id']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                <div class="card shadow-none rounded-lg mb-4">
                    <div class="card-header pt-md-4 pb-1">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="card-title text-primary font-weight-bold mb-0">Últimos contactos</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($destinations->count() === 0)
                        <div class="py-4 py-lg-5">
                            <div class="row">
                                <div class="col-md-9 col-lg-7 col-xl-6 mx-auto text-center">
                                    <img src="img/landing/empty-transactions.svg" alt="Transactions Empty"
                                    class="img-fluid mb-4" style="max-height: 100px">
                                    <h5 class="text-primary">Actualmente tu historial de contactos esta
                                    vacío</h5>
                                    <h6 class="text-muted">Todos los contactos que registres se irán mostrando
                                        aquí.
                                    </div>
                                </div>
                            </div>
                            @else
                            <ul class="list-unstyled">
                                @foreach($destinations as $destination)
                                <a href="#!" class="box">
                                    <li>
                                        <div class="form-row align-items-center">
                                            <div class="col-6 col-lg-4">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <h6 class="text-primary font-weight-bold text-truncate mb-0">{{ucfirst($destination->name)}} {{ucfirst($destination->lastname)}}</h6>
                                                        <div class="text-muted font-13">
                                                            @if($destination->type === 1)
                                                            Persona
                                                            @else
                                                            Negocios
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 col-lg-4">
                                                <div class="text-center">
                                                    <img
                                                    src="/img/landing/flags/{{strtolower($destination->country)}}.svg"
                                                    alt="VE" title="Venezuela" style="height: 24px">
                                                </div>
                                            </div>
                                            <div class="col-3 col-lg-4">
                                                <div class="text-md-right">
                                                    <h6 class="text-truncate font-14 mb-0">{{$destination->bank_name}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </a>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-md-5 col-lg-4">
                    <div class="card shadow-none rounded-lg mb-4">
                        <div class="card-header pt-md-4 pb-1">
                            <h6 class="card-title text-primary font-weight-bold mb-0">Tus billeteras</h6>
                        </div>
                        <div class="card-body">
                            <a href="{{URL::to('/wallets')}}" class="cWallet cWallet--transition --usd mb-3">
                                <img src="/img/landing/wallet-bg-right.svg" class="cWallet__bg--right">
                                <img src="/img/landing/wallet-bg-left.svg" class="cWallet__bg--left">
                                <div class="d-flex justify-content-between" style="z-index: 10">
                                    <div>
                                        <h3 class="cWallet__amount mb-0">{{number_format($userWallets->numbers['available'], 2)}}</h3>
                                        <div class="cWallet__text font-14">Billetera USD</div>
                                    </div>
                                    <div class="cWallet__flag">
                                        <img src="/img/landing/flags/us.svg" alt="USD" class="object-cover">
                                    </div>
                                </div>
                                <div class="btn btn-outline-light btn-pill btn-sm px-md-3 mt-3">Gestionar billetera
                                </div>
                            </a>
                            <div class="cWallet --btc mb-3">
                                <img src="/img/landing/wallet-bg-right.svg" class="cWallet__bg--right">
                                <img src="/img/landing/wallet-bg-left.svg" class="cWallet__bg--left">
                                <div class="d-flex justify-content-between" style="z-index: 10">
                                    <div>
                                        <h3 class="cWallet__amount mb-0">0.00000000</h3>
                                        <div class="cWallet__text font-14">Billetera BTC</div>
                                    </div>
                                    <div class="cWallet__flag">
                                        <img src="/img/landing/flags/btc.svg" alt="BTC" class="object-cover">
                                    </div>
                                </div>
                                <!-- <div class="btn btn-outline-light btn-pill btn-sm px-md-3 mt-3">Gestionar billetera</div> -->
                                <div class="overlay">
                                    <div class="text-white text-center">
                                        <i class="fa fa-lock fa-2x"></i>
                                        <div>¡Próximamente!</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-none d-md-block px-4">
                        <h6 class="font-14 font-weight-bold">Sácale el mayor provecho a tu cuenta</h6>
                        <ul class="font-14 pl-4">
                            <li>
                                <a href="{{URL::to('/wallets/transfer')}}" class="text-primary">Transferir dinero por
                                correo electrónico</a>
                            </li>
                            @if(!isset(Auth::user()->personProfile->selfie))
                            <li>
                                <a href="{{URL::to('/user-info')}}" class="text-primary">Agrega una foto de
                                perfil</a>
                            </li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </main>

    @endsection
