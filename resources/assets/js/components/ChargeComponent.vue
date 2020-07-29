<script src="../walletChargesJS.js"></script>

<template lang="html">
    <div class="card-body py-4 py-lg-4">
        <div class="row justify-content-center mt-4 mb-4">
            <div v-for="wallet in walletsObject" class="col-3 col-md-3 col-xl-2 px-1">
                <a href="javascript:void(0);"
                   class="cardBank mb-2 mb-md-0px-1"
                   v-on:click="changeCurrency(wallet['currency'])"
                   :class="{'--active': (currency === wallet['currency'])}">
                    <img :src="'/img/landing/wallet-'+ wallet['currency'] +'-primary.svg'"
                         alt="GBP Icon"
                         class="d-inline-block mb-2">
                    <h6 class="cardBank__title" v-if="wallet['currency'] === 'USD'">Dólares</h6>
                    <h6 class="cardBank__title" v-if="wallet['currency'] === 'GBP'">Libras Esterlinas</h6>
                    <h6 class="cardBank__title" v-if="wallet['currency'] === 'EUR'">Euros</h6>
                    <div class="cardBank__info">
                        {{formatMoney(wallet['numbers']['available'])}} {{wallet['currency']}}
                    </div>
                </a>
            </div>
        </div>

        <nav id="wallets-nav"
             class="nav nav-fill flex-nowrap align-items-center border-top border-bottom bg-white mx-n3 mx-md-0 sticky-top">
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3 border-right" :href="transactionsUrl">
                <div>Historial</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3 active" :href="chargeUrl">
                <img src="/img/landing/recharge-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Cargar {{currencyName}}</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="WithdrawUrl">
                <img src="/img/landing/withdraw-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Retirar {{currencyName}}</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="TransferUrl">
                <img src="/img/landing/transfer-blue.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Transferir {{currencyName}}</div>
            </a>
        </nav>

        <!-- transactions history  -->
        <div class="py-4">
            <div class="row">
                <div class="col-md-8  __overlay_container">
                    <div class="card shadow-none wow fadeIn">
                        <div class="card-body py-4">
                            <form class="" :action="formUrl" ref="form" method="post">
                                <input type="hidden" name="_token" v-model="csrf">
                                <input type="hidden" name="sender" v-model="sender" value="">
                                <input type="hidden" name="receiver" v-model="receiver" value="">
                                <input type="hidden" name="exchange-p-id" v-model="price_data_id" value="">
                                <h6 class="text-primary font-weight-bold mb-3">
                                    <span class="__step_counter">1</span>
                                    Selecciona el país origen de tu recarga
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sm-origen" class="text-primary">Origen</label>
                                            <select id="sm-origen"
                                                    class="custom-select flag-selector flag-selector--full"
                                                    v-model="sender_country"
                                                    name="sender_country"
                                                    v-on:change="getPrice">
                                                <optgroup label="Latino América">
                                                    <option value="Chile" data-flag="/img/landing/flags/cl.svg">
                                                        Chile
                                                    </option>
                                                    <option value="Venezuela" data-flag="/img/landing/flags/ve.svg">
                                                        Venezuela
                                                    </option>
                                                    <option value="Argentina" data-flag="/img/landing/flags/ar.svg">
                                                        Argentina
                                                    </option>
                                                    <option value="Colombia" data-flag="/img/landing/flags/co.svg">
                                                        Colombia
                                                    </option>
                                                    <option value="Peru" data-flag="/img/landing/flags/pe.svg">
                                                        Perú
                                                    </option>
                                                    <!--  <option value="Mexico" data-flag="/img/landing/flags/mx.svg">
                                                          Mexico
                                                      </option> -->
                                                    <option value="Dominican Republic"
                                                            data-flag="/img/landing/flags/do.svg">
                                                        República Dominicana
                                                    </option>
                                                    <!--  <option value="CRC" data-flag="/img/landing/flags/cr.svg">
                                                          Costa Rica
                                                      </option> -->
                                                    <option value="Panama" data-flag="/img/landing/flags/pa.svg">
                                                        Panama
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Norte America">
                                                    <option value="United States" data-flag="/img/landing/flags/us.svg"
                                                            selected>United States
                                                    </option>
                                                    <option value="Canada" data-flag="/img/landing/flags/ca.svg">
                                                        Canada
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Europa">
                                                    <option value="Spain" data-flag="/img/landing/flags/es.svg">
                                                        España
                                                    </option>
                                                    <option value="Portugal" data-flag="/img/landing/flags/pt.svg">
                                                        Portugal
                                                    </option>
                                                    <option value="Italy" data-flag="/img/landing/flags/it.svg">
                                                        Italia
                                                    </option>
                                                    <option value="France" data-flag="/img/landing/flags/fr.svg">
                                                        Francia
                                                    </option>
                                                    <option value="United Kingdom"
                                                            data-flag="/img/landing/flags/gb.svg">
                                                        Reino Unido
                                                    </option>
                                                </optgroup>
                                            </select>
                                            <div class="small text-right text-danger mt-1">Campo Obligatorio</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="sm-monto" class="text-primary">
                                                Ingrese el monto a cargar
                                            </label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">{{sender}}</div>
                                                </div>
                                                <input type="text"
                                                       class="form-control form-control-font-lg"
                                                       name="to_send"
                                                       @keyup="verifyMaxTransfer"
                                                       @blur="verifyMaxTransfer"
                                                       placeholder="000,000.00">
                                                <div v-if="!minAmountBoolean" class="small text-right text-danger mt-1">
                                                    Debe recargar un mínimo o más de 15 dólares o equivalente.
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">Monto inválido</div>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-primary font-weight-bold mb-3">
                                    <span class="__step_counter">2</span>
                                    Monto en Dólares y tasa de cambio
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label for="sm-monto" class="text-primary">
                                                Monto a cargar en Dólares
                                            </label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">USD</div>
                                                </div>
                                                <input type="text"
                                                       class="form-control custom-readonly"
                                                       style="background: url('/img/icons/loadExhanges-32px.gif') no-repeat center left; box-shadow: none"
                                                       v-if="loadingSend">
                                                <input type="text" id="sm-monto"
                                                       class="form-control "
                                                       :value="toReceive"
                                                       v-else
                                                       name="to_receive"
                                                       readonly
                                                       placeholder="000,000.00">
                                            </div>
                                            <div class="invalid-feedback">Monto inválido</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 align-content-center" v-if="loadingSend">
                                        <h6 class="text-primary text-right font-weight-bold mb-0 mb-md-2"
                                            style="font-size: 14px">
                                            <img src="/img/icons/loadExhanges.gif" alt="Cargando tasa...">
                                            Cargando Tasa...
                                        </h6>
                                    </div>
                                    <div class="col-md-6 align-content-center d-flex" v-else>
                                        <h6 class="text-primary text-right font-weight-bold mb-0 mb-md-2"
                                            style="font-size: 14px"
                                            v-if="recomended_price > 1">
                                            Tasa de cambio:
                                            1 {{sender}} = {{recommendedUtility(recomended_price)}} {{receiver}}
                                        </h6>
                                        <h6 class="text-primary text-right font-weight-bold mb-0 mb-md-2"
                                            style="font-size: 14px"
                                            v-else>
                                            Tasa de cambio
                                            1 {{receiver}} = {{recommendedUtility(recomended_price)}} {{sender}}
                                        </h6>
                                    </div>
                                </div>

                                <add-card :user=userObject
                                          :trans-tracking=transTracking
                                          :sender_country="sender_country"
                                          :force_loading="force_loading"
                                          :no_viable_country=no_viable_country
                                          :forbidden_chat="forbiddenChat"
                                          :qbpay="qbpay"
                                          :on_wallets="true"
                                          @updatePaymentMethod="onPaymentMethodUpdate"/>
                            </form>
                        </div>
                    </div>
                </div>
                <aside class="col-md-4">
                    <div class="card body-bg-color rounded text-primary sticky-top" style="top: 2rem;">
                        <div class="px-3 px-md-4 py-3">
                            <h6 class="text-primary font-weight-bold text-uppercase mb-4">Resumen de la transacción</h6>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="small text-truncate mr-2">Monto a transferir</div>
                                <div class="font-14 font-weight-bold text-truncate">
                                    {{formatMoneyNumeral(to_send)}}
                                    {{sender}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="small text-truncate mr-2">Comisión</div>
                                <div class="font-14 font-weight-bold text-truncate">
                                    {{formatMoneyNumeral(fees)}}
                                    {{sender}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="small text-truncate mr-2">Total transferencia</div>
                                <div class="font-14 font-weight-bold text-truncate">
                                    {{formatMoneyNumeral(payment_total)}}
                                    {{sender}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="small mr-2" style="text-align: justify;">
                                    * Estos valores son aproximados y pueden variar en el cobro final
                                    debitado de su estado de cuenta dependiendo de las comisiones bancarias o del proveedor de la tarjeta.
                                </div>
                            </div>
                        </div>
                        <div class="px-3 px-md-4 py-3">
                            Monto a Cargar<br>
                            <h4 class="mb-0">{{receiver}} {{toReceive}}</h4>
                            <div>
                                <h6 class="text-primary text-left mb-0 mb-md-2"
                                    v-if="recomended_price > 1">
                                    Tasa de cambio:
                                    1 {{sender}} = {{recommendedUtility(recomended_price)}} {{receiver}}
                                </h6>
                                <h6 class="text-primary text-left mb-0 mb-md-2"
                                    v-else>
                                    Tasa de cambio
                                    1 {{receiver}} = {{recommendedUtility(recomended_price)}} {{sender}}
                                </h6>
                            </div>
                            <!--<div>
                                <h6 class="text-primary mb-0 mb-md-2"
                                    style="font-size: 14px; text-align: justify;">
                                    * Tras 72 horas la recarga estará disponible cómo saldo positivo en su billetera.
                                </h6>
                            </div>-->
                        </div>
                        <div class="px-3 px-md-4 py-4 border-top border-primary">
                            <button class="btn btn-secondary btn-pill btn-block"
                                    :class="{'disabled' : ( !payment_method || disableButton || forbiddenByAmount !== 0 || forbiddenByHours !== 0 || !minAmountBoolean)}"
                                    v-if="noDuplicate === false"
                                    v-on:click="submitForm">
                              <span class="mr-2">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="27" height="28" viewBox="0 0 27 28"><g><g><g><g><g><g><g><path
                                          fill="#fff"
                                          d="M25.589 16.039c0 .302-.022.583-.065.863-.237 1.662-1.1 3.13-2.417 4.1a6.142 6.142 0 0 1-4.619 1.21c-3.065-.432-5.374-3.087-5.374-6.173 0-.28.021-.583.065-.864a6.232 6.232 0 0 1 7.036-5.309c3.064.432 5.374 3.086 5.374 6.173zm-13.338.021c0 3.518 2.633 6.54 6.108 7.015a7.072 7.072 0 0 0 5.266-1.36 6.982 6.982 0 0 0 2.762-4.683c.044-.324.065-.67.065-.993 0-3.518-2.633-6.54-6.13-7.015-3.863-.54-7.467 2.159-8.007 6.044-.043.323-.064.669-.064.992z"/></g></g></g></g></g><g><g><g><g><g><g><path
                                          fill="#fff"
                                          d="M17.14 14.582c0-.971.69-1.662 1.834-1.748V11.69h.647v1.165a3.346 3.346 0 0 1 1.943.928l-.497.605c-.54-.518-1.23-.799-1.834-.799-.713 0-1.23.346-1.23.95 0 .69.539.906 1.424 1.187 1.036.345 1.986.669 1.986 1.791 0 .928-.648 1.619-1.813 1.684v1.208h-.626v-1.251c-.583-.108-1.209-.367-1.62-.691l.476-.583c.496.367 1.144.561 1.597.561.669 0 1.122-.302 1.122-.863 0-.604-.583-.82-1.381-1.08-1.144-.388-2.029-.69-2.029-1.92z"/></g></g></g></g></g></g><g><g><g><g><g><path
                                          fill="#fff"
                                          d="M12.833 5.614H9.596v9.842c0 4.662 2.763 8.914 7.014 10.813l.497.216h-.928c-6.044 0-10.964-4.921-10.964-10.964V5.614H2.279L7.567 1.32zm-8.503.864v9.043c0 6.518 5.31 11.827 11.827 11.827h4.986l-4.187-1.878c-3.95-1.748-6.497-5.697-6.497-10.014V6.478h4.835L7.567.218-.18 6.479z"/></g></g></g></g></g></g></g></svg>
                              </span>Cargar Dólares
                            </button>
                            <span class="__loading_send_money" v-else>
                                <img src="/images/ajax-loader.gif" alt="Cargando">
                                En proceso
                            </span>
                            <p v-if="forbiddenByHours === 1" class="__warning_time">
                                En este momento el sitio web no está abierto a operaciones.
                                <br>
                                <br>
                                <span>
                                    Nuestro horario de atención es:
                                    <br><strong>Lunes a Viernes</strong>
                                    <br><strong>Desde las 09:00 AM hasta las 07:00 PM.</strong>
                                    <br><strong>Sábados</strong>
                                    <br><strong>Desde las 09:00 AM hasta las 01:00 PM.</strong>
                                </span>
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .slick-slide {
        margin: 5px;
    }

    @media (max-width: 640px) {
        .__del_destiny {
            margin-top: .8rem;
            margin-right: -1rem !important;
        }
    }

    .__slick_alerts {
        margin-top: -24px;
    }

    .__alert_messages {
        text-align: center;
        font-size: 1rem;
        padding: 8px;
        font-weight: 700;
        color: red;
    }

    .__greetings_warning {
        display: block;
        font-weight: 700;
        color: red;
        font-size: 16px;

        a {
            color: #426cff;
        }
    }

    .__greetings {
        display: block;
        font-size: 16px;
    }

    .__assist_text {
        text-align: center;
        font-size: 16px;
        padding: 8px;
        color: #e68108;
    }

    .__step_counter {
        background: #303392;
        margin-right: 8px;
        border-radius: 50px;
        font-size: 12px;
        color: #fff;
        width: 22px;
        height: 22px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .__flag_mobile,
    .__flag_office {
        background-size: auto 35px;
        background-position: 35px;
        padding: 0.375rem 45px 0.375rem .25rem;
        color: #333;
        max-width: 71px;

        @-moz-document url-prefix() {
            font-size: 12px;
        }
    }

    .__resume_sticky {
        position: sticky;
        top: 0;
        z-index: 1020;

        @media (max-width: 640px) {
            position: static;
        }

        a.btn:not([class*="btn-outline-"]):not([class*="btn-default"]):not([class*="btn-light"]) {
            &.disabled {
                background-color: #d6d6d6 !important;
                color: #555 !important;

                img {
                    filter: brightness(0%);
                }
            }
        }
    }

    .__overlay_container {
        z-index: 1049;
    }

    .jconfirm-title-c {
        text-align: center;
    }

    .jconfirm-content {
        text-align: center;
        overflow: hidden !important;
    }

    .jconfirm-type-red {
        .jconfirm-title-c {
            color: #e74c3c;
        }

        .jconfirm-buttons {
            display: none;
        }
    }

    .__warning_ammount {
        margin-top: 16px;
        margin-bottom: 0;
        color: #e74c3c;
        text-align: justify;
        font-size: 14px;
    }

    .__warning_time {
        margin-top: 16px;
        margin-bottom: 0;
        color: #e74c3c;
        text-align: justify;
        font-size: 14px;

        span {
            color: #303392 !important;
        }
    }

    .disabled {
        pointer-events: none;
        position: relative;

        * {
            pointer-events: none;
        }
    }

    .__loading_send_money {
        padding: 1.5rem;
        border-radius: 6px;
        background: #fff;
        display: block;
        margin: 0 auto;
        text-align: center;

        img {
            margin-right: 1rem;
        }
    }
</style>
