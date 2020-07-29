<script src="../walletWithdrawJS.js"></script>

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
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="chargeUrl">
                <img src="/img/landing/recharge-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Cargar {{currencyName}}</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3 active" :href="WithdrawUrl">
                <img src="/img/landing/withdraw-primary.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Retirar {{currencyName}}</div>
            </a>
            <a class="nav-item nav-link px-2 px-md-2 py-1 py-md-3" :href="TransferUrl">
                <img src="/img/landing/transfer-blue.svg" class="img-fluid mr-1 mr-lg-2" style="max-height: 26px">
                <div class="nav-link-title">Transferir {{currencyName}}</div>
            </a>
        </nav>

        <!-- transactions history -->
        <div class="py-4">
            <div class="row">
                <div class="col-md-8  __overlay_container">
                    <div class="card shadow-none wow fadeIn">
                        <div class="card-body py-4">
                            <h6 class="text-primary font-weight-bold mb-3">
                                <span class="__step_counter">1</span>
                                Indica el monto que vas a enviar y elige el país.
                            </h6>
                            <form class="" action="" ref="form" method="post">
                                <input type="hidden" name="_token" v-model="csrf">
                                <input type="hidden" name="exchange-p-id" v-model="price_data_id" value="">
                                <input type="hidden" name="sender" v-model="sender" value="">
                                <input type="hidden" name="receiver" v-model="receiver" value="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="sm-monto" class="text-primary">Monto a enviar</label>
                                            <input type="text" id="sm-monto" class="form-control form-control-font-lg"
                                                   @keyup="verifyMaxTransfer"
                                                   @blur="verifyMaxTransfer"
                                                   placeholder="000,000.00">
                                            <input type="hidden"
                                                   id="sending-amount"
                                                   name="sending-amount">
                                            <div v-if="!minAmountBoolean" class="small text-right text-danger mt-1">
                                                Debe retirar un mínimo o más de 10 dólares o equivalente.
                                            </div>
                                            <!--<div class="invalid-feedback">Monto inválido</div>-->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sm-destino" class="text-primary">Destino</label>
                                            <select id="sm-destino"
                                                    class="custom-select flag-selector flag-selector--full"
                                                    v-model="receiver_country"
                                                    name="receiver_country"
                                                    v-on:change="destinationHandler">
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
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-primary">Entregamos</label>
                                            <input type="text"
                                                   class="form-control custom-readonly"
                                                   style="background: url('/img/icons/loadExhanges-32px.gif') no-repeat center left; box-shadow: none"
                                                   v-if="loadingReceive">
                                            <input type="text" class="form-control custom-readonly"
                                                   id="to-receive-input"
                                                   placeholder="Ingrese monto a enviar"
                                                   :value="toReceive + ' ' + currencies[receiver_country]"
                                                   v-else
                                                   name="to_receive"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>

                                <section id="recipient" class="mt-4">
                                    <div class="loader--wrapper" :class="{ '--loading': loader }">
                                        <h5 class="text-primary font-weight-bold mb-4">
                                            <span class="__step_counter">2</span>
                                            ¿A quién vas a enviar dinero?
                                        </h5>
                                        <div class="row">
                                            <div class="col-6 col-md-4 col-xl-3 px-2">
                                                <div class="cardBank mx-2" id="person-btn"
                                                     data-target="#contact-wrapper"
                                                     @click="openPersonRow">
                                                    <div class="media align-items-center" style="pointer-events: none">
                                                        <div>
                                                            <img src="/img/landing/person-primary.svg" alt="Person Icon"
                                                                 class="mr-2">
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="cardBank__title mb-0">Persona</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-xl-3 px-2">
                                                <div class="cardBank mx-2" id="company-btn"
                                                     data-target="#contact-wrapper"
                                                     @click="openCompanyRow">
                                                    <div class="media align-items-center" style="pointer-events: none">
                                                        <div>
                                                            <img src="/img/landing/company-primary.svg"
                                                                 alt="Person Icon"
                                                                 class="mr-2">
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="cardBank__title mb-0">Empresa</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                        <div id="contact-wrapper" class="slideTab">
                                            <div>
                                                <div class="d-flex justify-content-between"
                                                     v-if="destinations.length > 0">
                                                    <div class="font-14 text-primary" v-if="type === 1">
                                                        Estos son tus contactos personales para éste país, elige uno.
                                                    </div>
                                                    <div class="font-14 text-primary" v-else>
                                                        Estos son tus contactos empresariales para éste país, elige uno.
                                                    </div>
                                                    <div id="recipients-slider-arrows" class="position-relative"></div>
                                                </div>
                                                <input type="hidden" v-model="destination_id" name="destination_id">
                                                <span class="__assist_text"
                                                      v-if="destinations[type] && destinations[type].length !== 0">
                                                Haz click en alguno de tus contactos para seleccionarlo.
                                            </span>
                                                <slick id="recipients-slider" ref="slick" class="mt-3 mb-0"
                                                       :options="slickOptions">
                                                    <div v-for="destination in destinations[type]"
                                                         v-on:click="selectDestination(destination.id, destination, $event)"
                                                         class="cardBank selective"
                                                         :class="{'--active' : (destination_id === destination.id)}">
                                                        <img src="/img/landing/person-primary.svg" alt="Person Icon"
                                                             class="mb-2 mx-auto">
                                                        <h6 class="cardBank__title">{{destination.name}}<br>{{destination.lastname}}
                                                        </h6>
                                                        <div class="cardBank__info">{{destination.bank_name}}<br>....
                                                            {{destination.account_number.substr(destination.account_number.length
                                                            -
                                                            4)}}
                                                        </div>
                                                        <a @click="editDestination(destination.id)"
                                                           style="right: 8px"
                                                           class="cardBank__edit text-muted">
                                                            <i class="fa fa-sliders fa-rotate-90"></i>
                                                        </a>
                                                    </div>
                                                </slick>
                                                <h6 class="__slick_alerts __alert_messages"
                                                    v-if="!destinations[type] || destinations[type].length === 0">
                                                    No tienes
                                                    <span v-if="type === 1">personas registradas.</span>
                                                    <span v-else>empresas registradas.</span>
                                                    Haz click en el siguiente botón de
                                                    <br>
                                                    <span v-if="type === 1">"Agregar Nueva Persona"</span>
                                                    <span v-else>"Agregar Nueva Empresa"</span>
                                                    para registrar uno.
                                                </h6>
                                                <span class="__assist_text"
                                                      v-if="destinations[type] && destinations[type].length !== 0">
                                                    Haz click aquí para agregar un nuevo contacto.
                                                </span>
                                                <a id="btnAdd-contact-person"
                                                   class="btn btn-secondary btn-block"
                                                   @click="openNewContactForm">
                                                    <img src="/img/landing/add-person-light.svg" class="img-fluid mr-2">
                                                    <span v-if="type === 1">Agregar Nueva Persona</span>
                                                    <span v-else>Agregar Nueva Empresa</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="loader">
                                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                                 role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </form>
                        </div>
                        <!-- add contact person card-overlaid -->
                        <div id="add-contact-person" style="z-index: 1050; -webkit-transform: translate3d(0,0,0);"
                             class="card--overlaid">
                            <div class="card m-2 m-md-3 m-lg-4">
                                <header class="card-header bg-white">
                                    <div class="clearfix">
                                        <h6 class="modal-title text-primary font-weight-bold float-left">
                                            <img src="/img/landing/personPlus-secondary.svg" class="img-fluid mr-3">
                                            Datos Personales del Receptor
                                        </h6>
                                        <a class="btn-transparent card--overlaid--dismiss float-right text-secondary font-14 px-2 mr-n3"
                                           @click="closeOverlaid"
                                           aria-label="Close">
                                            Cancelar
                                        </a>
                                        <a v-if="id"
                                           class="btn-transparent __del_destiny float-right text-danger font-14 px-2 mr-3"
                                           @click="deleteDestination(id)"
                                           aria-label="Delete">
                                            Eliminar
                                        </a>
                                    </div>
                                    <div class="font-14 mt-3">
                                        Aquí puede ingresar o editar los datos asociados a la cuenta destino
                                    </div>
                                </header>
                                <form action="/" id="__person_dest_form" @submit.prevent="createDestination">
                                    <div class="card-body">
                                        <div class="row">
                                            <!--<div class="col-md-12 text-right"
                                                 v-if="name !== ''">
                                                <a href="javascript:void(0)"
                                                   @click="deleteDestination(id)"
                                                   class="btn btn-danger">Eliminar</a>
                                            </div>-->
                                            <div class="col-md-12 pr-lg-5">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="dest-name" class="text-primary">Nombre</label>
                                                            <input type="text" class="form-control"
                                                                   id="dest-name"

                                                                   v-model="name"
                                                                   required
                                                                   name="name">
                                                            <input type="hidden" class="form-control" v-model="id"
                                                                   name="id">
                                                            <input type="hidden" class="form-control" v-model="type"
                                                                   name="type">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="dest-lastname"
                                                                   class="text-primary">Apellido</label>
                                                            <input type="text" class="form-control"
                                                                   id="dest-lastname"

                                                                   v-model="lastname"
                                                                   required
                                                                   name="lastname">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="dest-email" class="text-primary">Email</label>
                                                            <input type="email" class="form-control"
                                                                   id="dest-email"

                                                                   v-model="email"
                                                                   required
                                                                   name="email">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <label for="dest-main-mobile" class="text-primary">
                                                                Teléfono Móvil</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <select name="pre-mobile"
                                                                            id="dest-pre-mobile"
                                                                            v-model="phonecode"
                                                                            required
                                                                            class="custom-select __flag_mobile flag-selector">
                                                                        <optgroup label="Latino América">
                                                                            <option value="+56"
                                                                                    data-flag="/img/landing/flags/cl.svg">
                                                                                +56 Chile
                                                                            </option>
                                                                            <option value="+58"
                                                                                    data-flag="/img/landing/flags/ve.svg"
                                                                                    selected>
                                                                                +58 Venezuela
                                                                            </option>
                                                                            <option value="+54"
                                                                                    data-flag="/img/landing/flags/ar.svg">
                                                                                +54 Argentina
                                                                            </option>
                                                                            <option value="+57"
                                                                                    data-flag="/img/landing/flags/co.svg">
                                                                                +57 Colombia
                                                                            </option>
                                                                            <option value="+51"
                                                                                    data-flag="/img/landing/flags/pe.svg">
                                                                                +51 Perú
                                                                            </option>
                                                                            <option value="+52"
                                                                                    data-flag="/img/landing/flags/mx.svg">
                                                                                +52 México
                                                                            </option>
                                                                            <option value="+55"
                                                                                    data-flag="/img/landing/flags/br.svg">
                                                                                +55 Brazil
                                                                            </option>
                                                                            <!--<option value="+1"
                                                                                    data-flag="/img/landing/flags/do.svg">
                                                                                +1 República Dominicana
                                                                            </option>-->
                                                                            <!--  <option value="+506" data-flag="/img/landing/flags/cr.svg">
                                                                                  +506 Costa Rica
                                                                              </option> -->
                                                                            <option value="+507"
                                                                                    data-flag="/img/landing/flags/pa.svg">
                                                                                +507 Panamá
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Norte America">
                                                                            <option value="+1"
                                                                                    data-flag="/img/landing/flags/us.svg"
                                                                                    selected>
                                                                                +1 United States
                                                                            </option>
                                                                            <!--<option value="+1"
                                                                                    data-flag="/img/landing/flags/ca.svg">
                                                                                +1 Canada
                                                                            </option>-->
                                                                        </optgroup>
                                                                        <optgroup label="Europa">
                                                                            <option value="+34"
                                                                                    data-flag="/img/landing/flags/es.svg">
                                                                                +34 España
                                                                            </option>
                                                                            <option value="+351"
                                                                                    data-flag="/img/landing/flags/pt.svg">
                                                                                +351 Portugal
                                                                            </option>
                                                                            <option value="+39"
                                                                                    data-flag="/img/landing/flags/it.svg">
                                                                                +39 Italia
                                                                            </option>
                                                                            <option value="+33"
                                                                                    data-flag="/img/landing/flags/fr.svg">
                                                                                +33 Francia
                                                                            </option>
                                                                            <option value="+49"
                                                                                    data-flag="/img/landing/flags/de.svg">
                                                                                +49 Alemania
                                                                            </option>
                                                                            <option value="+44"
                                                                                    data-flag="/img/landing/flags/gb.svg">
                                                                                +44 Reino Unido
                                                                            </option>
                                                                        </optgroup>
                                                                    </select>
                                                                    <!--<select name="pre-mobile-code"
                                                                            id="dest-pre-mobile-code"
                                                                            v-model="pre_mobile_code"
                                                                            required
                                                                            style="padding-left: 2px"
                                                                            class="custom-select"
                                                                    v-if="phonecode === '+58'">
                                                                        <option value="412">0412</option>
                                                                        <option value="414">0414</option>
                                                                        <option value="424">0424</option>
                                                                        <option value="416">0416</option>
                                                                        <option value="426">0426</option>
                                                                    </select>-->
                                                                </div>
                                                                <vue-mask
                                                                        class="form-control"
                                                                        mask="(r00) 000-0000"
                                                                        required
                                                                        :options="maskOptions"
                                                                        v-model="phonenumber"
                                                                        id="dest-main-mobile"
                                                                        name="main-mobile">
                                                                </vue-mask>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dest-birthday" class="text-primary">
                                                                Fecha de nacimiento
                                                            </label>
                                                            &lt;!&ndash;<input type="text" name="birthday"
                                                                   required
                                                                   v-model="birthday"
                                                                   data-field="date"
                                                                   id="dest-birthday"
                                                                   class="form-control"/>&ndash;&gt;
                                                            <v-date-picker v-model='birthday'
                                                                           :input-props="{
                                                                            id: 'dest-birthday',
                                                                            class: 'form-control',
                                                                            required: true,
                                                                            name: 'birthday',
                                                                            type: 'text'
                                                                       }"
                                                                           :masks="{
                                                                            input: 'MM/DD/YYYY',
                                                                            data: 'MM/DD/YYYY',
                                                                       }"
                                                            ></v-date-picker>
                                                        </div>
                                                    </div>-->
                                                    <div class="col-md-12">
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dest-id_type" class="text-primary">
                                                                Tipo de Identificación
                                                            </label>
                                                            <select name="id_type"
                                                                    required
                                                                    v-model="id_type"
                                                                    id="dest-id_type" class="custom-select">
                                                                <option value="1">Cédula</option>
                                                                <option value="2">Licencia de Conducir</option>
                                                                <option value="3">Número de Seguro Social</option>
                                                                <option value="4">Pasaporte</option>
                                                                <option value="5">DNI</option>
                                                                <option value="6">ID</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--                                        <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="dest-id_origin_date" class="text-primary">
                                                                                                        Fecha de expedición</label>
                                                                                                    <v-date-picker v-model='id_origin_date'
                                                                                                                   :input-props="{
                                                                                                                        id: 'dest-id_origin_date',
                                                                                                                        class: 'form-control',
                                                                                                                        required: true,
                                                                                                                        name: 'id_origin_date',
                                                                                                                        type: 'text'
                                                                                                                   }"
                                                                                                                   :masks="{
                                                                                                                        input: 'MM/DD/YYYY',
                                                                                                                        data: 'MM/DD/YYYY',
                                                                                                                   }"
                                                                                                    ></v-date-picker>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label for="dest-id_end_date" class="text-primary">
                                                                                                        Fecha de expiración (Si cumple)</label>
                                                                                                    <v-date-picker v-model='id_end_date'
                                                                                                                   :input-props="{
                                                                                                                        id: 'dest-id_end_date',
                                                                                                                        class: 'form-control',
                                                                                                                        name: 'id_end_date',
                                                                                                                        type: 'text'
                                                                                                                   }"
                                                                                                                   :masks="{
                                                                                                                        input: 'MM/DD/YYYY',
                                                                                                                        data: 'MM/DD/YYYY',
                                                                                                                   }"
                                                                                                    ></v-date-picker>
                                                                                                </div>
                                                                                            </div>-->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dest-id_number" class="text-primary">
                                                                Número de Documento</label>
                                                            <div class="row" v-if="id_origin_country === 'VE'">
                                                                <div class="col-4" style="padding-right: 0;">
                                                                    <select id="__pre_id"
                                                                            name="__pre_id"
                                                                            v-model="pre_id"
                                                                            style="padding-left: 2px"
                                                                            class="custom-select">
                                                                        <option value="V-">V</option>
                                                                        <option value="E-">E</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col" style="padding-left: 0;">
                                                                    <input type="text" name="id_number"
                                                                           required
                                                                           v-model="id_number"
                                                                           id="dest-id_number"
                                                                           class="form-control"/>
                                                                </div>
                                                            </div>
                                                            <input type="text" name="id_number"
                                                                   v-else
                                                                   required
                                                                   v-model="id_number"
                                                                   id="dest-id_number"
                                                                   class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dest-id_origin_country" class="text-primary">
                                                                País del Documento</label>
                                                            <select name="id_origin_country"
                                                                    required
                                                                    @change="getStates('id_doc')"
                                                                    v-model="id_origin_country"
                                                                    id="dest-id_origin_country"
                                                                    class="custom-select flag-selector flag-selector--full">
                                                                <optgroup label="Sur América">
                                                                    <option value="VE"
                                                                            data-flag="/img/landing/flags/ve.svg">
                                                                        Venezuela
                                                                    </option>
                                                                    <option value="CO"
                                                                            data-flag="/img/landing/flags/co.svg">
                                                                        Colombia
                                                                    </option>
                                                                    <option value="PE"
                                                                            data-flag="/img/landing/flags/pe.svg">Perú
                                                                    </option>
                                                                    <option value="CL"
                                                                            data-flag="/img/landing/flags/cl.svg">Chile
                                                                    </option>
                                                                    <option value="AR"
                                                                            data-flag="/img/landing/flags/ar.svg">
                                                                        Argentina
                                                                    </option>
                                                                    <option value="BR"
                                                                            data-flag="/img/landing/flags/br.svg">Brazil
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Centro América">
                                                                    <option value="PA"
                                                                            data-flag="/img/landing/flags/pa.svg">Panamá
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Norte América">
                                                                    <option value="MX"
                                                                            data-flag="/img/landing/flags/mx.svg">México
                                                                    </option>
                                                                    <option value="US"
                                                                            data-flag="/img/landing/flags/us.svg">United
                                                                        States
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Europa">
                                                                    <option value="UK"
                                                                            data-flag="/img/landing/flags/gb.svg">Reino
                                                                        Unido
                                                                    </option>
                                                                    <option value="ES"
                                                                            data-flag="/img/landing/flags/es.svg">España
                                                                    </option>
                                                                    <option value="PT"
                                                                            data-flag="/img/landing/flags/pt.svg">
                                                                        Portugal
                                                                    </option>
                                                                    <option value="IT"
                                                                            data-flag="/img/landing/flags/it.svg">Italia
                                                                    </option>
                                                                    <option value="FR"
                                                                            data-flag="/img/landing/flags/fr.svg">
                                                                        Francia
                                                                    </option>
                                                                    <option value="DE"
                                                                            data-flag="/img/landing/flags/de.svg">
                                                                        Alemania
                                                                    </option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4"
                                                         v-if="id_origin_country === 'US' && id_type === '2'"
                                                    >
                                                        <div class="form-group __id-dynamic_states"
                                                             :data-id-state="id_origin_state">
                                                            <label for="dest-id_origin_state"
                                                                   class="text-primary">Estado</label>
                                                            <input type="text" name="id_origin_state"
                                                                   id="dest-id_origin_state"
                                                                   class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dest-country" class="text-primary">País</label>
                                                            <select name="country"
                                                                    required
                                                                    @change="getStates('holder')"
                                                                    v-model="country"
                                                                    id="dest-country"
                                                                    class="custom-select flag-selector flag-selector--full">
                                                                <optgroup label="Sur América">
                                                                    <option value="VE"
                                                                            data-flag="/img/landing/flags/ve.svg">
                                                                        Venezuela
                                                                    </option>
                                                                    <option value="CO"
                                                                            data-flag="/img/landing/flags/co.svg">
                                                                        Colombia
                                                                    </option>
                                                                    <option value="PE"
                                                                            data-flag="/img/landing/flags/pe.svg">Perú
                                                                    </option>
                                                                    <option value="CL"
                                                                            data-flag="/img/landing/flags/cl.svg">Chile
                                                                    </option>
                                                                    <option value="AR"
                                                                            data-flag="/img/landing/flags/ar.svg">
                                                                        Argentina
                                                                    </option>
                                                                    <option value="BR"
                                                                            data-flag="/img/landing/flags/br.svg">Brazil
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Centro América">
                                                                    <option value="PA"
                                                                            data-flag="/img/landing/flags/pa.svg">Panamá
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Norte América">
                                                                    <option value="MX"
                                                                            data-flag="/img/landing/flags/mx.svg">México
                                                                    </option>
                                                                    <option value="US"
                                                                            data-flag="/img/landing/flags/us.svg">United
                                                                        States
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Europa">
                                                                    <option value="UK"
                                                                            data-flag="/img/landing/flags/gb.svg">Reino
                                                                        Unido
                                                                    </option>
                                                                    <option value="ES"
                                                                            data-flag="/img/landing/flags/es.svg">España
                                                                    </option>
                                                                    <option value="PT"
                                                                            data-flag="/img/landing/flags/pt.svg">
                                                                        Portugal
                                                                    </option>
                                                                    <option value="IT"
                                                                            data-flag="/img/landing/flags/it.svg">Italia
                                                                    </option>
                                                                    <option value="FR"
                                                                            data-flag="/img/landing/flags/fr.svg">
                                                                        Francia
                                                                    </option>
                                                                    <option value="DE"
                                                                            data-flag="/img/landing/flags/de.svg">
                                                                        Alemania
                                                                    </option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="dest-street"
                                                                   class="text-primary">Dirección</label>
                                                            <input id="dest-street"
                                                                   name="street"
                                                                   v-model="street"

                                                                   required
                                                                   type="text"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dest-city" class="text-primary">Ciudad</label>
                                                            <input id="dest-city" type="text" class="form-control"

                                                                   v-model="city"
                                                                   required
                                                                   name="city"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group __holder-dynamic_states person"
                                                             :data-holder-state="state">
                                                            <label for="dest-select_state"
                                                                   class="text-primary">Estado/Departamento</label>
                                                            <select id="dest-select_state" class="custom-select"
                                                                    required
                                                                    name="state">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dest-zip_code" class="text-primary">Código
                                                                Postal</label>
                                                            <input id="dest-zip_code"
                                                                   type="text"
                                                                   class="form-control"
                                                                   v-model="zip_code"

                                                                   name="zip_code">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div v-if="receiver === 'VES'" class="alert alert-danger"
                                                             role="alert">
                                                            Por la normativa estadounidense vigente, ninguna empresa con
                                                            base
                                                            en Estados Unidos puede operar con bancos pertenecientes al
                                                            Estado
                                                            venezolano. No podemos operar con:
                                                            <ul>
                                                                <li>Banco de Venezuela</li>
                                                                <li>Banco del Tesoro</li>
                                                                <li>Banco del Bicentenario</li>
                                                                <li>Ningún otro banco perteneciente al Estado</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"
                                                             data-user-state=" ">
                                                            <label for="dest-bank_name" class="text-primary">Nombre del
                                                                Banco</label>
                                                            <input type="text" id="dest-bank_name" class="form-control"
                                                                   required
                                                                   v-model="bank_name"
                                                                   name="bank_name"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"
                                                             data-user-state=" ">
                                                            <label for="dest-account_number" class="text-primary">
                                                                Número de Cuenta
                                                            </label>
                                                            <input type="text" id="dest-account_number"
                                                                   class="form-control"
                                                                   required
                                                                   v-model="account_number"
                                                                   name="account_number"/>
                                                            <input type="hidden" id="dest-currency" class="form-control"
                                                                   v-model="receiver"
                                                                   name="currency"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"
                                                             data-user-state=" ">
                                                            <label for="dest-type" class="text-primary">Tipo de
                                                                Cuenta</label>
                                                            <select id="dest-type" class="custom-select"
                                                                    required
                                                                    v-model="account_type"
                                                                    name="account_type">
                                                                <option value="1">De Cheques</option>
                                                                <option value="2">De Ahorros</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div v-if="receiver === 'VES'" class="col-md-6">
                                                        <div class="form-group text-center"
                                                             data-user-state=" ">
                                                            <label for="" class="text-primary">¿Cuenta con Pago
                                                                Móvil?</label>
                                                            <div class="text-center">
                                                                <input type="checkbox" id="pago_movil" name="pago_movil"
                                                                       value="1">
                                                                <label for="pago_movil" class="text-primary"> Sí</label>
                                                            </div>
                                                            <label for="" style="color:red">
                                                                * Por favor verifique que el número de teléfono del
                                                                destinatario
                                                                sea el afiliado a pago móvil.
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" v-if="country === 'US'">
                                                        <div class="form-group"
                                                             data-user-state=" ">
                                                            <label for="dest-aba_number" class="text-primary">
                                                                Número ABA
                                                            </label>
                                                            <input type="text" id="dest-aba_number" class="form-control"
                                                                   v-model="aba_number"
                                                                   required
                                                                   name="aba_number"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <footer>
                                        <a class="btn-transparent card--overlaid--dismiss float-right text-secondary py-2 font-14 px-2"
                                           @click="closeOverlaid"
                                           aria-label="Close">
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-secondary btn-block rounded-0 py-md-3"
                                                v-if="id">
                                            Guardar la edición y Continuar con el Envío
                                        </button>
                                        <button type="submit" class="btn btn-secondary btn-block rounded-0 py-md-3"
                                                v-else>
                                            Agregar Contacto y Continuar con el Envío
                                        </button>
                                    </footer>
                                </form>
                            </div>
                        </div>
                        <div id="add-contact-company" style="z-index: 1050;" class="card--overlaid">
                            <div class="card m-2 m-md-3 m-lg-4">
                                <header class="card-header bg-white">
                                    <div class="clearfix">
                                        <h6 class="modal-title text-primary font-weight-bold float-left">
                                            <img src="/img/landing/personPlus-secondary.svg" class="img-fluid mr-3">
                                            Datos de la Empresa Receptora
                                        </h6>
                                        <a class="btn-transparent card--overlaid--dismiss float-right text-secondary font-14 px-2 mr-n3"
                                           @click="closeOverlaid"
                                           aria-label="Close">
                                            Cancelar
                                        </a>
                                        <a v-if="id"
                                           class="btn-transparent float-right text-danger font-14 px-2  __del_destiny mr-3"
                                           @click="deleteDestination(id)"
                                           aria-label="Delete">
                                            Eliminar
                                        </a>
                                    </div>
                                    <div class="font-14 mt-3">
                                        Aquí puede ingresar o editar los datos asociados a la cuenta destino
                                    </div>
                                </header>
                                <form action="" id="__company_dest_form" @submit.prevent="createDestination">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 pr-lg-5">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="comp-name" class="text-primary">
                                                                Nombre de la empresa
                                                            </label>
                                                            <input type="text"
                                                                   id="comp-name"
                                                                   v-model="name"
                                                                   required
                                                                   name="name"
                                                                   class="form-control">
                                                            <input type="hidden" class="form-control" v-model="id"
                                                                   name="id">
                                                            <input type="hidden" class="form-control" v-model="type"
                                                                   name="type">
                                                            <small class="form-text">Debe ser idéntico al titular de la
                                                                cuenta
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" v-if="country === 'US'">
                                                        <div class="form-group">
                                                            <label for="comp-dba" class="text-primary">Doing Business As
                                                                (DBA)</label>
                                                            <input type="text" id="comp-dba"
                                                                   v-model="dba"
                                                                   name="dba"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="comp-website" class="text-primary">Sitio
                                                                Web</label>
                                                            <input type="text"
                                                                   id="comp-website"
                                                                   v-model="website"
                                                                   name="website"
                                                                   required
                                                                   class="form-control"
                                                                   placeholder="ej. http://example.com">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="comp-email" class="text-primary">Email</label>
                                                            <input type="text" id="comp-email" class="form-control"
                                                                   v-model="email"
                                                                   name="email"
                                                                   required
                                                                   placeholder="Email">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="comp-cellphone"
                                                                   class="text-primary">Celular</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <select name="pre-mobile"
                                                                            id="comp-pre-mobile"
                                                                            v-model="phonecode"
                                                                            required
                                                                            class="custom-select __flag_mobile flag-selector">
                                                                        <optgroup label="Latino América">
                                                                            <option value="+56"
                                                                                    data-flag="img/landing/flags/cl.svg">
                                                                                +56 Chile
                                                                            </option>
                                                                            <option value="+58"
                                                                                    data-flag="img/landing/flags/ve.svg"
                                                                                    selected>
                                                                                +58 Venezuela
                                                                            </option>
                                                                            <option value="+54"
                                                                                    data-flag="img/landing/flags/ar.svg">
                                                                                +54 Argentina
                                                                            </option>
                                                                            <option value="+57"
                                                                                    data-flag="img/landing/flags/co.svg">
                                                                                +57 Colombia
                                                                            </option>
                                                                            <option value="+51"
                                                                                    data-flag="img/landing/flags/pe.svg">
                                                                                +51 Perú
                                                                            </option>
                                                                            <option value="+52"
                                                                                    data-flag="img/landing/flags/mx.svg">
                                                                                +52 México
                                                                            </option>
                                                                            <option value="+55"
                                                                                    data-flag="img/landing/flags/br.svg">
                                                                                +55 Brazil
                                                                            </option>
                                                                            <!--<option value="+1"
                                                                                    data-flag="img/landing/flags/do.svg">
                                                                                +1 República Dominicana
                                                                            </option>-->
                                                                            <!--  <option value="+506" data-flag="img/landing/flags/cr.svg">
                                                                                  +506 Costa Rica
                                                                              </option> -->
                                                                            <option value="+507"
                                                                                    data-flag="img/landing/flags/pa.svg">
                                                                                +507 Panamá
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Norte America">
                                                                            <option value="+1"
                                                                                    data-flag="img/landing/flags/us.svg"
                                                                                    selected>
                                                                                +1 United States
                                                                            </option>
                                                                            <!--<option value="+1"
                                                                                    data-flag="img/landing/flags/ca.svg">
                                                                                +1 Canada
                                                                            </option>-->
                                                                        </optgroup>
                                                                        <optgroup label="Europa">
                                                                            <option value="+34"
                                                                                    data-flag="img/landing/flags/es.svg">
                                                                                +34 España
                                                                            </option>
                                                                            <option value="+351"
                                                                                    data-flag="img/landing/flags/pt.svg">
                                                                                +351 Portugal
                                                                            </option>
                                                                            <option value="+39"
                                                                                    data-flag="img/landing/flags/it.svg">
                                                                                +39 Italia
                                                                            </option>
                                                                            <option value="+33"
                                                                                    data-flag="img/landing/flags/fr.svg">
                                                                                +33 Francia
                                                                            </option>
                                                                            <option value="+49"
                                                                                    data-flag="img/landing/flags/de.svg">
                                                                                +49 Alemania
                                                                            </option>
                                                                            <option value="+44"
                                                                                    data-flag="img/landing/flags/gb.svg">
                                                                                +44 Reino Unido
                                                                            </option>
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                                <vue-mask
                                                                        class="form-control"
                                                                        mask="(r00) 000-0000"
                                                                        required
                                                                        :options="maskOptions"
                                                                        v-model="phonenumber"
                                                                        id="comp-cellphone"
                                                                        name="main-mobile">
                                                                </vue-mask>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="comp-main-office_phone" class="text-primary">Teléfono
                                                                Oficina</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <select name="pre-office_phone"
                                                                            id="comp-pre-office_phone"
                                                                            v-model="office_phone_code"
                                                                            required
                                                                            class="custom-select __flag_office flag-selector">
                                                                        <optgroup label="Latino América">
                                                                            <option value="+56"
                                                                                    data-flag="img/landing/flags/cl.svg">
                                                                                +56 Chile
                                                                            </option>
                                                                            <option value="+58"
                                                                                    data-flag="img/landing/flags/ve.svg"
                                                                                    selected>
                                                                                +58 Venezuela
                                                                            </option>
                                                                            <option value="+54"
                                                                                    data-flag="img/landing/flags/ar.svg">
                                                                                +54 Argentina
                                                                            </option>
                                                                            <option value="+57"
                                                                                    data-flag="img/landing/flags/co.svg">
                                                                                +57 Colombia
                                                                            </option>
                                                                            <option value="+51"
                                                                                    data-flag="img/landing/flags/pe.svg">
                                                                                +51 Perú
                                                                            </option>
                                                                            <option value="+52"
                                                                                    data-flag="img/landing/flags/mx.svg">
                                                                                +52 México
                                                                            </option>
                                                                            <option value="+55"
                                                                                    data-flag="img/landing/flags/br.svg">
                                                                                +55 Brazil
                                                                            </option>
                                                                            <!--<option value="+1"
                                                                                    data-flag="img/landing/flags/do.svg">
                                                                                +1 República Dominicana
                                                                            </option>-->
                                                                            <!--  <option value="+506" data-flag="img/landing/flags/cr.svg">
                                                                                  +506 Costa Rica
                                                                              </option> -->
                                                                            <option value="+507"
                                                                                    data-flag="img/landing/flags/pa.svg">
                                                                                +507 Panamá
                                                                            </option>
                                                                        </optgroup>
                                                                        <optgroup label="Norte America">
                                                                            <option value="+1"
                                                                                    data-flag="img/landing/flags/us.svg"
                                                                                    selected>
                                                                                +1 United States
                                                                            </option>
                                                                            <!--<option value="+1"
                                                                                    data-flag="img/landing/flags/ca.svg">
                                                                                +1 Canada
                                                                            </option>-->
                                                                        </optgroup>
                                                                        <optgroup label="Europa">
                                                                            <option value="+34"
                                                                                    data-flag="img/landing/flags/es.svg">
                                                                                +34 España
                                                                            </option>
                                                                            <option value="+351"
                                                                                    data-flag="img/landing/flags/pt.svg">
                                                                                +351 Portugal
                                                                            </option>
                                                                            <option value="+39"
                                                                                    data-flag="img/landing/flags/it.svg">
                                                                                +39 Italia
                                                                            </option>
                                                                            <option value="+33"
                                                                                    data-flag="img/landing/flags/fr.svg">
                                                                                +33 Francia
                                                                            </option>
                                                                            <option value="+49"
                                                                                    data-flag="img/landing/flags/de.svg">
                                                                                +49 Alemania
                                                                            </option>
                                                                            <option value="+44"
                                                                                    data-flag="img/landing/flags/gb.svg">
                                                                                +44 Reino Unido
                                                                            </option>
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                                <vue-mask
                                                                        class="form-control"
                                                                        mask="(r00) 000-0000"
                                                                        required
                                                                        :options="maskOptions"
                                                                        v-model="office_phonenumber"
                                                                        id="comp-main-office_phone"
                                                                        name="main-office_phone">
                                                                </vue-mask>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="comp-country" class="text-primary">País</label>
                                                            <select name="country"
                                                                    required
                                                                    @change="getStates('holder')"
                                                                    v-model="country"
                                                                    id="comp-country"
                                                                    class="custom-select flag-selector flag-selector--full">
                                                                <optgroup label="Sur América">
                                                                    <option value="VE"
                                                                            data-flag="/img/landing/flags/ve.svg">
                                                                        Venezuela
                                                                    </option>
                                                                    <option value="CO"
                                                                            data-flag="/img/landing/flags/co.svg">
                                                                        Colombia
                                                                    </option>
                                                                    <option value="PE"
                                                                            data-flag="/img/landing/flags/pe.svg">Perú
                                                                    </option>
                                                                    <option value="CL"
                                                                            data-flag="/img/landing/flags/cl.svg">Chile
                                                                    </option>
                                                                    <option value="AR"
                                                                            data-flag="/img/landing/flags/ar.svg">
                                                                        Argentina
                                                                    </option>
                                                                    <option value="BR"
                                                                            data-flag="/img/landing/flags/br.svg">Brazil
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Centro América">
                                                                    <option value="PA"
                                                                            data-flag="/img/landing/flags/pa.svg">Panamá
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Norte América">
                                                                    <option value="MX"
                                                                            data-flag="/img/landing/flags/mx.svg">México
                                                                    </option>
                                                                    <option value="US"
                                                                            data-flag="/img/landing/flags/us.svg">United
                                                                        States
                                                                    </option>
                                                                </optgroup>
                                                                <optgroup label="Europa">
                                                                    <option value="UK"
                                                                            data-flag="/img/landing/flags/gb.svg">Reino
                                                                        Unido
                                                                    </option>
                                                                    <option value="ES"
                                                                            data-flag="/img/landing/flags/es.svg">España
                                                                    </option>
                                                                    <option value="PT"
                                                                            data-flag="/img/landing/flags/pt.svg">
                                                                        Portugal
                                                                    </option>
                                                                    <option value="IT"
                                                                            data-flag="/img/landing/flags/it.svg">Italia
                                                                    </option>
                                                                    <option value="FR"
                                                                            data-flag="/img/landing/flags/fr.svg">
                                                                        Francia
                                                                    </option>
                                                                    <option value="DE"
                                                                            data-flag="/img/landing/flags/de.svg">
                                                                        Alemania
                                                                    </option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="comp-street"
                                                                   class="text-primary">Dirección</label>
                                                            <input id="comp-street"
                                                                   name="street"
                                                                   v-model="street"

                                                                   required
                                                                   type="text"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" v-if="country === 'US'">
                                                        <div class="form-group">
                                                            <label for="comp-zip_code" class="text-primary">Código
                                                                Postal</label>
                                                            <input id="comp-zip_code"
                                                                   type="text"
                                                                   class="form-control"
                                                                   v-model="zip_code"

                                                                   required
                                                                   name="zip_code">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="comp-city" class="text-primary">Ciudad</label>
                                                            <input id="comp-city" type="text" class="form-control"

                                                                   v-model="city"
                                                                   required
                                                                   name="city"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group __holder-dynamic_states company"
                                                             :data-holder-state="state">
                                                            <label for="comp-select_state"
                                                                   class="text-primary">Estado/Departamento</label>
                                                            <select id="comp-select_state" class="custom-select"
                                                                    required
                                                                    name="state">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div id="tribuNumber" class="col-md-6" v-if="country !== 'US'">
                                                        <div class="form-group">
                                                            <label for="comp-tax_id_number" class="text-primary">
                                                                Número de identificación tributaria
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="text" id="comp-tax_id_number"
                                                                       required
                                                                       name="tax_id_number"
                                                                       v-model="tax_id_number"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="comp_us_taxid" class="col-md-6" v-if="country === 'US'">
                                                        <div class="form-group">
                                                            <label for="comp-us-taxid" class="text-primary">
                                                                Número del registro del estado
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="text" id="comp-us-taxid"
                                                                       required
                                                                       name="tax_id_number"
                                                                       v-model="tax_id_number"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="compEmpId" class="col-md-6" v-if="country === 'US'">
                                                        <div class="form-group">
                                                            <label for="comp-empId" class="text-primary">
                                                                Número de identificación del empleador (EIN)
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="text" id="comp-empId"
                                                                       required
                                                                       name="ein"
                                                                       v-model="ein"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!--
                                                <h6 class="text-primary font-weight-bold mb-0 mt-4">Accionistas</h6>
                                                <div class="text-muted font-14 mb-3">Esta información es privada y será usada únicamente para efectos de seguridad</div>
                                                <div id="stockholders" class="row">
                                                    <div class="col-md-6 dynamicInput__col">
                                                        <div class="dynamicInput&#45;&#45;wrapper form-group">
                                                            <input type="text" class="form-control dynamicInput" placeholder="Nombre de accionista">
                                                            <button type="button" class="dynamicInput__delete">&times;</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <button id="addDynamicInput" class="btn btn-light btn-block font-14 text-left">+ Añadir nombre de accionista</button>
                                                    </div>
                                                </div>

                                                <hr>-->

                                                <h6 class="text-primary font-weight-bold mb-3 mt-4">
                                                    Datos de la cuenta de la empresa
                                                </h6>
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div v-if="receiver === 'VES'" class="alert alert-danger"
                                                             role="alert">
                                                            Por la normativa estadounidense vigente, ninguna empresa con
                                                            base
                                                            en Estados Unidos puede operar con bancos pertenecientes al
                                                            Estado
                                                            venezolano. No podemos operar con:
                                                            <ul>
                                                                <li>Banco de Venezuela</li>
                                                                <li>Banco del Tesoro</li>
                                                                <li>Banco del Bicentenario</li>
                                                                <li>Ningún otro banco perteneciente al Estado</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"
                                                             data-user-state=" ">
                                                            <label for="comp-bank_name" class="text-primary">
                                                                Nombre del Banco
                                                            </label>
                                                            <input type="text" id="comp-bank_name" class="form-control"
                                                                   required
                                                                   v-model="bank_name"
                                                                   name="bank_name"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"
                                                             data-user-state=" ">
                                                            <label for="comp-account_number" class="text-primary">
                                                                Número de cuenta
                                                            </label>
                                                            <input type="text" id="comp-account_number"
                                                                   class="form-control"
                                                                   required
                                                                   v-model="account_number"
                                                                   name="account_number"/>
                                                            <input type="hidden" id="comp-currency" class="form-control"
                                                                   v-model="receiver"
                                                                   name="currency"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group"
                                                             data-user-state=" ">
                                                            <label for="comp-type" class="text-primary">Tipo de
                                                                cuenta</label>
                                                            <select id="comp-type" class="custom-select"
                                                                    required
                                                                    v-model="account_type"
                                                                    name="account_type">
                                                                <option value="1">De Cheques</option>
                                                                <option value="2">De Ahorros</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div v-if="receiver === 'VES'" class="col-md-6">
                                                        <div class="form-group text-center"
                                                             data-user-state=" ">
                                                            <label for="" class="text-primary">¿Cuenta con Pago
                                                                Móvil?</label>
                                                            <div class="text-center">
                                                                <input type="checkbox" id="pago_movil2"
                                                                       name="pago_movil"
                                                                       value="1">
                                                                <label for="pago_movil2" class="text-primary">
                                                                    Sí</label>
                                                            </div>
                                                            <label for="" style="color:red">
                                                                * Por favor verifique que el número de teléfono del
                                                                destinatario
                                                                sea el afiliado a pago móvil.
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" v-if="country === 'US'">
                                                        <div class="form-group"
                                                             data-user-state=" ">
                                                            <label for="comp-aba_number" class="text-primary">
                                                                Número ABA
                                                            </label>
                                                            <input type="text" id="comp-aba_number" class="form-control"
                                                                   v-model="aba_number"
                                                                   name="aba_number"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <footer>
                                        <a class="btn-transparent card--overlaid--dismiss float-right text-secondary py-2 font-14 px-2"
                                           @click="closeOverlaid"
                                           aria-label="Close">
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-secondary btn-block rounded-0 py-md-3">
                                            Agregar Empresa y Continuar con el Envío
                                        </button>
                                    </footer>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="col-md-4">
                    <div class="card body-bg-color rounded text-primary sticky-top" style="top: 2rem;">
                        <div class="px-3 px-md-4 py-3">
                            <h6 class="text-primary font-weight-bold text-uppercase mb-4">Resumen de la transacción</h6>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="small text-truncate mr-2">Monto a retirar</div>
                                <div class="font-14 font-weight-bold text-truncate">
                                    {{transferAmount + ' ' + currency}}
                                </div>
                            </div>
                        </div>
                        <div class="px-3 px-md-4 py-4 border-top border-primary">
                            <button class="btn btn-secondary btn-pill btn-block"
                                    :class="{'disabled' : (!selected_account || forbiddenByAmount !== 0 || forbiddenByHours !== 0 || !minAmountBoolean)}"
                                    id="withdraw-submit"
                                    v-if="noDuplicate === false"
                                    v-on:click="submitForm">
                                <span class="mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="27"
                                     viewBox="0 0 28 27"><g><g><g><g><g><g><g><path
                                        fill="#fff"
                                        d="M11.88 26.237c-.302 0-.583-.022-.863-.065-1.662-.237-3.13-1.1-4.101-2.417a6.142 6.142 0 0 1-1.209-4.619c.432-3.065 3.087-5.374 6.173-5.374.28 0 .583.021.863.065a6.232 6.232 0 0 1 5.31 7.036c-.432 3.064-3.087 5.374-6.173 5.374zm-.022-13.339c-3.518 0-6.54 2.634-7.014 6.108a7.072 7.072 0 0 0 1.36 5.267 6.982 6.982 0 0 0 4.683 2.762c.324.043.67.065.993.065 3.518 0 6.54-2.633 7.014-6.13.54-3.863-2.158-7.467-6.043-8.007a7.667 7.667 0 0 0-.993-.065z"/></g></g></g></g></g><g><g><g><g><g><g><path
                                        fill="#fff"
                                        d="M9.657 18.532c0-.972.69-1.662 1.834-1.749V15.64h.648v1.165a3.346 3.346 0 0 1 1.942.928l-.496.604c-.54-.518-1.23-.798-1.835-.798-.712 0-1.23.345-1.23.95 0 .69.54.906 1.425 1.187 1.036.345 1.985.669 1.985 1.79 0 .929-.647 1.62-1.813 1.684v1.209h-.626v-1.252c-.582-.108-1.208-.367-1.618-.69l.475-.583c.496.367 1.143.561 1.597.561.669 0 1.122-.302 1.122-.863 0-.605-.583-.82-1.381-1.08-1.144-.388-2.03-.69-2.03-1.92z"/></g></g></g></g></g></g><g><g><g><g><g><path
                                        fill="#fff"
                                        d="M22.304 13.481v-3.237h-9.841A11.872 11.872 0 0 0 1.65 17.258l-.216.497v-.928c0-6.044 4.92-10.965 10.964-10.965h9.906V2.927L26.6 8.215zm-.863-8.503h-9.043C5.88 4.978.57 10.287.57 16.805v4.986l1.877-4.187c1.748-3.95 5.698-6.497 10.015-6.497h8.978v4.835l6.26-7.727L21.44.467z"/></g></g></g></g></g></g></g></svg>
                                </span>
                                Retirar Dólares
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
