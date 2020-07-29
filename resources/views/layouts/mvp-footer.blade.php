<footer class="bg-primary text-white">
  <div class="py-5">
    <div class="container">
        <ul class="nav justify-content-center flex-nowrap mb-4 mb-md-5" id="footerTabs" role="tablist">
            <li class="nav-item mx-2">
                <a class="nav-link footer__btnTabs py-0 btn-pill active" id="personas-tab" data-toggle="tab"
                   href="#personas" role="tab" aria-controls="personal"
                   aria-selected="true">@lang('mvp-header.personal')</a>
            </li>
            <li class="nav-item mx-2">
                <a class="nav-link footer__btnTabs py-0 btn-pill" id="negocios-tab" data-toggle="tab"
                   href="#negocios" role="tab" aria-controls="negocios"
                   aria-selected="false">@lang('mvp-header.business')</a>
            </li>
            {{--<li class="nav-item mx-2">
                <a class="nav-link footer__btnTabs py-0 btn-pill" id="inversionistas-tab" data-toggle="tab"
                   href="#inversionistas" role="tab" aria-controls="inversionistas" aria-selected="false">@lang('mvp-header.investors')</a>
            </li>--}}
        </ul>
        <div class="tab-content">
            <div class="tab-pane show active" id="personas" role="tabpanel" aria-labelledby="personas-tab">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <img src="/img/cb-img/coinbank-logo-light.png" class="img-fluid mb-4 mb-md-5"
                             style="max-width: 195px">
                        <ul class="list-unstyled font-14">
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
                                    <div class="media-body">
                                        <a href="mailto:csupport@americankryptosbank.com"
                                           class="text-light text-break">
                                            csupport@americankryptosbank.com
                                        </a>
                                    </div>
                                </div>
                            </li>
{{--                            <li class="mb-3">--}}
{{--                                <div class="media">--}}
{{--                                    <div class="text-center">--}}
{{--                                        <i class="fa fa-map-marker fa-2x mr-2 va-middle" style="width: 2rem"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="media-body">700 Smith ST #61070 Houston TX, USA. 77002</div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
                        </ul>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                                <h6 class="text-secondary font-weight-bold">@lang('mvp-home.services')</h6>
                                <li><a href="/services/send-money"
                                       class="text-light">@lang('mvp-home.send_money')</a></li>
                                <li><a href="/services/exchange-money"
                                       class="text-light">@lang('mvp-home.convert_money')</a></li>
                                <li><a href="/services/crypto-market"
                                       class="text-light">@lang('mvp-home.buy_sell_crypto')</a></li>
                                <li><a href="/services/savings"
                                       class="text-light">@lang('mvp-home.save_money')</a></li>
                                <li><a href="/services/transfer-money"
                                       class="text-light">@lang('mvp-home.transfer_money')</a></li>
                                <li><a href="/services/investments"
                                       class="text-light">@lang('mvp-home.investment')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                       class="text-light">@lang('mvp-home.debit_card')</a></li>
                            </ul>
                        </div>
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                                <h6 class="text-secondary font-weight-bold">@lang('mvp-header.help')</h6>
                                <li><a href="{{URL::to('/help')}}"
                                       class="text-light">@lang('mvp-home.freq_questions')</a></li>
                                <li><a href="{{URL::to('/contact')}}" class="text-light">@lang('mvp-home.support')</a>
                                </li>
                                <li><a href="{{URL::to('/contact')}}"
                                       class="text-light">@lang('mvp-header.contact_us')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                                <h6 class="text-secondary font-weight-bold">@lang('mvp-header.about_us')</h6>
                                <li><a href="{{URL::to('/about-us')}}"
                                       class="text-light">@lang('mvp-home.our_business')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                       class="text-light">@lang('mvp-home.careers')</a></li>
                                <li>
                                    <a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                       class="text-light">@lang('mvp-home.press_media')</a>
                                    <ul class="pl-3 my-2">
                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                               class="text-secondary">@lang('mvp-home.news')</a></li>

                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                               class="text-secondary">@lang('mvp-home.blog')</a></li>
                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                               class="text-secondary">@lang('mvp-home.events')</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="" class="text-light">@lang('mvp-home.legal_privacy')</a>
                                    <ul class="pl-3 my-2">
                                        <li><a href="{{URL::to('/agreement')}}"
                                               class="text-secondary">@lang('mvp-home.user_agreement')</a></li>
                                        <li>
                                            <a class="text-secondary"
                                               href="{{URL::to('/wallet-agreement')}}">@lang('mvp-home.wallet_agreement')</a>
                                        </li>
                                        <li><a href="{{URL::to('/privacy-policies')}}"
                                               class="text-secondary">@lang('mvp-home.privacy_policies')</a></li>
                                        <li><a href="{{URL::to('/cookies-policies')}}"
                                               class="text-secondary">@lang('mvp-home.cookies')</a></li>
                                        <li><a href="{{URL::to('/licenses')}}"
                                               class="text-secondary">@lang('mvp-home.licenses')</a></li>
                                    <!--<li><a href="" class="text-secondary">@lang('mvp-home.service_costs')</a></li>-->
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <h6 class="text-secondary font-weight-bold text-center mb-4">@lang('mvp-home.follow_us')</h6>
                        <div class="row">
                            <div class="col-6 text-right mb-4">
                                <a href="https://www.facebook.com/akryptosbankenespanol/" class="text-light"
                                   target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-facebook fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-left mb-4">
                                <a href="https://www.instagram.com/akryptosbankenespanol/" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-instagram fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-right mb-4">
                                <a href="https://twitter.com/AkryptosBankEsp" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-twitter fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-left mb-4">
                                <a href="https://www.linkedin.com/company/coin-bank-usa/" class="text-light"
                                   target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-linkedin fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="negocios" role="tabpanel" aria-labelledby="negocios-tab">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <img src="/img/cb-img/coinbank-logo-light.png" class="img-fluid mb-4 mb-md-5"
                             style="max-width: 195px">
                        <ul class="list-unstyled font-14">
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
                                    <div class="media-body"><a href="mailto:csupport@americankryptosbank.com"
                                                               class="text-light text-break">csupport@americankryptosbank.com</a>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="media">
                                    <div class="text-center">
                                        <i class="fa fa-map-marker fa-2x mr-2 va-middle" style="width: 2rem"></i>
                                    </div>
                                    <div class="media-body">3517 NW 115TH AVE. DORAL FL 33178</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                                <h6 class="text-secondary font-weight-bold">@lang('mvp-home.services')</h6>
                                <li><a href="javascript:void(0);"
                                       data-toggle="tooltip" title="Coming Soon" class="text-light">Transferencias</a></li>
                                <li><a href="javascript:void(0);"
                                       data-toggle="tooltip" title="Coming Soon" class="text-light">Transferencias domésticas</a></li>
                                <li><a href="javascript:void(0);"
                                       data-toggle="tooltip" title="Coming Soon" class="text-light">
                                        Transferencias Internacionales
                                    </a></li>
                                <li><a href="javascript:void(0);"
                                       data-toggle="tooltip" title="Coming Soon" class="text-light">
                                        Compra y Vende Criptomoneda
                                    </a></li>
                                <li><a href="javascript:void(0);"
                                       data-toggle="tooltip" title="Coming Soon" class="text-light">
                                        Punto de Ventas Kryptos
                                    </a></li>
                                <li><a href="javascript:void(0);"
                                       data-toggle="tooltip" title="Coming Soon" class="text-light">
                                        Franquicia Kryptos
                                    </a></li>
                                <li><a href="javascript:void(0);"
                                       data-toggle="tooltip" title="Coming Soon" class="text-light">
                                        Inversiones
                                    </a></li>
                                <li><a href="javascript:void(0);"
                                       data-toggle="tooltip" title="Coming Soon" class="text-light">
                                        Tarjeta de Débito
                                    </a></li>
                            </ul>
                        </div>
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                                <h6 class="text-secondary font-weight-bold">@lang('mvp-header.help')</h6>
                                <li><a href="{{URL::to('/help')}}"
                                       class="text-light">@lang('mvp-home.freq_questions')</a></li>
                                <li><a href="{{URL::to('/contact')}}" class="text-light">@lang('mvp-home.support')</a>
                                </li>
                                <li><a href="{{URL::to('/contact')}}"
                                       class="text-light">@lang('mvp-header.contact_us')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                                <h6 class="text-secondary font-weight-bold">@lang('mvp-header.about_us')</h6>
                                <li><a href="{{URL::to('/about-us')}}"
                                       class="text-light">@lang('mvp-home.our_business')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                       class="text-light">@lang('mvp-home.careers')</a></li>
                                <li>
                                    <a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                       class="text-light">@lang('mvp-home.press_media')</a>
                                    <ul class="pl-3 my-2">
                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                               class="text-secondary">@lang('mvp-home.news')</a></li>

                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                               class="text-secondary">@lang('mvp-home.blog')</a></li>
                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon"
                                               class="text-secondary">@lang('mvp-home.events')</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="" class="text-light">@lang('mvp-home.legal_privacy')</a>
                                    <ul class="pl-3 my-2">
                                        <li><a href="{{URL::to('/agreement')}}"
                                               class="text-secondary">@lang('mvp-home.user_agreement')</a></li>
                                        <li><a href="" class="text-secondary">@lang('mvp-home.privacy_policies')</a>
                                        </li>
                                        <li><a href="{{URL::to('/cookies-policies')}}"
                                               class="text-secondary">@lang('mvp-home.cookies')</a></li>
                                        <li><a href="{{URL::to('/licenses')}}"
                                               class="text-secondary">@lang('mvp-home.licenses')</a></li>
                                    <!--<li><a href="" class="text-secondary">@lang('mvp-home.service_costs')</a></li>-->
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <h6 class="text-secondary font-weight-bold text-center mb-4">@lang('mvp-home.follow_us')</h6>
                        <div class="row">
                            <div class="col-6 text-right mb-4">
                                <a href="https://www.facebook.com/americankryptosbank/" class="text-light"
                                   target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-facebook fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-left mb-4">
                                <a href="https://www.instagram.com/akryptosbankven/" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-instagram fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-right mb-4">
                                <a href="https://twitter.com/akryptosbankven" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-twitter fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-left mb-4">
                                <a href="https://www.linkedin.com/company/coin-bank-usa/" class="text-light"
                                   target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-linkedin fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="tab-pane" id="inversionistas" role="tabpanel" aria-labelledby="inversionistas-tab">
                <div class="row">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <img src="/img/cb-img/coinbank-logo-light.png" class="img-fluid mb-4 mb-md-5"
                             style="max-width: 195px">
                        <ul class="list-unstyled font-14">
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
                                    <div class="media-body"><a href="mailto:customercare@americankryptosbank.com"
                                                               class="text-light text-break">customercare@americankryptosbank.com</a>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="media">
                                    <div class="text-center">
                                        <i class="fa fa-map-marker fa-2x mr-2 va-middle" style="width: 2rem"></i>
                                    </div>
                                    <div class="media-body">3517 NW 115TH AVE. DORAL FL 33178</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                                <h6 class="text-secondary font-weight-bold">@lang('mvp-home.services')</h6>
                                <li><a href="{{URL::to('/send-money')}}" class="text-light">@lang('mvp-home.send_money')</a></li>
                                <li><a href="{{URL::to('/send-money')}}" class="text-light">@lang('mvp-home.convert_money')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-light">@lang('mvp-home.buy_sell_crypto')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-light">@lang('mvp-home.save_money')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-light">@lang('mvp-home.transfer_money')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-light">@lang('mvp-home.investment')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-light">@lang('mvp-home.debit_card')</a></li>
                            </ul>
                        </div>
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                              <h6 class="text-secondary font-weight-bold">@lang('mvp-header.help')</h6>
                              <li><a href="{{URL::to('/help')}}" class="text-light">@lang('mvp-home.freq_questions')</a></li>
                              <li><a href="{{URL::to('/contact')}}" class="text-light">@lang('mvp-home.support')</a></li>
                              <li><a href="{{URL::to('/contact')}}" class="text-light">@lang('mvp-header.contact_us')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="mb-4">
                            <ul class="list-unstyled font-14">
                              <h6 class="text-secondary font-weight-bold">@lang('mvp-header.about_us')</h6>
                              <li><a href="{{URL::to('/about-us')}}" class="text-light">@lang('mvp-home.our_business')</a></li>
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-light">@lang('mvp-home.careers')</a></li>
                                <li>
                                    <a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-light">@lang('mvp-home.press_media')</a>
                                    <ul class="pl-3 my-2">
                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-secondary">@lang('mvp-home.news')</a></li>

                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-secondary">@lang('mvp-home.blog')</a></li>
                                        <li><a href="javascript:void(0);" data-toggle="tooltip" title="Coming Soon" class="text-secondary">@lang('mvp-home.events')</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="" class="text-light">@lang('mvp-home.legal_privacy')</a>
                                    <ul class="pl-3 my-2">
                                        <li><a href="{{URL::to('/agreement')}}" class="text-secondary">@lang('mvp-home.user_agreement')</a></li>
                                        <li><a href="" class="text-secondary">@lang('mvp-home.privacy_policies')</a></li>
                                        <li><a href="{{URL::to('/cookies-policies')}}" class="text-secondary">@lang('mvp-home.cookies')</a></li>
                                        <li><a href="{{URL::to('/licenses')}}" class="text-secondary">@lang('mvp-home.licenses')</a></li>
                                        <!--<li><a href="" class="text-secondary">@lang('mvp-home.service_costs')</a></li>-->
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <h6 class="text-secondary font-weight-bold text-center mb-4">@lang('mvp-home.follow_us')</h6>
                        <div class="row">
                            <div class="col-6 text-right mb-4">
                                <a href="https://www.facebook.com/americankryptosbank/" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-facebook fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-left mb-4">
                                <a href="https://www.instagram.com/akryptosbankven/" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-instagram fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-right mb-4">
                                <a href="https://twitter.com/akryptosbankven" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-twitter fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-6 text-left mb-4">
                                <a href="https://www.linkedin.com/company/coin-bank-usa/" class="text-light" target="_blank">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x"></i>
                                        <i class="fa fa-linkedin fa-stack-1x text-primary"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
  </div>
  <div class="bg-white text-primary font-14 text-center py-2">
    <div class="container-fluid">
      <p class="mb-0">
          Copyright © 2019 American Kryptos Bank. Es un Nombre Comercial, propiedad de American Time Holding LLC.
          Todos los Derechos Reservados.
      </p>
    </div>
  </div>
</footer>
