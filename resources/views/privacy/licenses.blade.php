@extends('layouts.mvp-layout')

@section('content')

    <main class="py-section-4">
        <div class="container">
            <div class="row animated fadeIn">
                <aside id="legalViews-navigation" class="col-md-3 sticky-top h-100 pt-md-4">
                    <div class="text-center mb-4 mb-md-0">
                        <img src="img/landing/shield-icon.svg" class="img-fluid mb-3">
                        <h2 class="text-primary font-weight-bold">@lang('mvp-home.legal_content')</h2>
                        <nav class="asideMenu nav flex-column mt-4">
                            <a class="nav-link py-md-3"
                               href="{{URL::to('/agreement')}}">@lang('mvp-home.user_agreement')</a>
                            <a class="nav-link py-md-3"
                               href="{{URL::to('/wallets-agreement')}}">@lang('mvp-home.wallet_agreement')</a>
                            <a class="nav-link py-md-3"
                               href="{{URL::to('/privacy-policies')}}">@lang('mvp-home.legal_privacy')</a>
                            <a class="nav-link py-md-3"
                               href="{{URL::to('/cookies-policies')}}">@lang('mvp-home.cookies')</a>
                            <a class="nav-link py-md-3 active"
                               href="{{URL::to('/licenses')}}">@lang('mvp-home.licenses')</a>
                            <!--  <a class="nav-link py-md-3" href="legal-costos.html">Costos de Servicios</a> -->
                        </nav>
                    </div>
                </aside>
                <div class="col-md-8 offset-md-1">
                    <div class="d-flex justify-content-between align-items-end border-bottom py-2 bg-white sticky-top">
                        <h2>@lang('licenses.licenses')</h2>
                    </div>
                    <div class="row no-gutters align-items-center border-bottom py-3 px-5 px-md-0">
                        <div class="col-md">
                            <div class="text-center text-md-left mb-3 mb-md-0 mr-md-3">
                                <img src="img/landing/fcen-logo.png" alt="Financial Crimes Enforcement Network"
                                     title="Financial Crimes Enforcement Network" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-7 text-center text-md-left">
                            @lang('licenses.financial_crimes')
                        </div>
                        <div class="col-12 col-md-3 text-center text-md-right">
                            <a href="/docs/AKB-(msb_registration Licencia).pdf"
                               class="btn btn-transparent ws-nowrap text-truncate"
                               target="_blank"
                               title="Descargar en PDF">
                                <span class="text-primary">@lang('mvp-home.download')</span>
                                <img src="img/landing/pdf-primary.svg" class="img-fluid ml-2">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
