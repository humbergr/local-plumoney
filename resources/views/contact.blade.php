@extends('layouts.mvp-layout')

@section('content')
<main>

        <section id="contact-map" class="mt-n5">
            <div class="container-fluid px-0">
                <div id="main-office" class="map--wrapper" style="min-height: 500px; pointer-events: none;"></div>
            </div>
        </section>

        <section class="py-section-3" style="margin-top: -15rem">
            <div class="container">
                <div class="row no-gutters shadow">
                    <div class="col-md-5">
                        <div class="bg-primary text-white px-3 px-md-5 py-5 py-md-5 h-100">
                            <div class="text-center">
                                <img src="img/cb-img/coinbank-logo-light.png" class="img-fluid mb-4 mb-md-5" style="max-width: 195px">
                            </div>
                            <ul class="list-unstyled mb-5 font-14">
                                <li class="mb-3">
                                    <div class="media">
                                        <div class="text-center">
                                            <i class="fa fa-phone fa-2x mr-2 va-middle" style="width: 2rem"></i>
                                        </div>
                                        <div class="media-body">
                                            <a href="tel:+1 (786) 245-8123" class="text-light">+1 (786) 245-8123</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="media">
                                        <div class="text-center">
                                            <i class="fa fa-envelope fa-2x mr-2 va-middle" style="width: 2rem"></i>
                                        </div>
                                        <div class="media-body"><a href="mailto:customercare@americankryptosbank.com" class="text-light">customercare@americankryptosbank.com</a></div>
                                    </div>
                                </li>
{{--                                <li class="mb-3">--}}
{{--                                    <div class="media">--}}
{{--                                        <div class="text-center">--}}
{{--                                            <i class="fa fa-map-marker fa-2x mr-2 va-middle" style="width: 2rem"></i>--}}
{{--                                        </div>--}}
{{--                                        <div class="media-body">700 Smith ST #61070 Houston TX, USA. 77002</div>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
                            </ul>
                            <h6 class="text-secondary font-weight-bold text-center mb-4">Síguenos en nuestras redes</h6>
                            <ul class="list-inline mb-0 text-center">
                                <li class="list-inline-item">
                                    <a href="" class="text-light" target="_blank">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x"></i>
                                            <i class="fa fa-facebook fa-stack-1x text-primary"></i>
                                        </span>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="text-light" target="_blank">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x"></i>
                                            <i class="fa fa-instagram fa-stack-1x text-primary"></i>
                                        </span>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="text-light" target="_blank">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x"></i>
                                            <i class="fa fa-twitter fa-stack-1x text-primary"></i>
                                        </span>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="text-light" target="_blank">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-circle fa-stack-2x"></i>
                                            <i class="fa fa-linkedin fa-stack-1x text-primary"></i>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="body-bg-color px-3 py-4 p-md-5 h-100">
                            <form class="" action="{{URL::to('/contact')}}" method="post">
                              @csrf
                                <div class="form-row">
                                    <div class="col-md offset-md-2">
                                        <h3 class="text-primary font-weight-bold mb-4">Contáctenos</h3>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-2 text-md-right pr-md-3">
                                        <label for="name">Nombre</label>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <input id="name" name="name" type="text" class="form-control" @if(Auth::user()) value="{{Auth::user()->name}}" readonly @endif required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-2 text-md-right pr-md-3">
                                        <label for="email">Email</label>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <input id="email" name="email" type="email" class="form-control" @if(Auth::user()) value="{{Auth::user()->email}}" readonly @endif required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-2 text-md-right pr-md-3">
                                        <label for="phone">Teléfono</label>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <input id="phone" name="phone"  type="text" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-2 text-md-right pr-md-3">
                                        <label for="message">Mensaje</label>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <textarea id="message" name="message" class="form-control" style="min-height: 100px" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-secondary btn-pill px-4">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
