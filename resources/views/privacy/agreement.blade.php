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
                            <a class="nav-link py-md-3 active"
                               href="{{URL::to('/agreement')}}">@lang('mvp-home.user_agreement')</a>
                            <a class="nav-link py-md-3"
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
                        <h2>@lang('mvp-home.user_agreement')</h2>
                        <div>
                            <a href="/docs/AKB-(USER AGREEMENT).pdf" target="_blank"
                               class="btn btn-transparent ws-nowrap text-truncate" title="Descargar en PDF"><span
                                    class="text-primary">@lang('mvp-home.download')</span><img
                                    src="img/landing/pdf-primary.svg" class="img-fluid ml-2"></a>
                        </div>
                    </div>
                    <!-- content -->
                    <div class="font-14 py-4">
                        <p>@lang('agreement.text_1')</p>
                        <p>@lang('agreement.text_2')</p>
                        <p>@lang('agreement.text_3')</p>
                        <p></p>
                        <div class="my-4 text-uppercase border-bottom pb-2">
                            <h6 class="text-primary font-weight-bold mb-0"><p>@lang('agreement.title_1')</h6>
                        </div>
                        <p><strong>@lang('agreement.sub_title_1')</strong></p>
                        <p><strong>@lang('agreement.list_1')</strong>@lang('agreement.list_1_text')</p>
                        <p><strong>@lang('agreement.list_2')</strong>@lang('agreement.list_2_text')</p>
                        <p><strong>@lang('agreement.list_3')</strong>@lang('agreement.list_3_text')</p>
                        <p><strong>@lang('agreement.list_4')</strong>@lang('agreement.list_4_text')</p>
                        <p><strong>@lang('agreement.list_5')</strong>@lang('agreement.list_5_text')</p>
                        <div class="text-center mt-4 mt-md-5">
                            <a href="" class="btn btn-transparent ws-nowrap" title="Descargar en PDF"><span
                                    class="text-primary">@lang('mvp-home.download')</span><img
                                    src="img/landing/pdf-primary.svg" class="img-fluid ml-2"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
