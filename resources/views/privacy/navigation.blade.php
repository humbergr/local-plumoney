<aside id="legalViews-navigation" class="col-md-3 sticky-top h-100 pt-md-4">
    <div class="text-center mb-4 mb-md-0">
        <img src="img/landing/shield-icon.svg" class="img-fluid mb-3">
        <h2 class="text-primary font-weight-bold">@lang('mvp-home.legal_content')</h2>
        <nav class="asideMenu nav flex-column mt-4">
            <a class="nav-link py-md-3 active" href="{{URL::to('/agreement')}}">@lang('mvp-home.user_agreement')</a>
            <a class="nav-link py-md-3"
               href="{{URL::to('/agreement')}}">@lang('mvp-home.wallet_agreement')</a>
            <a class="nav-link py-md-3" href="{{URL::to('/privacy-policies')}}">@lang('mvp-home.legal_privacy')</a>
            <a class="nav-link py-md-3" href="legal-cookies.html">@lang('mvp-home.cookies')</a>
            <a class="nav-link py-md-3" href="legal-licencias.html">@lang('mvp-home.licenses')</a>
            <!--  <a class="nav-link py-md-3" href="legal-costos.html">Costos de Servicios</a> -->
        </nav>
    </div>
</aside>
