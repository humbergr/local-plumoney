<script src="../add-card.js"></script>

<template lang="html">
    <section id="payment-method" class="mt-4">
        <div class="loader--wrapper" :class="[loader || force_loading ? '--loading' : '']">
            <vue-toastr ref="toastr"></vue-toastr>
            <input type="hidden" v-model="pay_method" name="pay_method" value="">
            <input type="hidden" v-model="pay_method_country" name="pay_method_country" value="">
            <input type="hidden" v-model="transTracking" name="tracking_id" value="">

            <h5 class="text-primary font-weight-bold mb-3">
                <span class="__step_counter">3</span>
                Elige el método que quieres usar para pagar 
                <br>
                <small style="font-size: 12px">Haz click para seleccionar alguno.</small>
            </h5>
           
            <div class="row px-3"
                 v-if="sender_country !== 'Venezuela'">
                <div class="col-6 mb-3 px-1"
                     data-toggle="tooltip"
                     :title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''"
                     :data-original-title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''">
                    <div class="cardBank h-100 px-2"
                         title="Tarjeta de Crédito"
                         v-on:click="showCardOptions('cc')"
                         :class="{'--active' : (selected_payment_method === 'cc'), '__disabled_method' : (sender_country === 'Venezuela')}">
                        <img src="/img/landing/pay-cc-primary.svg" alt="Person Icon"
                             class="mb-2 mx-auto">
                        <h6 class="cardBank__title">Tarjeta de Crédito</h6>
                    
                    </div>
                </div>
                <div class="col-6 mb-3 px-1"
                     data-toggle="tooltip"
                     :title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''"
                     :data-original-title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''">
                    <div class="cardBank h-100 px-2"
                         title="Tarjeta de Débito"
                         v-on:click="showCardOptions('dc')"
                         :class="{'--active' : (selected_payment_method === 'dc'), '__disabled_method' : (sender_country === 'Venezuela')}">
                        <img src="/img/landing/pay-dc-primary.svg" alt="Person Icon"
                             class="mb-2 mx-auto">
                        <h6 class="cardBank__title">Tarjeta de Débito</h6>
                    </div>
                </div>
            </div>
            <div id="payment-wrapper-row-cards" class="slideTab payments-methods-zone">
                <div class="row px-3">
                    <div class="col-12" v-if="qbpay !== '1'">
                       <!--  <hr>
                        <span class="__assist_text" v-if="cards.length !== 0">
                            Haz click en alguna de tus tarjetas para seleccionarla.
                        </span>
                        <slick id="payment-slider" class="card_slider mb-0" ref="slick" :options="slickOptions">
                            <div v-for="card in cards" class="cardBank selective" v-on:click="selectCard(card)"
                                 :class="{'--active' : (pay_method === card.id)}">
                                <img src="/img/landing/pay-cc-primary.svg" class="mb-2 mx-auto">
                                <h6 class="cardBank__title">{{card.brand}}</h6>
                                <div class="cardBank__info">{{card.funding}} {{card.object}}<br>....{{card.last4}}</div>
                                <a class="cardBank__edit __alert_delete"
                                   @click="deleteCard(card.id)"
                                   style="right: 8px">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </div>
                        </slick>
                        <h6 class="__slick_alerts __alert_messages"
                            v-if="cards.length === 0">
                            No tienes tarjetas registradas. Haz click en el siguiente botón de
                            <br>
                            "Agregar una nueva tarjeta" para registrar una.
                        </h6>
                        <span class="__assist_text" v-if="cards.length !== 0">
                            Haz click aquí para agregar una nueva tarjeta.
                        </span>
                        <a id="btnAdd-new-card"
                           class="btn btn-secondary btn-block mb-3"
                           @click="openNewCardForm">
                            <img src="/img/landing/add-card-light.svg" class="img-fluid mr-2">
                            Agregar una nueva tarjeta
                        </a>
                        <hr> -->
                    </div>
                    <div class="col-12" v-else>
                        <hr>
                         <div class="alert alert-primary" role="alert">
                         <strong>Si es primera vez que va usar la tarjeta con nosotros debe primero confirmarla!</strong> <a href="#" class="alert-link"></a>
                     <a id="btnAdd-new-card"
                           class="btn btn-secondary btn-block mb-3"
                            href="confirm-card">
                            <img src="/img/landing/add-card-light.svg" class="img-fluid mr-2">
                            Confirmar una nueva tarjeta
                        </a>
                     </div>
                        <div class="form-group">
                            <label for="qb_username">Nombre Completo (cómo aparece en la tarjeta)</label>
                            <input type="text"
                                   id="qb_username"
                                   name="qb_card[username]"
                                   placeholder="Nombre Completo"
                                   required
                                   v-model="card_name"
                                   @keyup="verifyQbFields"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="qb_cardNumber">Número de la tarjeta</label>
                            <div class="input-group">
                                <cleave v-model="card_number"
                                        :options="card_options"
                                        id="qb_cardNumber"
                                        name="qb_card[cardNumber]"
                                        class="form-control"
                                        @keyup="verifyQbFields"
                                        placeholder="Número de la tarjeta"></cleave>
                                <div class="input-group-append">
                                    <span class="input-group-text text-muted">
                                        <i class="fa fa-cc-visa mx-1"></i>
                                        <i class="fa fa-cc-mastercard mx-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>Fecha de vencimiento</label>
                                    <div class="input-group">
                                        <label for="qb-cardMonth" style="width: 40%" class="mr-2">
                                            Mes
                                            <br>
                                            <select name="qb_card[card_month]"
                                                    class="form-control custom-select"
                                                    id="qb-cardMonth"
                                                    v-model="card_month"
                                                    @change="verifyQbFields"
                                                    required>
                                                <option value="01">01</option>
                                                <option value="02">02</option>
                                                <option value="03">03</option>
                                                <option value="04">04</option>
                                                <option value="05">05</option>
                                                <option value="06">06</option>
                                                <option value="07">07</option>
                                                <option value="08">08</option>
                                                <option value="09">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </label>
                                        <label for="qb-cardYear" style="width: 40%">
                                            Año
                                            <br>
                                            <select name="qb_card[card_year]"
                                                    class="form-control custom-select"
                                                    id="qb-cardYear"
                                                    v-model="card_year"
                                                    @change="verifyQbFields"
                                                    required>
                                                <option value="2019">2019</option>
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                                <option value="2023">2023</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                                <option value="2029">2029</option>
                                                <option value="2030">2030</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>&nbsp;</label>
                                <div class="form-group mb-4">
                                    <label data-toggle="tooltip" title="Código de 3 dígitos al reverso de la tarjeta.">
                                        CVV
                                        <i class="fa fa-question-circle"></i>
                                        <br>
                                        <input type="text"
                                               name="qb_card[card_cvv]"
                                               @keyup="verifyQbFields"
                                               v-model="card_cvv"
                                               required
                                               class="form-control">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            
            <!--<div id="payment-wrapper-row-1" class="slideTab payments-methods-zone">
                <div class="row px-3">
                    <div class="col-9 col-xl-12">
                        <hr>
                        <h6 class="text-primary">Debes emitir un Cheque usando estos datos:</h6>
                        <hr>
                    </div>
                </div>
            </div>-->
            <!--
                <div class="d-flex justify-content-between flex-sm-row flex-column align-items-center">
                    <div class="font-14 text-primary mb-2 mb-md-0">
                        Ahora mismo no se encuentra habilitado el pago por tarjetas de crédito.
                        <br>
                        Estamos trabajando activamente para su pronto restablecimiento. Disculpen las molestias.
                        <br><br><br>
                    </div>
                </div>
            -->
            <!--<div class="row px-3">
                <div class="col-12 mb-2 px-1">
                    &lt;!&ndash;<hr>&ndash;&gt;
                    <p class="font-14 text-danger mb-2 mb-md-0 mb-0">
                        Se han deshabilitado temporalmente los pagos por tarjetas de crédito o débito.
                    </p>
                </div>
            </div>-->
            <div class="row px-3"
                 v-if="sender_country === 'United States' && on_wallets === true">
                <div class="col-6 mb-3 px-1">
                    <div class="cardBank h-100 px-2"
                         title="Pagar con Gift Card - Amazon"
                         v-on:click="showAmazPrepaidOptions"
                         :class="{'--active' : (selected_payment_method === 'amaz_prepaid')}">
                        <img src="/img/landing/pay-cc-primary.svg" alt="Person Icon"
                             class="mb-2 mx-auto">
                        <h6 class="cardBank__title">
                            Pagar con Gift Card
                            <br> Amazon
                        </h6>
                    </div>
                </div>
                <div class="col-6 mb-3 px-1">
                    <div class="cardBank h-100 px-2"
                         title="Pagar con Gift Card - American Time Holding"
                         v-on:click="showAthPrepaidOptions"
                         :class="{'--active' : (selected_payment_method === 'ath_prepaid')}">
                        <img src="/img/icons/logoATH.svg" alt="Person Icon"
                             class="mb-2 mx-auto" style="max-height: 15px">
                        <h6 class="cardBank__title">
                            Pagar con Gift Card
                            <br> American Time Holding 
                        </h6>
                    </div>
                </div>
            </div>
            <div id="payment-wrapper-row-prepaid" class="slideTab payments-methods-zone">
                <div class="row px-3">
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                <li>
                                    Éste método de pago consiste en el uso de Tarjetas Visa Gift Cards adquiridas en
                                    Amazon
                                </li>
                                <li>
                                    Recuerde que las tarjetas se presentan en los siguientes montos:
                                    <ul>
                                        <li>25 USD</li>
                                        <li>50 USD</li>
                                        <li>100 USD</li>
                                        <li>200 USD</li>
                                    </ul>
                                </li>
                                <li>
                                    Importante: Del total recargado se descontará un monto equivalente al 4% de la
                                    recarga.
                                    Ejemplo: De una intención de recarga de 100 USD se recargarán efectivamente 96 USD,
                                    siendo que 4 USD representa el 4% de 100 USD y se descontarán del total
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="payment-wrapper-row-ath-prepaid" class="slideTab payments-methods-zone">
                <div class="row px-3">
                    <div class="col-12">
                        <div class="alert alert-primary" v-if="pay_method === 'ath_prepaid'">
                            <label for="ath_prepaid_code">Introduce el código de la tarjeta de regalo.</label>
                            <input id="ath_prepaid_code"
                                   name="ath_prepaid_code"
                                   class="form-control" type="text"
                                   placeholder="Código de la tarjeta de regalo">
                        </div>
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                <li>
                                    Éste método de pago consiste en el uso de Tarjetas de Regalo adquiridas en
                                    American Time Holding:
                                    <a href="https://americantimeholding.com/" target="_blank">
                                        americantimeholding.com
                                    </a>
                                </li>
                                <li>
                                    Recuerde que el monto en dólares de la recarga debe ser igual al valor de la
                                    tarjeta,
                                    no puede hacer recargas parciales.
                                </li>
                                <li>
                                    Si el monto de la recarga no coincide con el valor de la tarjeta, la recarga se
                                    rechazará sin afectar la validez de la tarjeta de regalo.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-3"
                 v-if="sender_country === 'United States'">
                <div class="col-12 mb-3 px-1">
                    <!--<hr>-->
                    <p class="font-14 text-primary mb-2 mb-md-0 mb-0">
                        Si seleccionas cualquiera de los siguientes métodos, al hacer click en "Enviar Dinero" podrás
                        ver los datos necesarios para reportar tu pago.
                    </p>
                </div>
                <!--<div class="col-6 col-md-3 col-xl-4 mb-3 px-1"
                     data-toggle="tooltip"
                     :title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''"
                     :data-original-title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''">
                    <div class="cardBank color-icons d-flex align-items-center h-100 px-2" data-toggle="tooltip"
                         v-on:click="showVenmoOptions"
                         :class="{'&#45;&#45;active' : (selected_payment_method === 'venmo'), '__disabled_method' : (sender_country === 'Venezuela')}"
                         title="Venmo">
                        <img src="/img/landing/venmo.png" alt="Venmo Logo" class="img-fluid mx-auto"
                             style="max-height: 55px">
                    </div>
                </div>-->
                <!--<div class="col-6 col-md-3 col-xl-3 mb-3 px-1"
                     data-toggle="tooltip"
                     :title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''"
                     :data-original-title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''">
                    <div class="cardBank color-icons d-flex align-items-center h-100 px-2" data-toggle="tooltip"
                         v-on:click="showCashAppOptions"
                         :class="{'--active' : (selected_payment_method === 'cashapp'), '__disabled_method' : (sender_country === 'Venezuela')}"
                         title="Cash App">
                        <img src="/img/landing/cashapp.png" alt="Cash App Logo"
                             class="img-fluid mx-auto"
                             style="max-height: 55px">
                    </div>
                </div>
                <div class="col-6 col-md-3 col-xl-3 mb-3 px-1"
                     data-toggle="tooltip"
                     :title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''"
                     :data-original-title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''">
                    <div class="cardBank color-icons d-flex align-items-center h-100 px-2" data-toggle="tooltip"
                         v-on:click="showPayoneerOptions"
                         :class="{'&#45;&#45;active' : (selected_payment_method === 'payoneer'), '__disabled_method' : (sender_country === 'Venezuela')}"
                         title="Payoneer">
                        <img src="/img/landing/payoneer.png" alt="Payoneer Logo"
                             class="img-fluid mx-auto" style="max-height: 55px">
                    </div>
                </div>-->
                <div class="col-12 mb-3 px-1"
                     data-toggle="tooltip"
                     :title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''"
                     :data-original-title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''">
                    <div class="cardBank color-icons d-flex align-items-center h-100 px-2"
                         data-toggle="tooltip"
                         v-on:click="showZelleOptions"
                         :class="{'--active' : (selected_payment_method === 'zelle')}"
                         title="Zelle">
                        <img src="/img/landing/zelle.png" alt="Zelle Logo" class="img-fluid mx-auto"
                             style="max-height: 55px">
                    </div>
                </div>
                <!--<div class="col-6 mb-3 px-1"
                     data-toggle="tooltip"
                     :title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''"
                     :data-original-title="sender_country === 'Venezuela' ? 'No válido para Venezuela' : ''">
                    <div class="cardBank color-icons d-flex align-items-center h-100 px-2" data-toggle="tooltip"
                         v-on:click="showPopmoneyOptions"
                         :class="{'&#45;&#45;active' : (selected_payment_method === 'popmoney'), '__disabled_method' : (sender_country === 'Venezuela')}"
                         title="Pop Money">
                        <img src="/img/landing/popmoney.png" alt="Popmoney Logo"
                             class="img-fluid mx-auto" style="max-height: 55px">
                    </div>
                </div>-->
                <!--<div class="col-6 col-md-3 col-xl-3 mb-3 px-1">
                    <div class="cardBank color-icons d-flex align-items-center h-100 px-2 __disabled_method"
                         data-toggle="tooltip"
                         v-on:click="showPandcoOptions"
                         :class="{'&#45;&#45;active' : (selected_payment_method === 'pandco')}"
                         title="Pandco">
                        <img src="/img/landing/pandco.png" alt="Pandco Logo"
                             class="img-fluid mx-auto"
                             style="max-height: 55px">
                    </div>
                </div>-->
                <div class="col-12 mb-3 px-1">
                    <hr>
                </div>
            </div>
            <!--<div id="payment-wrapper-row-2" class="slideTab payments-methods-zone">
                <div class="row px-3">
                    <div class="col-12" v-if="selected_payment_method === 'zelle'">
                        <hr>
                        <h6 class="text-primary">Estos son los datos que necesitas para transferir por ZELLE</h6>
                        <p class="font-14" style="color: #979797;">
                            Cuando hagas tu operacion usando Zelle, por favor incluye el siguiente código de seguimiento
                            en la descripción del pago. <b>Es muy importante</b>.
                        </p>
                        <p style="color: #979797;">
                            <b>Código de seguimiento único:</b>
                        </p>
                        <p class="text-secondary" style="font-size: 24px">
                            <b>{{transTracking}}</b>
                        </p>
                        <p class="font-14" style="color: #979797;">
                            Estos son los datos que debes conocer para tu pago:
                        </p>
                        <div class="row __info_row px-3">
                            <div class="col-md px-0 py-0">
                                <span class="title">Nombre</span>
                                <div class="info">
                                    Nehuz
                                </div>
                            </div>
                            <div class="col-md px-0 py-0">
                                <span class="title">E-mail</span>
                                <div class="info">
                                    NO - zelle.mw01@gmail.com
                                    zelle.ath01@gmail.com
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="col-12" v-if="selected_payment_method === 'venmo'">
                        <hr>
                        <h6 class="text-primary">Estos son los datos que necesitas para transferir por Venmo</h6>
                        <p class="font-14" style="color: #979797;">
                            Cuando hagas tu operacion usando Venmo, por favor incluye el siguiente código de seguimiento
                            en la descripción del pago. <b>Es muy importante</b>.
                        </p>
                        <p style="color: #979797;">
                            <b>Código de seguimiento único:</b>
                        </p>
                        <p class="text-secondary" style="font-size: 24px">
                            <b>{{transTracking}}</b>
                        </p>
                        <p class="font-14" style="color: #979797;">
                            Estos son los datos que debes conocer para tu pago:
                        </p>
                        <div class="row __info_row px-3">
                            <div class="col-md px-0 py-0">
                                <span class="title">E-mail</span>
                                <div class="info">
                                    venmo@americantimeholding.com
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="col-12" v-if="selected_payment_method === 'cashapp'">
                        <hr>
                        <h6 class="text-primary">Estos son los datos que necesitas para transferir por CashApp</h6>
                        <p class="font-14" style="color: #979797;">
                            Cuando hagas tu operacion usando CashApp, por favor incluye el siguiente código de
                            seguimiento
                            en la descripción del pago. <b>Es muy importante</b>.
                        </p>
                        <p style="color: #979797;">
                            <b>Código de seguimiento único:</b>
                        </p>
                        <p class="text-secondary" style="font-size: 24px">
                            <b>{{transTracking}}</b>
                        </p>
                        <p class="font-14" style="color: #979797;">
                            Estos son los datos que debes conocer para tu pago:
                        </p>
                        <div class="row __info_row px-3">
                            <div class="col-md px-0 py-0">
                                <span class="title">$CashTag</span>
                                <div class="info">
                                    $Americankryptosbank
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="col-12" v-if="selected_payment_method === 'payoneer'">
                        <hr>
                        <h6 class="text-primary">Estos son los datos que necesitas para transferir por Payoneer</h6>
                        <p class="font-14" style="color: #979797;">
                            Cuando hagas tu operacion usando Payoneer, por favor incluye el siguiente código de
                            seguimiento
                            en la <i>finalidad del pago</i>. <b>Es muy importante</b>.
                        </p>
                        <p style="color: #979797;">
                            <b>Código de seguimiento único:</b>
                        </p>
                        <p class="text-secondary" style="font-size: 24px">
                            <b>{{transTracking}}</b>
                        </p>
                        <p class="font-14" style="color: #979797;">
                            Estos son los datos que debes conocer para tu pago:
                        </p>
                        <div class="row __info_row px-3">
                            <div class="col-md px-0 py-0">
                                <span class="title">E-mail</span>
                                <div class="info">
                                    americantimeholding@gmail.com
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="col-12" v-if="selected_payment_method === 'popmoney'">
                        <hr>
                        <h6 class="text-primary">Estos son los datos que necesitas para transferir por Popmoney</h6>
                        <p class="font-14" style="color: #979797;">
                            Cuando hagas tu operacion usando Popmoney, por favor incluye el siguiente código de
                            seguimiento en la <i>finalidad del pago</i>. <b>Es muy importante</b>.
                        </p>
                        <p style="color: #979797;">
                            <b>Código de seguimiento único:</b>
                        </p>
                        <p class="text-secondary" style="font-size: 24px">
                            <b>{{transTracking}}</b>
                        </p>
                        <p class="font-14" style="color: #979797;">
                            Estos son los datos que debes conocer para tu pago:
                        </p>
                        <div class="row __info_row px-3">
                            <div class="col-md px-0 py-0">
                                <span class="title">E-mail</span>
                                <div class="info">
                                    popmoney@americantimeholding.com
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

                <span class="__small_other_methods mb-3 d-md-none bg-light py-2 px-2 d-block text-center"
                      @click="seeMethods">Ver otros métodos</span>
            </div>-->
            <div class="d-flex justify-content-between flex-sm-row flex-column align-items-center"
                 v-if="(no_viable_country === null || no_viable_country === false) && forbidden_chat === 0 && on_wallets === true">
                <div class="font-14 text-primary mb-2 mb-md-0 __cash_pretext"
                     v-on:click="selectCash()">
                    Si tienes dinero en efectivo o deseas hacer transferencias,
                    puedes usar nuestro pago asistido para enviar tu dinero.
                </div>
                <div class="cardBank h-100 __cash_payment_btn" data-toggle="tooltip" title="Pago Asistido"
                     v-on:click="selectCash()"
                     :class="{'--active' : (pay_method === 'cash')}">
                    <div class="media align-items-center">
                        <div>
                            <img src="/img/landing/pay-cash-primary.svg" class="img-fluid mr-2">
                        </div>
                        <div class="media-body">Usar Pago Asistido</div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between flex-sm-row flex-column align-items-center"
                 v-if="forbidden_chat === 1">
                <div class="font-14 text-primary mb-2 mb-md-0 __cash_pretext">
                    Ahora mismo no se encuentra habilitado el pago asistido.
                    <br>
                    <br>
                    <span>
                        Nuestro horario de atención es:
                        <br><strong>Lunes a Viernes</strong>
                        <br><strong>Desde las 09:00 AM hasta las 07:00 PM.</strong>
                        <br><strong>Sábados</strong>
                        <br><strong>Desde las 09:00 AM hasta las 01:00 PM.</strong>
                    </span>
                </div>
            </div>

            <div class="loader">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>

        <div id="add-card-form" style="z-index: 1050;" class="card--overlaid">
            <div class="card m-2 m-md-3 m-lg-4 loader--wrapper" :class="{ '--loading': loader }">
                <header class="card-header bg-white">
                    <div class="clearfix">
                        <h6 class="modal-title text-primary font-weight-bold float-left">
                            <img src="/img/landing/ccPlus-secondary.svg" class="img-fluid mr-3">
                            Datos de la tarjeta de
                            <span style="text-transform: capitalize;">
                                {{user.first_name.toLowerCase()}} {{user.last_name.toLowerCase()}}
                            </span>
                        </h6>
                        <a class="btn-transparent card--overlaid--dismiss float-right text-secondary font-14 px-2 mr-n3"
                           @click="closeCardOverlaid"
                           aria-label="Close">
                            Cancelar
                        </a>
                    </div>
                    <div class="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="card-name" class="text-primary">Nombre y Apellido del
                                            Titular</label>
                                        <input type="text" id="card-name" class="form-control"
                                               placeholder="Nombre del titular">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cc-number" class="text-primary">Datos de la tarjeta</label>
                                        <div class="form-control" style="height:34.5px" ref="card">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <a class="btn btn-secondary btn-block" data-dismiss="modal"
                                       v-on:click="addCard">Guardar ésta tarjeta
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="loader">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style lang="scss">
    .payments-methods-zone {
        .cardBank {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            margin-bottom: 15px;
        }
    }

    .cardBank {
        &.--active {
            .cardBank__edit {
                &:hover {
                    color: #fff;
                }
            }
        }

        &.color-icons {
            &.--active {
                &::after {
                    color: #fff;
                    background-color: #303392;
                }

                border: 1px solid #303392;
                background-color: #fff;

                img {
                    filter: none;
                }
            }
        }
    }

    .__info_row {
        .title {
            display: block;
            text-align: center;
            color: #5c5c5c;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: -0.33px;
            line-height: 18px;
        }

        .info {
            padding: 16px 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #303392;
            font-size: 15px;
            font-weight: 700;
            line-height: 18px;
            border-top: 1px solid rgba(151, 151, 151, 0.56);
            border-bottom: 1px solid rgba(151, 151, 151, 0.56);
            border-left: 1px solid rgba(151, 151, 151, 0.56);
            border-right: 0;
        }

        .col-md {
            &:last-child {
                .info {
                    border-right: 1px solid rgba(151, 151, 151, 0.56);
                }
            }
        }
    }

    .__alert_delete {
        color: #ff6c60;

        &:hover {
            color: #d23d3b !important;
        }
    }

    .__disabled_method {
        pointer-events: none;
        box-shadow: none;

        img {
            filter: grayscale(100%);
        }

        * {
            pointer-events: none;
        }
    }

    .__cash_pretext {
        width: 60%;

        @media screen and (max-width: 39.9375em) {
            width: auto;
        }
    }

    .__cash_payment_btn {
        flex-grow: 1;
        width: 35%;
        margin-left: 1rem;

        @media screen and (max-width: 39.9375em) {
            margin-left: 0;
            width: auto;
        }
    }

    .__small_other_methods {
        color: #333;
        cursor: pointer;
        font-size: 12px;
        border: 1px solid #d3d4d5;
    }

    .__step_number {

    }
</style>
