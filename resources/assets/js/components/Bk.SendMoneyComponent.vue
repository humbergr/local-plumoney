<script src="../send-money.js"></script>

<template lang="html">
    <div class="row">
        <vue-toastr ref="toastr"></vue-toastr>
        <div class="col-md-8 pl-md-5 pr-md-4">
            <form class="" :action="formUrl" ref="form" method="post">
                <input type="hidden" name="_token" v-model="csrf">
                <!--<h6 class="text-primary font-weight-bold mb-4">Origen, destino y monto</h6>-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sm-origen" class="text-primary">Origen</label>
                            <select id="sm-origen" class="custom-select flag-selector flag-selector--full"
                                    v-model="sender"
                                    name="sender" v-on:change="getPrice">
                                <optgroup label="Latino América">
                                    <!--<option value="CLP" data-flag="img/landing/flags/cl.svg" selected>Chile</option>-->
                                    <option value="VES" data-flag="img/landing/flags/ve.svg">Venezuela</option>
                                    <!--<option value="ARS" data-flag="img/landing/flags/ar.svg">Argentina</option>-->
                                    <!-- <option value="BO" data-flag="img/landing/flags/bo.svg">Bolivia</option>
                                        <option value="BR" data-flag="img/landing/flags/br.svg">Brasil</option> -->
                                    <option value="COP" data-flag="img/landing/flags/co.svg">Colombia</option>
                                    <option value="PEN" data-flag="img/landing/flags/pe.svg">Perú</option>
                                    <!--    <option value="PY" data-flag="img/landing/flags/py.svg">Paraguay</option>
                                        <option value="UY" data-flag="img/landing/flags/uy.svg">Uruguay</option> -->
                                </optgroup>
                                <optgroup label="Others">
                                    <option value="USD" data-flag="img/landing/flags/us.svg" selected>United States
                                    </option>
                                    <!--    <option value="CA" data-flag="img/landing/flags/ca.svg">Canada</option> -->
                                    <option value="EUR" data-flag="img/landing/flags/es.svg">España</option>
                                    <!--    <option value="PT" data-flag="img/landing/flags/pt.svg">Portugal</option> -->
                                </optgroup>
                            </select>
                            <div class="small text-right text-danger mt-1">Campo Obligatorio</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sm-monto" class="text-primary">Monto a enviar</label>
                            <input type="text" id="sm-monto" v-model="to_send" name="to_send"
                                   class="form-control form-control-font-lg" placeholder="Escriba monto a enviar">
                            <div class="small text-right text-danger mt-1">Campo Obligatorio</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sm-destino" class="text-primary">Destino</label>
                            <select class="custom-select flag-selector flag-selector--full" v-model="receiver"
                                    name="receiver" v-on:change="getPrice">
                                <optgroup label="Latino América">
                                    <option value="CLP" data-flag="img/landing/flags/cl.svg" selected>Chile</option>
                                    <option value="VES" data-flag="img/landing/flags/ve.svg">Venezuela</option>
                                    <option value="ARS" data-flag="img/landing/flags/ar.svg">Argentina</option>
                                    <option value="COP" data-flag="img/landing/flags/co.svg">Colombia</option>
                                    <!-- <option value="BO" data-flag="img/landing/flags/bo.svg">Bolivia</option>
                                        <option value="BR" data-flag="img/landing/flags/br.svg">Brasil</option>
                                         -->
                                    <!-- <option value="PEN" data-flag="img/landing/flags/pe.svg">Perú</option>
                                       <option value="PY" data-flag="img/landing/flags/py.svg">Paraguay</option>
                                        <option value="UY" data-flag="img/landing/flags/uy.svg">Uruguay</option> -->
                                </optgroup>
                                <optgroup label="Others">
                                    <option value="USD" data-flag="img/landing/flags/us.svg">United States</option>
                                    <!--    <option value="CA" data-flag="img/landing/flags/ca.svg">Canada</option> -->
                                    <option value="EUR" data-flag="img/landing/flags/es.svg">España</option>
                                    <!--    <option value="PT" data-flag="img/landing/flags/pt.svg">Portugal</option> -->
                                </optgroup>
                            </select>
                            <div class="small text-right text-danger mt-1">Campo Obligatorio</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sm-amount" class="text-primary">Entregamos</label>
                            <input type="text" class="form-control custom-readonly" :value="toReceive" name="to_receive"
                                   readonly>
                        </div>
                    </div>
                </div>
                <input type="hidden" v-model="destination_id" name="destination_id" value="">

                <add-card @updatePaymentMethod="onPaymentMethodUpdate"/>

                <div id="recipient" class="mt-4 loader--wrapper" :class="{ '--loading': loader }">
                    <h6 class="text-primary font-weight-bold mb-2">Destino</h6>
                    <div class="font-14 text-primary">Cuentas bancarias de destino</div>
                    <div class="row mt-3">
                        <div class="col-3 col-xl-2">
                          <span data-toggle="tooltip" data-placement="left" title="Registrar Nuevo Contacto">
                              <button type="button" class="btn-as-card p-3 mb-4" style="height: 131px"
                                      data-toggle="modal" @click="clearAll()"
                                      data-target="#add-contact-conditionalModal">
                                  <img src="img/landing/add-person.svg" alt="Registrar Nuevo Contacto">
                                  <div class="sr-only">Registrar Nuevo Contacto</div>
                              </button>
                          </span>
                        </div>
                        <div class="col-9 col-xl-10">
                            <slick id="recipients-slider" class="mb-0" ref="slick" :options="slickOptions">
                                <div v-for="destination in destinations"
                                     v-on:click="selectDestination(destination.id, destination, $event)"
                                     class="cardBank selective mx-2"
                                     :class="{'--active' : (destination_id === destination.id)}">
                                    <img src="img/landing/person-primary.svg" alt="Person Icon" class="mb-2 mx-auto">
                                    <h6 class="cardBank__title">{{destination.name}}<br>{{destination.lastname}}</h6>
                                    <div class="cardBank__info">{{destination.bank_name}}<br>....
                                        {{destination.account_number.substr(destination.account_number.length - 4)}}
                                    </div>
                                    <button type="button"
                                            @click="editDestination(destination.id)"
                                            class="cardBank__edit text-muted">
                                        <i class="fa fa-sliders fa-rotate-90"></i>
                                    </button>
                                </div>
                            </slick>
                        </div>
                    </div>
                    <div class="loader">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <aside v-if="selected_account && to_send !== ''" class="col-md-4 d-none d-md-block">
            <div class="body-bg-color text-primary rounded h-100">
                <div class="p-3">
                    <h6 class="text-primary font-weight-bold text-uppercase">Resumen</h6>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="small text-truncate mr-2">Monto a transferir</div>
                        <div class="font-14 font-weight-bold text-truncate">
                            {{to_send.toLocaleString('en')}}
                            {{sender}}
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="small text-truncate mr-2">Comisión</div>
                        <div class="font-14 font-weight-bold text-truncate">
                            {{fees.toLocaleString('en')}}
                            {{sender}}
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="small text-truncate mr-2">Total a pagar</div>
                        <div class="font-14 font-weight-bold text-truncate">
                            {{payment_total.toLocaleString('en')}}
                            {{sender}}
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="small mr-2">
                            * Estos valores son aproximados y pueden variar en el cobro final
                            debido a comisiones bancarias o del proveedor de la tarjeta.
                        </div>
                    </div>
                </div>
                <div class="p-3 border-bottom border-primary">
                    <div class="font-14 font-weight-bold mb-1">A {{selected_account.name}}
                        {{selected_account.lastname}}
                    </div>
                    <div class="small">{{selected_account.country}}</div>
                    <div class="small">{{selected_account.bank_name}}
                        ....{{selected_account.account_number.substr(selected_account.account_number.length - 4)}}
                    </div>
                </div>
                <div class="p-3">
                    Entregamos<br>
                    <h4 class="mb-0">{{receiver}} {{toReceive}}</h4>
                    <div class="small">Entrega en 5 días hábiles</div>
                </div>
                <div class="p-3">
                    <button class="btn btn-secondary btn-pill btn-block" type="button" v-on:click="submitForm"
                            form="purchase-form">
                        <img class="d-none d-md-inline-block mr-2" src="img/landing/enviar-dinero.svg" height="32">
                        Enviar dinero
                    </button>
                </div>
            </div>
        </aside>

        <!-- add contact modal - conditional person or company -->
        <div class="modal fade" id="add-contact-conditionalModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title text-primary font-weight-bold">Tu transferencia va dirigida a</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-6">
                                <div id="openNewPersonModal"
                                     @click="openPersonModal()"
                                     class="cardBank mx-2">
                                    <img src="img/landing/person-primary.svg" alt="Person Icon" class="mb-2 mx-auto">
                                    <h6 class="cardBank__title">Persona</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div id="openNewCompanyModal"
                                     @click="openCompanyModal()"
                                     class="cardBank mx-2">
                                    <img src="img/landing/company-primary.svg" alt="Person Icon" class="mb-2 mx-auto">
                                    <h6 class="cardBank__title">Empresa</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- add contat modal -->
        <div class="modal fade" id="add-contactPerson-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header px-lg-4">
                        <h6 class="modal-title text-primary font-weight-bold">Agrega tu contacto para transferir</h6>
                    </div>
                    <form action="" id="destForm">
                        <div class="modal-body px-lg-4">
                            <div class="row">
                                <div class="col-md-12 text-right"
                                v-if="name !== ''">
                                    <a href="javascript:void(0)"
                                       @click="deleteDestination(id)"
                                       class="btn btn-danger">Eliminar</a>
                                </div>
                                <div class="col-md-12 pr-lg-5">
                                    <h5 class="text-primary font-weight-bold mb-1">Datos Personales</h5>
                                    <p class="text-muted font-weight-light font-14 mb-3">Aquí puede ingresar y editar
                                        los datos asociados a su cuenta con American Kriptos Bank</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dest-name" class="text-primary">Nombre</label>
                                                <input type="text" class="form-control"
                                                       id="dest-name"
                                                       value=""
                                                       v-model="name"
                                                       required
                                                       name="name">
                                                <input type="hidden" class="form-control" v-model="id" value=""
                                                       name="id">
                                                <input type="hidden" class="form-control" v-model="type" value=""
                                                       name="type">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dest-lastname" class="text-primary">Apellido</label>
                                                <input type="text" class="form-control"
                                                       id="dest-lastname"
                                                       value=""
                                                       v-model="lastname"
                                                       required
                                                       name="lastname">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="dest-email" class="text-primary">Email</label>
                                                <input type="email" class="form-control"
                                                       id="dest-email"
                                                       value=""
                                                       v-model="email"
                                                       required
                                                       name="email">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="dest-main-mobile" class="text-primary">
                                                    Teléfono Móvil</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <select name="pre-mobile"
                                                                id="dest-pre-mobile"
                                                                v-model="phonecode"
                                                                required
                                                                class="custom-select">
                                                            <option value="+1">+1
                                                            </option>
                                                            <option value="+34">+34
                                                            </option>
                                                            <option value="+51">+51
                                                            </option>
                                                            <option value="+54">+54
                                                            </option>
                                                            <option value="+55">+55
                                                            </option>
                                                            <option value="+56">+56
                                                            </option>
                                                            <option value="+57">+57
                                                            </option>
                                                            <option value="+58">+58
                                                            </option>
                                                            <option value="+351">+351
                                                            </option>
                                                            <option value="+591">+591
                                                            </option>
                                                            <option value="+595">+595
                                                            </option>
                                                            <option value="+598">+598
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <input type="text" class="form-control"
                                                           required
                                                           v-model="phonenumber"
                                                           id="dest-main-mobile"
                                                           value=""
                                                           name="main-mobile">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="dest-birthday" class="text-primary">
                                                    Fecha de nacimiento
                                                </label>
                                                <!--<input type="text" name="birthday"
                                                       required
                                                       v-model="birthday"
                                                       data-field="date"
                                                       id="dest-birthday"
                                                       class="form-control"/>-->
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
                                        </div>
                                        <div class="col-md-12">
                                            <hr>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="dest-id_type" class="text-primary">
                                                    Tipo de identificación
                                                </label>
                                                <select name="id_type"
                                                        required
                                                        v-model="id_type"
                                                        id="dest-id_type" class="custom-select">
                                                    <option value="1">Cédula</option>
                                                    <option value="2">Licencia de conducir</option>
                                                    <option value="3">Número de seguro social</option>
                                                    <option value="4">Pasaporte</option>
                                                </select>
                                            </div>
                                        </div>
<!--                                        <div class="col-md-4">-->
<!--                                            <div class="form-group">-->
<!--                                                <label for="dest-id_origin_date" class="text-primary">-->
<!--                                                    Fecha de expedición</label>-->
<!--                                                <v-date-picker v-model='id_origin_date'-->
<!--                                                               :input-props="{-->
<!--                                                                    id: 'dest-id_origin_date',-->
<!--                                                                    class: 'form-control',-->
<!--                                                                    required: true,-->
<!--                                                                    name: 'id_origin_date',-->
<!--                                                                    type: 'text'-->
<!--                                                               }"-->
<!--                                                               :masks="{-->
<!--                                                                    input: 'MM/DD/YYYY',-->
<!--                                                                    data: 'MM/DD/YYYY',-->
<!--                                                               }"-->
<!--                                                ></v-date-picker>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="col-md-4">-->
<!--                                            <div class="form-group">-->
<!--                                                <label for="dest-id_end_date" class="text-primary">-->
<!--                                                    Fecha de expiración (Si cumple)</label>-->
<!--                                                <v-date-picker v-model='id_end_date'-->
<!--                                                               :input-props="{-->
<!--                                                                    id: 'dest-id_end_date',-->
<!--                                                                    class: 'form-control',-->
<!--                                                                    name: 'id_end_date',-->
<!--                                                                    type: 'text'-->
<!--                                                               }"-->
<!--                                                               :masks="{-->
<!--                                                                    input: 'MM/DD/YYYY',-->
<!--                                                                    data: 'MM/DD/YYYY',-->
<!--                                                               }"-->
<!--                                                ></v-date-picker>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="dest-id_number" class="text-primary">
                                                    Número del documento</label>
                                                <input type="text" name="id_number"
                                                       required
                                                       v-model="id_number"
                                                       id="dest-id_number"
                                                       class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="dest-id_origin_country" class="text-primary">
                                                    País que emite</label>
                                                <select name="id_origin_country"
                                                        readonly
                                                        required
                                                        @change="getStates('id_doc')"
                                                        v-model="id_origin_country"
                                                        id="dest-id_origin_country"
                                                        style="pointer-events: none"
                                                        class="custom-select flag-selector disable_select flag-selector--full">
                                                    <optgroup label="Latino América">
                                                        <option value="VE" data-flag="img/landing/flags/ve.svg">
                                                            Venezuela
                                                        </option>
                                                        <option value="AR" data-flag="img/landing/flags/ar.svg">
                                                            Argentina
                                                        </option>
                                                        <!--<option value="BO" data-flag="img/landing/flags/bo.svg">
                                                            Bolivia
                                                        </option>
                                                        <option value="BR" data-flag="img/landing/flags/br.svg">
                                                            Brasil
                                                        </option>-->
                                                        <option value="CL" data-flag="img/landing/flags/cl.svg">
                                                            Chile
                                                        </option>
                                                        <option value="CO" data-flag="img/landing/flags/co.svg">
                                                            Colombia
                                                        </option>
                                                        <!--<option value="PE" data-flag="img/landing/flags/pe.svg">
                                                            Perú
                                                        </option>
                                                        <option value="PY" data-flag="img/landing/flags/py.svg">
                                                            Paraguay
                                                        </option>
                                                        <option value="UY" data-flag="img/landing/flags/uy.svg">
                                                            Uruguay
                                                        </option>-->
                                                    </optgroup>
                                                    <optgroup label="Otros">
                                                        <option value="US" data-flag="img/landing/flags/us.svg">
                                                            United States
                                                        </option>
                                                        <!-- <option value="CA" data-flag="img/landing/flags/ca.svg">
                                                             Canada
                                                         </option>-->
                                                        <option value="ES" data-flag="img/landing/flags/es.svg">
                                                            España
                                                        </option>
                                                        <!--<option value="PT" data-flag="img/landing/flags/pt.svg">
                                                            Portugal
                                                        </option>-->
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4"
                                             v-if="id_origin_country === 'US' && id_type === '2'"
                                        >
                                            <div class="form-group __id-dynamic_states"
                                                 :data-id-state="id_origin_state">
                                                <label for="dest-id_origin_state" class="text-primary">Estado</label>
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
                                                        readonly
                                                        @change="getStates('holder')"
                                                        v-model="country"
                                                        id="dest-country"
                                                        style="pointer-events: none"
                                                        class="custom-select flag-selector disable_select flag-selector--full">
                                                    <optgroup label="Latino América">
                                                        <option value="VE" data-flag="img/landing/flags/ve.svg">
                                                            Venezuela
                                                        </option>
                                                        <option value="AR" data-flag="img/landing/flags/ar.svg">
                                                            Argentina
                                                        </option>
                                                        <!--<option value="BO" data-flag="img/landing/flags/bo.svg">
                                                            Bolivia
                                                        </option>
                                                        <option value="BR" data-flag="img/landing/flags/br.svg">
                                                            Brasil
                                                        </option>-->
                                                        <option value="CL" data-flag="img/landing/flags/cl.svg">
                                                            Chile
                                                        </option>
                                                        <option value="CO" data-flag="img/landing/flags/co.svg">
                                                            Colombia
                                                        </option>
                                                        <!--<option value="PE" data-flag="img/landing/flags/pe.svg">
                                                            Perú
                                                        </option>
                                                        <option value="PY" data-flag="img/landing/flags/py.svg">
                                                            Paraguay
                                                        </option>
                                                        <option value="UY" data-flag="img/landing/flags/uy.svg">
                                                            Uruguay
                                                        </option>-->
                                                    </optgroup>
                                                    <optgroup label="Otros">
                                                        <option value="US" data-flag="img/landing/flags/us.svg">
                                                            United States
                                                        </option>
                                                        <!-- <option value="CA" data-flag="img/landing/flags/ca.svg">
                                                             Canada
                                                         </option>-->
                                                        <option value="ES" data-flag="img/landing/flags/es.svg">
                                                            España
                                                        </option>
                                                        <!--<option value="PT" data-flag="img/landing/flags/pt.svg">
                                                            Portugal
                                                        </option>-->
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="dest-street" class="text-primary">Dirección</label>
                                                <input id="dest-street"
                                                       name="street"
                                                       v-model="street"
                                                       value=""
                                                       required
                                                       type="text"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="dest-city" class="text-primary">Ciudad</label>
                                                <input id="dest-city" type="text" class="form-control"
                                                       value=""
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
                                        <div class="col-md-4" v-if="country === 'US'">
                                            <div class="form-group">
                                                <label for="dest-zip_code" class="text-primary">Código Postal</label>
                                                <input id="dest-zip_code"
                                                       type="text"
                                                       class="form-control"
                                                       v-model="zip_code"
                                                       value=""
                                                       required
                                                       name="zip_code">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <hr>
                                        </div>
                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <div class="form-group"
                                                 data-user-state=" ">
                                                <label for="dest-account_number" class="text-primary">
                                                    Número de cuenta
                                                </label>
                                                <input type="text" id="dest-account_number" class="form-control"
                                                       required
                                                       v-model="account_number"
                                                       name="account_number"/>
                                                <input type="hidden" id="dest-currency" class="form-control"
                                                       v-model="receiver"
                                                       name="currency"/>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group"
                                                 data-user-state=" ">
                                                <label for="dest-type" class="text-primary">Tipo de cuenta</label>
                                                <select id="dest-type" class="custom-select"
                                                        required
                                                        v-model="account_type"
                                                        name="account_type">
                                                    <option value="1">De Cheques</option>
                                                    <option value="2">De Ahorros</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" v-if="country === 'US'">
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
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-secondary btn-pill px-4"
                                    v-on:click="createDestination">Agregar contacto
                            </button>
                        </div>
                    </form>
                    <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">
                        <span class="d-none d-md-inline">Cerrar</span>
                        <span class="d-md-none">&times;</span>
                    </button>
                    <img src="img/landing/add-contact-modal.png"
                         class="d-none d-md-block modal-animated-img animated flipInY">
                </div>
            </div>
        </div>
        <!-- add contact as company - modal -->
        <div class="modal fade" id="add-contactCompany-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header px-lg-4">
                        <h6 class="modal-title text-primary font-weight-bold">
                            <img src="img/landing/companyPlus-secondary.svg" class="img-fluid mr-3">
                            Agrega la empresa a la que vas a transferir
                        </h6>
                    </div>
                    <form action="">
                        <div class="modal-body px-lg-4">
                        <div class="row">
                            <div class="col-md-12 text-right"
                                 v-if="name !== ''">
                                <a href="javascript:void(0)"
                                   @click="deleteDestination(id)"
                                   class="btn btn-danger">Eliminar</a>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="comp-name" class="text-primary">Nombre de la empresa</label>
                                    <input type="text"
                                           id="comp-name"
                                           v-model="name"
                                           required
                                           name="name"
                                           class="form-control">
                                    <input type="hidden" class="form-control" v-model="id" value=""
                                           name="id">
                                    <input type="hidden" class="form-control" v-model="type" value=""
                                           name="type">
                                    <small class="form-text">Debe ser idéntico al titular de la cuenta</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="comp-dba" class="text-primary">Doing Business As (DBA)</label>
                                    <input type="text" id="comp-dba"
                                           v-model="dba"
                                           name="dba"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="comp-website" class="text-primary">Sitio Web</label>
                                    <input type="text"
                                           id="comp-website"
                                           v-model="website"
                                           name="website"
                                           required
                                           class="form-control"
                                           placeholder="ej. http://example.com">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="comp-email" class="text-primary">Email</label>
                                    <input type="text" id="comp-email" class="form-control"
                                           v-model="email"
                                           name="email"
                                           required
                                           placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="comp-cellphone" class="text-primary">Celular</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <select name="pre-mobile"
                                                    id="comp-pre-mobile"
                                                    v-model="phonecode"
                                                    required
                                                    class="custom-select">
                                                <option value="+1">+1
                                                </option>
                                                <option value="+34">+34
                                                </option>
                                                <option value="+51">+51
                                                </option>
                                                <option value="+54">+54
                                                </option>
                                                <option value="+55">+55
                                                </option>
                                                <option value="+56">+56
                                                </option>
                                                <option value="+57">+57
                                                </option>
                                                <option value="+58">+58
                                                </option>
                                                <option value="+351">+351
                                                </option>
                                                <option value="+591">+591
                                                </option>
                                                <option value="+595">+595
                                                </option>
                                                <option value="+598">+598
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" id="comp-cellphone"
                                               required
                                               v-model="phonenumber"
                                               name="main-mobile"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 px-md-1">
                                <div class="form-group">
                                    <label for="comp-main-office_phone" class="text-primary">Teléfono
                                        Oficina</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <select name="pre-office_phone"
                                                    id="comp-pre-office_phone"
                                                    v-model="office_phone_code"
                                                    required
                                                    class="custom-select">
                                                <option value="+1">+1
                                                </option>
                                                <option value="+34">+34
                                                </option>
                                                <option value="+51">+51
                                                </option>
                                                <option value="+54">+54
                                                </option>
                                                <option value="+55">+55
                                                </option>
                                                <option value="+56">+56
                                                </option>
                                                <option value="+57">+57
                                                </option>
                                                <option value="+58">+58
                                                </option>
                                                <option value="+351">+351
                                                </option>
                                                <option value="+591">+591
                                                </option>
                                                <option value="+595">+595
                                                </option>
                                                <option value="+598">+598
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" id="comp-main-office_phone"
                                               required
                                               v-model="office_phonenumber"
                                               name="main-office_phone"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="comp-country" class="text-primary">País</label>
                                    <select name="country"
                                            required
                                            readonly
                                            @change="getStates('holder')"
                                            v-model="country"
                                            id="comp-country"
                                            style="pointer-events: none"
                                            class="custom-select flag-selector disable_select flag-selector--full">
                                        <optgroup label="Latino América">
                                            <option value="VE" data-flag="img/landing/flags/ve.svg">
                                                Venezuela
                                            </option>
                                            <option value="AR" data-flag="img/landing/flags/ar.svg">
                                                Argentina
                                            </option>
                                            <!--<option value="BO" data-flag="img/landing/flags/bo.svg">
                                                Bolivia
                                            </option>
                                            <option value="BR" data-flag="img/landing/flags/br.svg">
                                                Brasil
                                            </option>-->
                                            <option value="CL" data-flag="img/landing/flags/cl.svg">
                                                Chile
                                            </option>
                                            <option value="CO" data-flag="img/landing/flags/co.svg">
                                                Colombia
                                            </option>
                                            <!--<option value="PE" data-flag="img/landing/flags/pe.svg">
                                                Perú
                                            </option>
                                            <option value="PY" data-flag="img/landing/flags/py.svg">
                                                Paraguay
                                            </option>
                                            <option value="UY" data-flag="img/landing/flags/uy.svg">
                                                Uruguay
                                            </option>-->
                                        </optgroup>
                                        <optgroup label="Otros">
                                            <option value="US" data-flag="img/landing/flags/us.svg">
                                                United States
                                            </option>
                                            <!-- <option value="CA" data-flag="img/landing/flags/ca.svg">
                                                 Canada
                                             </option>-->
                                            <option value="ES" data-flag="img/landing/flags/es.svg">
                                                España
                                            </option>
                                            <!--<option value="PT" data-flag="img/landing/flags/pt.svg">
                                                Portugal
                                            </option>-->
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="comp-street" class="text-primary">Dirección</label>
                                    <input id="comp-street"
                                           name="street"
                                           v-model="street"
                                           value=""
                                           required
                                           type="text"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4" v-if="country === 'US'">
                                <div class="form-group">
                                    <label for="comp-zip_code" class="text-primary">Código Postal</label>
                                    <input id="comp-zip_code"
                                           type="text"
                                           class="form-control"
                                           v-model="zip_code"
                                           value=""
                                           required
                                           name="zip_code">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="comp-city" class="text-primary">Ciudad</label>
                                    <input id="comp-city" type="text" class="form-control"
                                           value=""
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
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group"
                                     data-user-state=" ">
                                    <label for="comp-account_number" class="text-primary">
                                        Número de cuenta
                                    </label>
                                    <input type="text" id="comp-account_number" class="form-control"
                                           required
                                           v-model="account_number"
                                           name="account_number"/>
                                    <input type="hidden" id="comp-currency" class="form-control"
                                           v-model="receiver"
                                           name="currency"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group"
                                     data-user-state=" ">
                                    <label for="comp-type" class="text-primary">Tipo de cuenta</label>
                                    <select id="comp-type" class="custom-select"
                                            required
                                            v-model="account_type"
                                            name="account_type">
                                        <option value="1">De Cheques</option>
                                        <option value="2">De Ahorros</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-secondary btn-pill px-4"
                                    v-on:click="createDestination">
                                Agregar contacto
                            </button>
                        </div>
                    </form>
                </div>
                <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">
                    <span class="d-none d-md-inline">Cerrar</span>
                    <span class="d-md-none">&times;</span>
                </button>
                <img src="img/landing/add-company-modal.png"
                     class="d-none d-md-block modal-animated-img animated flipInY">
            </div>
        </div>
    </div>
</template>

<style lang="css">
</style>
