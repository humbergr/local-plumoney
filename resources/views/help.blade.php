@extends('layouts.mvp-layout')

@section('content')
    <main>
        <section class="py-section-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-lg-8 mx-auto">
                        <div class="text-center py-4">
                            <img src="img/landing/question-akb.png" class="img-fluid">
                        </div>
                        <h1 id="questions" class="text-primary font-weight-bold text-center">@lang('help.title')</h1>
                        {{-- <h2 class="text-primary text-center font-weight-bold mb-4">@lang('help.help_you')</h2>
                        <p class="text-justify">@lang('help.help_you_text_1')</p>
                        <p class="text-justify">@lang('help.help_you_text_2')</p>

                        <div class="accordion mt-4" id="faqs-accordion">
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingOne" data-toggle="collapse"
                                     data-target="#questionOne" aria-expanded="false" aria-controls="questionOne">
                                    <h6 class="float-left mb-0">¿A qué países puedo enviar?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>

                                <div id="questionOne" class="collapse" aria-labelledby="headingOne"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Puedes enviar dinero desde y hasta:
                                        <br>
                                        <ul>
                                            <li>
                                                Venezuela
                                            </li>
                                            <li>
                                                Argentina
                                            </li>
                                            <li>
                                                Bolivia
                                            </li>
                                            <li>
                                                Brasil
                                            </li>
                                            <li>
                                                Chile
                                            </li>
                                            <li>
                                                Colombia
                                            </li>
                                            <li>
                                                Perú
                                            </li>
                                            <li>
                                                Paraguay
                                            </li>
                                            <li>
                                                Uruguay
                                            </li>
                                            <li>
                                                México
                                            </li>
                                            <li>
                                                United States
                                            </li>
                                            <li>
                                                Reino Unido
                                            </li>
                                            <li>
                                                Canada
                                            </li>
                                            <li>
                                                España
                                            </li>
                                            <li>
                                                Italia
                                            </li>
                                            <li>
                                                Portugal
                                            </li>
                                        </ul>

                                        Envía dinero a tus amigos y familiares desde tu casa y ellos lo recibirán
                                        directamente en su cuenta bancaria.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingTwo" data-toggle="collapse"
                                     data-target="#questionTwo" aria-expanded="false" aria-controls="questionTwo">
                                    <h6 class="float-left mb-0">¿Que necesito para comenzar a enviar?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionTwo" class="collapse" aria-labelledby="headingTwo"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Debes registrarte en nuestra plataforma. Con un registro simple podrás enviar
                                        hasta <strong>100 USD diarios</strong>.
                                        <br>
                                        <br>
                                        Si deseas enviar montos superiores deberás completar un <strong>perfil de
                                            usuario o empresa</strong>, indicándonos datos que nos ayudarán a validar tu
                                        identidad y brindarte mayor seguridad.
                                        <br>
                                        <br>
                                        Puedes contactarnos si necesitas algunas condiciones especiales. Queremos
                                        escucharte.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingFour" data-toggle="collapse"
                                     data-target="#questionFour" aria-expanded="false" aria-controls="questionFour">
                                    <h6 class="float-left mb-0">¿Cómo puedo pagar?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionFour" class="collapse" aria-labelledby="headingFour"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        Puedes pagar por diferentes métodos:
                                        <br>
                                        <br>
                                        <ul>
                                            <li>Tarjetas de Crédito o Débito</li>
                                            <li>
                                                Diferentes sericios internacionales de pago como:
                                                <ul>
                                                    <li>Venmo</li>
                                                    <li>Cash App</li>
                                                    <li>Payoneer</li>
                                                    <li>Zelle</li>
                                                    <li>PopMoney</li>
                                                    <li>Pandco</li>
                                                </ul>
                                            </li>
                                            <li>Efectivo o Transferencia desde el país en el que estás</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingThree" data-toggle="collapse"
                                     data-target="#questionThree" aria-expanded="false" aria-controls="questionThree">
                                    <h6 class="float-left mb-0">¿Cómo calculan la tasa de cambio?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionThree" class="collapse" aria-labelledby="headingThree"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        En American Kryptos Bank nos basamos en la Blockchain del Bitcoin y es
                                        nuestra herramienta principal para completar las operaciones.
                                        <br>
                                        Sabemos que el precio del Bitcoin varía constantemente sin embargo
                                        hemos desarrollado algoritmos que nos permiten sostener tasas de cambio
                                        estables y muy competitivas en comparación con las casas de cambio habituales.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp">
                                <div class="card-header py-4" id="headingFive" data-toggle="collapse"
                                     data-target="#questionFive" aria-expanded="false" aria-controls="questionFive">
                                    <h6 class="float-left mb-0">¿Quienes son American Kryptos Bank?</h6>
                                    <i class="fa fa-angle-down float-right"></i>
                                </div>
                                <div id="questionFive" class="collapse show" aria-labelledby="headingFive"
                                     data-parent="#faqs-accordion">
                                    <div class="card-body pt-0">
                                        American Kryptos Bank (AKB) es una empresa ubicada en los Estados Unidos,
                                        específicamente en el estado de Florida. Un grupo de empresarios y jóvenes
                                        amantes de la tecnología decidimos desarrollar esta Fintech para ayudar a
                                        oxigenar mercados como el venezolano y otros más en las mismas posibles
                                        condiciones, y permitirles a los ciudadanos conservar el valor de su dinero
                                        o movilizar éste entre diversas divisas.
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 mx-auto" id="questions">
                        <div align="left" class="r-border">
                            <h5 class="text-secondary font-weight-bold cursor" id="m-info"> <i class="fa fa-angle-right"></i> @lang('help.info')</h5>
                            <h5 class="text-primary font-weight-bold cursor" id="m-registry"> <i class="fa fa-angle-right"></i> @lang('help.registry')</h5>
                            <h5 class="text-primary font-weight-bold cursor" id="m-wallet"> <i class="fa fa-angle-right"></i> @lang('help.wallet')</h5>
                            <h5 class="text-primary font-weight-bold cursor" id="m-withdraw_exchange"> <i class="fa fa-angle-right"></i> @lang('help.withdraw_exchange')</h5>
                            <h5 class="text-primary font-weight-bold cursor" id="m-transactions"> <i class="fa fa-angle-right"></i> @lang('help.transactions')</h5>
                            <h5 class="text-primary font-weight-bold cursor" id="m-other_platforms"> <i class="fa fa-angle-right"></i> @lang('help.other_platforms')</h5>
                            <h5 class="text-primary font-weight-bold cursor" id="m-security"> <i class="fa fa-angle-right"></i> @lang('help.security')</h5>
                            <h5 class="text-primary font-weight-bold cursor" id="m-cryptocurrencies"> <i class="fa fa-angle-right"></i> @lang('help.cryptocurrencies')</h5>
                            <h5 class="text-primary font-weight-bold cursor" id="m-company"> <i class="fa fa-angle-right"></i> @lang('help.company')</h5>
                        </div>
                    </div>
                    <div class="col-lg-8 mx-auto" id="answers" >
                        <div align="center">
                            <h4 class="text-secondary font-weight-bold" id="t-answers"> <i class="fa fa-angle-right"></i> @lang('help.info')</h4>
                        </div>
                        <div id="info">
                            <div class="accordion" id="faqs-1">
                                <div class="card">
                                    <div class="card-header py-4" id="q1" data-toggle="collapse"
                                            data-target="#a1" aria-expanded="false" aria-controls="a1">
                                        <h6 class="float-left mb-0">@lang('help.1')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a1" class="collapse" aria-labelledby="q1"
                                            data-parent="#faqs-1">
                                        <div class="card-body pt-0">
                                            @lang('help.1-1')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-2">
                                <div class="card">
                                    <div class="card-header py-4" id="q2" data-toggle="collapse"
                                            data-target="#a2" aria-expanded="false" aria-controls="a2">
                                        <h6 class="float-left mb-0">@lang('help.2')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a2" class="collapse" aria-labelledby="q2"
                                            data-parent="#faqs-2">
                                        <div class="card-body pt-0">
                                            @lang('help.2-1')
                                            <ul>
                                                <br>a. @lang('help.2-1a')
                                                <br>b. @lang('help.2-1b')
                                                <br>c. @lang('help.2-1c')
                                                <br>d. @lang('help.2-1d')
                                                <br>e. @lang('help.2-1e')
                                                <br>f. @lang('help.2-1f')
                                            </ul>
                                            @lang('help.2-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-3">
                                <div class="card">
                                    <div class="card-header py-4" id="q3" data-toggle="collapse"
                                            data-target="#a3" aria-expanded="false" aria-controls="a3">
                                        <h6 class="float-left mb-0">@lang('help.3')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a3" class="collapse" aria-labelledby="q3"
                                            data-parent="#faqs-3">
                                        <div class="card-body pt-0">
                                            @lang('help.3-1')
                                            <ul>
                                                <br>@lang('help.3-1a')
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="registry">
                            <div class="accordion" id="faqs-4">
                                <div class="card">
                                    <div class="card-header py-4" id="q4" data-toggle="collapse"
                                            data-target="#a4" aria-expanded="false" aria-controls="a4">
                                        <h6 class="float-left mb-0">@lang('help.4')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a4" class="collapse" aria-labelledby="q4"
                                            data-parent="#faqs-4">
                                        <div class="card-body pt-0">
                                            @lang('help.4-1')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-5">
                                <div class="card">
                                    <div class="card-header py-4" id="q5" data-toggle="collapse"
                                            data-target="#a5" aria-expanded="false" aria-controls="a5">
                                        <h6 class="float-left mb-0">@lang('help.5')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a5" class="collapse" aria-labelledby="q5"
                                            data-parent="#faqs-5">
                                        <div class="card-body pt-0">
                                            @lang('help.5-1')
                                            <ul>
                                                <br>a. @lang('help.5-1a')
                                                <br>b. @lang('help.5-1b')
                                                <br>c. @lang('help.5-1c')
                                            </ul>
                                            @lang('help.5-2')
                                            <br>@lang('help.5-3')
                                            <ul>
                                                <br>a. @lang('help.5-3a')
                                                <br>b. @lang('help.5-3b')
                                                <br>c. @lang('help.5-3c')
                                                <br>d. @lang('help.5-3d')
                                            </ul>
                                            @lang('help.5-4')
                                            <br>@lang('help.5-5')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-6">
                                <div class="card">
                                    <div class="card-header py-4" id="q6" data-toggle="collapse"
                                            data-target="#a6" aria-expanded="false" aria-controls="a6">
                                        <h6 class="float-left mb-0">@lang('help.6')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a6" class="collapse" aria-labelledby="q6"
                                            data-parent="#faqs-6">
                                        <div class="card-body pt-0">
                                            @lang('help.6-1')
                                        </div>
                                    </div>
                                </div>
                            </div>                    
                            <div class="accordion" id="faqs-7">
                                <div class="card">
                                    <div class="card-header py-4" id="q7" data-toggle="collapse"
                                            data-target="#a7" aria-expanded="false" aria-controls="a7">
                                        <h7 class="float-left mb-0">@lang('help.7')</h7>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a7" class="collapse" aria-labelledby="q7"
                                            data-parent="#faqs-7">
                                        <div class="card-body pt-0">
                                            @lang('help.7-1')
                                        </div>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                        <div id="wallet">                    
                            <div class="accordion" id="faqs-8">
                                <div class="card">
                                    <div class="card-header py-4" id="q8" data-toggle="collapse"
                                            data-target="#a8" aria-expanded="false" aria-controls="a8">
                                        <h6 class="float-left mb-0">@lang('help.8')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a8" class="collapse" aria-labelledby="q8"
                                            data-parent="#faqs-8">
                                        <div class="card-body pt-0">
                                            @lang('help.8-1')
                                            <ul>
                                                <br>a. @lang('help.8-1a')
                                                <br>b. @lang('help.8-1b')
                                                <br>c. @lang('help.8-1c')
                                                <br>d. @lang('help.8-1d')
                                            </ul>
                                            @lang('help.8-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-9">
                                <div class="card">
                                    <div class="card-header py-4" id="q9" data-toggle="collapse"
                                            data-target="#a9" aria-expanded="false" aria-controls="a9">
                                        <h6 class="float-left mb-0">@lang('help.9')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a9" class="collapse" aria-labelledby="q9"
                                            data-parent="#faqs-9">
                                        <div class="card-body pt-0">
                                            @lang('help.9-1')
                                            <ul>
                                                <br>a. @lang('help.9-1a')
                                                <br>b. @lang('help.9-1b')
                                                <br>c. @lang('help.9-1c')
                                                <br>d. @lang('help.9-1d')
                                                <br>e. @lang('help.9-1e')
                                                <br>f. @lang('help.9-1f')
                                            </ul>
                                            @lang('help.9-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-10">
                                <div class="card">
                                    <div class="card-header py-4" id="q10" data-toggle="collapse"
                                            data-target="#a10" aria-expanded="false" aria-controls="a10">
                                        <h6 class="float-left mb-0">@lang('help.10')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a10" class="collapse" aria-labelledby="q10"
                                            data-parent="#faqs-10">
                                        <div class="card-body pt-0">
                                            @lang('help.10-1')
                                            <ul>
                                                <br>a. @lang('help.10-1a')
                                                <br>b. @lang('help.10-1b')
                                                <br>c. @lang('help.10-1c')
                                                <br>d. @lang('help.10-1d')
                                                <br>e. @lang('help.10-1e')
                                                <br>f. @lang('help.10-1f')
                                                <br>g. @lang('help.10-1g')
                                                <br>h. @lang('help.10-1h')
                                            </ul>
                                            @lang('help.10-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-11">
                                <div class="card">
                                    <div class="card-header py-4" id="q11" data-toggle="collapse"
                                            data-target="#a11" aria-expanded="false" aria-controls="a11">
                                        <h6 class="float-left mb-0">@lang('help.11')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a11" class="collapse" aria-labelledby="q11"
                                            data-parent="#faqs-11">
                                        <div class="card-body pt-0">
                                            @lang('help.11-1')
                                            <ul>
                                                <br>a. @lang('help.11-1a')
                                                <ul>
                                                    <br>i. @lang('help.11-1a-1')
                                                    <br>ii. @lang('help.11-1a-2')
                                                    <br>iii. @lang('help.11-1a-3')
                                                    <br>iv. @lang('help.11-1a-4')
                                                    <br>v. @lang('help.11-1a-5')
                                                    <ul>
                                                        <br>1. @lang('help.11-1a-51')
                                                        <br>2. @lang('help.11-1a-52')
                                                        <br>3. @lang('help.11-1a-53')
                                                        <br>4. @lang('help.11-1a-54')
                                                    </ul>
                                                </ul>
                                                <br>b. @lang('help.11-1b')
                                                <ul>
                                                    <br>i. @lang('help.11-1b-1')
                                                    <br>ii. @lang('help.11-1b-2')
                                                    <br>iii. @lang('help.11-1b-3')
                                                    <br>iv. @lang('help.11-1b-4')
                                                    <br>v. @lang('help.11-1b-5')
                                                    <br>vi. @lang('help.11-1b-6')
                                                </ul>
                                                <br>c. @lang('help.11-1c')
                                                <ul>
                                                    <br>i. @lang('help.11-1c-1')
                                                    <br>ii. @lang('help.11-1c-2')
                                                    <br>iii. @lang('help.11-1c-3')
                                                    <br>iv. @lang('help.11-1c-4')
                                                    <br>v. @lang('help.11-1c-5')
                                                </ul>
                                            </ul>
                                            @lang('help.11-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-12">
                                <div class="card">
                                    <div class="card-header py-4" id="q12" data-toggle="collapse"
                                            data-target="#a12" aria-expanded="false" aria-controls="a12">
                                        <h6 class="float-left mb-0">@lang('help.12')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a12" class="collapse" aria-labelledby="q12"
                                            data-parent="#faqs-12">
                                        <div class="card-body pt-0">
                                            @lang('help.12-1')
                                            <ul>
                                                <br>a. @lang('help.12-1a')
                                                <br>b. @lang('help.12-1b')
                                                <br>c. @lang('help.12-1c')
                                                <br>d. @lang('help.12-1d')
                                                <br>e. @lang('help.12-1e')
                                                <br>f. @lang('help.12-1f')
                                                <br>g. @lang('help.12-1g')
                                                <br>h. @lang('help.12-1h')
                                            </ul>
                                            @lang('help.12-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-13">
                                <div class="card">
                                    <div class="card-header py-4" id="q13" data-toggle="collapse"
                                            data-target="#a13" aria-expanded="false" aria-controls="a13">
                                        <h6 class="float-left mb-0">@lang('help.13')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a13" class="collapse" aria-labelledby="q13"
                                            data-parent="#faqs-13">
                                        <div class="card-body pt-0">
                                            @lang('help.13-1')
                                        </div>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                        <div id="withdraw_exchange">
                            <div class="accordion" id="faqs-14">
                                <div class="card">
                                    <div class="card-header py-4" id="q14" data-toggle="collapse"
                                            data-target="#a14" aria-expanded="false" aria-controls="a14">
                                        <h6 class="float-left mb-0">@lang('help.14')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a14" class="collapse" aria-labelledby="q14"
                                            data-parent="#faqs-14">
                                        <div class="card-body pt-0">
                                            @lang('help.14-1')
                                            <ul>
                                                <br>a. @lang('help.14-1a')
                                                <br>b. @lang('help.14-1b')
                                                <br>c. @lang('help.14-1c')
                                                <br>d. @lang('help.14-1d')
                                                <br>e. @lang('help.14-1e')
                                                <br>f. @lang('help.14-1f')
                                                <br>g. @lang('help.14-1g')
                                            </ul>
                                            @lang('help.14-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-15">
                                <div class="card">
                                    <div class="card-header py-4" id="q15" data-toggle="collapse"
                                            data-target="#a15" aria-expanded="false" aria-controls="a15">
                                        <h6 class="float-left mb-0">@lang('help.15')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a15" class="collapse" aria-labelledby="q15"
                                            data-parent="#faqs-15">
                                        <div class="card-body pt-0">
                                            @lang('help.15-1')
                                            <ul>
                                                <br>a. @lang('help.15-1a')
                                                <br>b. @lang('help.15-1b')
                                                <br>c. @lang('help.15-1c')
                                                <br>d. @lang('help.15-1d')
                                                <br>e. @lang('help.15-1e')
                                                <br>f. @lang('help.15-1f')
                                                <br>g. @lang('help.15-1g')
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-16">
                                <div class="card">
                                    <div class="card-header py-4" id="q16" data-toggle="collapse"
                                            data-target="#a16" aria-expanded="false" aria-controls="a16">
                                        <h6 class="float-left mb-0">@lang('help.16')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a16" class="collapse" aria-labelledby="q16"
                                            data-parent="#faqs-16">
                                        <div class="card-body pt-0">
                                            @lang('help.16-1')
                                            <ul>
                                                <br>a. @lang('help.16-1a')
                                                <br>b. @lang('help.16-1b')
                                                <br>c. @lang('help.16-1c')
                                                <br>d. @lang('help.16-1d')
                                                <br>e. @lang('help.16-1e')
                                            </ul>
                                            @lang('help.16-2')
                                            <ul>
                                                <br>a. @lang('help.16-2a')
                                                <br>b. @lang('help.16-2b')
                                                <br>c. @lang('help.16-2c')
                                                <br>d. @lang('help.16-2d')
                                                <br>e. @lang('help.16-2e')
                                                <br>f. @lang('help.16-2f')
                                            </ul>
                                            @lang('help.16-3')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="transactions">
                            <div class="accordion" id="faqs-17">
                                <div class="card">
                                    <div class="card-header py-4" id="q17" data-toggle="collapse"
                                            data-target="#a17" aria-expanded="false" aria-controls="a17">
                                        <h6 class="float-left mb-0">@lang('help.17')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a17" class="collapse" aria-labelledby="q17"
                                            data-parent="#faqs-17">
                                        <div class="card-body pt-0">
                                            @lang('help.17-1')
                                            <ul>
                                                <br>a. @lang('help.17-1a')
                                                <br>b. @lang('help.17-1b')
                                            </ul>
                                            @lang('help.17-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-18">
                                <div class="card">
                                    <div class="card-header py-4" id="q18" data-toggle="collapse"
                                            data-target="#a18" aria-expanded="false" aria-controls="a18">
                                        <h6 class="float-left mb-0">@lang('help.18')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a18" class="collapse" aria-labelledby="q18"
                                            data-parent="#faqs-18">
                                        <div class="card-body pt-0">
                                            @lang('help.18-1')
                                            <br>@lang('help.18-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-19">
                                <div class="card">
                                    <div class="card-header py-4" id="q19" data-toggle="collapse"
                                            data-target="#a19" aria-expanded="false" aria-controls="a19">
                                        <h6 class="float-left mb-0">@lang('help.19')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a19" class="collapse" aria-labelledby="q19"
                                            data-parent="#faqs-19">
                                        <div class="card-body pt-0">
                                            @lang('help.19-1')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="other_platforms">
                            <div class="accordion" id="faqs-20">
                                <div class="card">
                                    <div class="card-header py-4" id="q20" data-toggle="collapse"
                                            data-target="#a20" aria-expanded="false" aria-controls="a20">
                                        <h6 class="float-left mb-0">@lang('help.20')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a20" class="collapse" aria-labelledby="q20"
                                            data-parent="#faqs-20">
                                        <div class="card-body pt-0">
                                            @lang('help.20-1')
                                            <ul>
                                                <br>a. @lang('help.20-1a')
                                                <br>b. @lang('help.20-1b')
                                                <br>c. @lang('help.20-1c')
                                            </ul>
                                            @lang('help.20-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-21">
                                <div class="card">
                                    <div class="card-header py-4" id="q21" data-toggle="collapse"
                                            data-target="#a21" aria-expanded="false" aria-controls="a21">
                                        <h6 class="float-left mb-0">@lang('help.21')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a21" class="collapse" aria-labelledby="q21"
                                            data-parent="#faqs-21">
                                        <div class="card-body pt-0">
                                            @lang('help.21-1')
                                            <ul>
                                                <br>a. @lang('help.21-1a')
                                                <br>b. @lang('help.21-1b')
                                                <br>c. @lang('help.21-1c')
                                                <br>d. @lang('help.21-1d')
                                                <br>e. @lang('help.21-1e')
                                                <br>f. @lang('help.21-1f')
                                            </ul>
                                            @lang('help.21-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-22">
                                <div class="card">
                                    <div class="card-header py-4" id="q22" data-toggle="collapse"
                                            data-target="#a22" aria-expanded="false" aria-controls="a22">
                                        <h6 class="float-left mb-0">@lang('help.22')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a22" class="collapse" aria-labelledby="q22"
                                            data-parent="#faqs-22">
                                        <div class="card-body pt-0">
                                            @lang('help.22-1')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="security">
                            <div class="accordion" id="faqs-23">
                                <div class="card">
                                    <div class="card-header py-4" id="q23" data-toggle="collapse"
                                            data-target="#a23" aria-expanded="false" aria-controls="a23">
                                        <h6 class="float-left mb-0">@lang('help.23')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a23" class="collapse" aria-labelledby="q23"
                                            data-parent="#faqs-23">
                                        <div class="card-body pt-0">
                                            @lang('help.23-1')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-24">
                                <div class="card">
                                    <div class="card-header py-4" id="q24" data-toggle="collapse"
                                            data-target="#a24" aria-expanded="false" aria-controls="a24">
                                        <h6 class="float-left mb-0">@lang('help.24')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a24" class="collapse" aria-labelledby="q24"
                                            data-parent="#faqs-24">
                                        <div class="card-body pt-0">
                                            @lang('help.24-1')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="cryptocurrencies">
                            <div class="accordion" id="faqs-25">
                                <div class="card">
                                    <div class="card-header py-4" id="q25" data-toggle="collapse"
                                            data-target="#a25" aria-expanded="false" aria-controls="a25">
                                        <h6 class="float-left mb-0">@lang('help.25')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a25" class="collapse" aria-labelledby="q25"
                                            data-parent="#faqs-25">
                                        <div class="card-body pt-0">
                                            @lang('help.25-1')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="company">
                            <div class="accordion" id="faqs-26">
                                <div class="card">
                                    <div class="card-header py-4" id="q26" data-toggle="collapse"
                                            data-target="#a26" aria-expanded="false" aria-controls="a26">
                                        <h6 class="float-left mb-0">@lang('help.26')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a26" class="collapse" aria-labelledby="q26"
                                            data-parent="#faqs-26">
                                        <div class="card-body pt-0">
                                            @lang('help.26-1')
                                            <ul>
                                                <br>a. @lang('help.26-1a')
                                                <br>b. @lang('help.26-1b')
                                                <br>c. @lang('help.26-1c')
                                                <br>d. @lang('help.26-1d')
                                            </ul>
                                            @lang('help.26-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-27">
                                <div class="card">
                                    <div class="card-header py-4" id="q27" data-toggle="collapse"
                                            data-target="#a27" aria-expanded="false" aria-controls="a27">
                                        <h6 class="float-left mb-0">@lang('help.27')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a27" class="collapse" aria-labelledby="q27"
                                            data-parent="#faqs-27">
                                        <div class="card-body pt-0">
                                            @lang('help.27-1')
                                            <ul>
                                                <br>a. @lang('help.27-1a')
                                                <br>b. @lang('help.27-1b')
                                                <br>c. @lang('help.27-1c')
                                                <br>d. @lang('help.27-1d')
                                            </ul>
                                            @lang('help.27-2')
                                            <ul>
                                                <br>a. @lang('help.27-2a')
                                                <br>b. @lang('help.27-2b')
                                                <br>c. @lang('help.27-2c')
                                                <br>d. @lang('help.27-2d')
                                                <br>e. @lang('help.27-2e')
                                                <br>f. @lang('help.27-2f')
                                            </ul>
                                            @lang('help.27-3')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-28">
                                <div class="card">
                                    <div class="card-header py-4" id="q28" data-toggle="collapse"
                                            data-target="#a28" aria-expanded="false" aria-controls="a28">
                                        <h6 class="float-left mb-0">@lang('help.28')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a28" class="collapse" aria-labelledby="q28"
                                            data-parent="#faqs-28">
                                        <div class="card-body pt-0">
                                            @lang('help.28-1')
                                            <ul>
                                                <br>a. @lang('help.28-1a')
                                                <br>b. @lang('help.28-1b')
                                                <br>c. @lang('help.28-1c')
                                                <br>d. @lang('help.28-1d')
                                                <br>e. @lang('help.28-1e')
                                                <br>f. @lang('help.28-1f')
                                            </ul>
                                            @lang('help.28-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="faqs-29">
                                <div class="card">
                                    <div class="card-header py-4" id="q29" data-toggle="collapse"
                                            data-target="#a29" aria-expanded="false" aria-controls="a29">
                                        <h6 class="float-left mb-0">@lang('help.29')</h6>
                                        <i class="fa fa-angle-down float-right"></i>
                                    </div>
                                    <div id="a29" class="collapse" aria-labelledby="q29"
                                            data-parent="#faqs-29">
                                        <div class="card-body pt-0">
                                            @lang('help.29-1')
                                            <ul>
                                                <br>a. @lang('help.29-1a')
                                                <br>b. @lang('help.29-1b')
                                                <br>c. @lang('help.29-1c')
                                                <br>d. @lang('help.29-1d')
                                                <br>e. @lang('help.29-1e')
                                            </ul>
                                            @lang('help.29-2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="mobile">
                    <div class="col-md-10 col-lg-8 mx-auto">
                        <div class="text-center mt-5 wow fadeIn" style="visibility: visible; animation-name: fadeIn;">
                            <button id="b-up" class="btn btn-secondary btn-pill px-lg-4">@lang('help.up')</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(function(){
        $('#mobile').hide();
        $('#registry,#wallet,#withdraw_exchange,#transactions,#other_platforms,#security,#cryptocurrencies,#company').hide();
        $('#m-info').click(function(){
            $('#m-registry,#m-wallet,#m-withdraw_exchange,#m-transactions,#m-other_platforms,#m-security,#m-cryptocurrencies,#m-company').removeClass('text-secondary').addClass('text-primary');
            $('#registry,#wallet,#withdraw_exchange,#transactions,#other_platforms,#security,#cryptocurrencies,#company').hide();
            $('#info').show().focus();
            $('#m-info').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-info').html());
        });
        $('#m-registry').click(function(){
            $('#m-info,#m-wallet,#m-withdraw_exchange,#m-transactions,#m-other_platforms,#m-security,#m-cryptocurrencies,#m-company').removeClass('text-secondary').addClass('text-primary');
            $('#info,#wallet,#withdraw_exchange,#transactions,#other_platforms,#security,#cryptocurrencies,#company').hide();
            $('#registry').show().focus();
            $('#m-registry').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-registry').html());
        });
        $('#m-wallet').click(function(){
            $('#m-registry,#m-info,#m-withdraw_exchange,#m-transactions,#m-other_platforms,#m-security,#m-cryptocurrencies,#m-company').removeClass('text-secondary').addClass('text-primary');
            $('#registry,#info,#withdraw_exchange,#transactions,#other_platforms,#security,#cryptocurrencies,#company').hide();
            $('#wallet').show().focus();
            $('#m-wallet').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-wallet').html());
        });
        $('#m-withdraw_exchange').click(function(){
            $('#m-registry,#m-wallet,#m-info,#m-transactions,#m-other_platforms,#m-security,#m-cryptocurrencies,#m-company').removeClass('text-secondary').addClass('text-primary');
            $('#registry,#wallet,#info,#transactions,#other_platforms,#security,#cryptocurrencies,#company').hide();
            $('#withdraw_exchange').show().focus();
            $('#m-withdraw_exchange').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-withdraw_exchange').html());
        });
        $('#m-transactions').click(function(){
            $('#m-registry,#m-wallet,#m-withdraw_exchange,#m-info,#m-other_platforms,#m-security,#m-cryptocurrencies,#m-company').removeClass('text-secondary').addClass('text-primary');
            $('#registry,#wallet,#withdraw_exchange,#info,#other_platforms,#security,#cryptocurrencies,#company').hide();
            $('#transactions').show().focus();
            $('#m-transactions').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-transactions').html());
        });
        $('#m-other_platforms').click(function(){
            $('#m-registry,#m-wallet,#m-withdraw_exchange,#m-transactions,#m-info,#m-security,#m-cryptocurrencies,#m-company').removeClass('text-secondary').addClass('text-primary');
            $('#registry,#wallet,#withdraw_exchange,#transactions,#info,#security,#cryptocurrencies,#company').hide();
            $('#other_platforms').show().focus();
            $('#m-other_platforms').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-other_platforms').html());            
        });
        $('#m-security').click(function(){
            $('#m-registry,#m-wallet,#m-withdraw_exchange,#m-transactions,#m-other_platforms,#m-info,#m-cryptocurrencies,#m-company').removeClass('text-secondary').addClass('text-primary');
            $('#registry,#wallet,#withdraw_exchange,#transactions,#other_platforms,#info,#cryptocurrencies,#company').hide();
            $('#security').show().focus();
            $('#m-security').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-security').html());
        });
        $('#m-cryptocurrencies').click(function(){
            $('#m-registry,#m-wallet,#m-withdraw_exchange,#m-transactions,#m-other_platforms,#m-security,#m-info,#m-company').removeClass('text-secondary').addClass('text-primary');
            $('#registry,#wallet,#withdraw_exchange,#transactions,#other_platforms,#security,#info,#company').hide();
            $('#cryptocurrencies').show().focus();
            $('#m-cryptocurrencies').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-cryptocurrencies').html());
        });
        $('#m-company').click(function(){
            $('#m-registry,#m-wallet,#m-withdraw_exchange,#m-transactions,#m-other_platforms,#m-security,#m-cryptocurrencies,#m-info').removeClass('text-secondary').addClass('text-primary');
            $('#registry,#wallet,#withdraw_exchange,#transactions,#other_platforms,#security,#cryptocurrencies,#info').hide();
            $('#company').show().focus();
            $('#m-company').removeClass('text-primary').addClass('text-secondary');
            if ($(window).width() < 991) {
                   $([document.documentElement, document.body]).animate({
                    scrollTop: $("#answers").offset().top
                }, 500);
                $('#mobile').show();
            }
            $('#t-answers').empty().append($('#m-company').html());
        });
        $('#b-up').click(function(){            
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#questions").offset().top
            }, 500);            
        });
    });
</script>
<style>    
    body{
        background-color: white;
    }
    .cursor{
        cursor: pointer;
    }
    .accordion{
        text-align: justify;
    }
    .r-border{
        border: 15px solid #f4f4f9!important;
        border-radius: 20px;
        background-color: #f4f4f9;
    }
</style>