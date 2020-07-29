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
                            <a class="nav-link py-md-3 active"
                               href="{{URL::to('/wallets-agreement')}}">@lang('mvp-home.wallet_agreement')</a>
                            <a class="nav-link py-md-3"
                               href="{{URL::to('/privacy-policies')}}">@lang('mvp-home.legal_privacy')</a>
                            <a class="nav-link py-md-3"
                               href="{{URL::to('/cookies-policies')}}">@lang('mvp-home.cookies')</a>
                            <a class="nav-link py-md-3" href="{{URL::to('/licenses')}}">@lang('mvp-home.licenses')</a>
                            <!--  <a class="nav-link py-md-3" href="legal-costos.html">Costos de Servicios</a> -->
                        </nav>
                    </div>
                </aside>
                <div class="col-md-8 offset-md-1">
                    <div class="d-flex justify-content-between align-items-end border-bottom py-2 bg-white sticky-top">
                        <h2>@lang('wallets-agreement.main_title')</h2>
                        <div>
                            <a href="/docs/@lang('wallets-agreement.file_name')"
                               target="_blank"
                               class="btn btn-transparent ws-nowrap text-truncate" title="Descargar en PDF">
                                <span class="text-primary">@lang('mvp-home.download')</span>
                                <img src="img/landing/pdf-primary.svg" class="img-fluid ml-2">
                            </a>
                        </div>
                    </div>
                    <!-- content -->
                    <div class="font-14 py-4">
                        <p>@lang('wallets-agreement.text_1')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_1')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_1_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_2')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_2_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_3')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_3_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_4')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_4_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_5')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_5_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_6')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_6_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_7')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_7_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_8')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_8_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_9')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_9_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_10')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_10_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_11')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_11_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_12')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_12_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_13')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_13_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_14')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_14_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_15')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_15_text')</p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('wallets-agreement.title_16')</h6>
                        </div>
                        <p>@lang('wallets-agreement.list_16_text')</p>
                        <div class="text-center mt-4 mt-md-5">
                            <a href="/docs/@lang('wallets-agreement.file_name')"
                               target="_blank"
                               class="btn btn-transparent ws-nowrap text-truncate" title="Descargar en PDF">
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
