<script src="../wallet-transaction.js"></script>

<style lang="scss">
    @import '../../sass/__ign_sendCash.scss';
</style>

<template lang="html">
    <div class="container px-sm-0">
        <div class="row">
            <div class="col-12 mx-auto px-0 px-md-3">
                <div class="card shadow-none mb-md-4 wow fadeInUp"
                     style="z-index: 10; background: transparent; width: 100%">
                    <div class="row __no-wrap">
                        <div class="col-md-8 pr-md-0 pl-md-0 animated fadeIn __general_transaction_info"
                             id="transaction-info">
                            <div class="body-bg-color p-3 p-md-4">
                                <div class="media justify-content-center mb-3">
                                    <img src="/img/landing/cash-secondary.svg" class="img-fluid mb-0 mr-3">
                                    <div class="media-body">
                                        <h5 class="d-inline-block text-primary font-weight-bold mb-0">Pago asistido</h5>
                                        <span v-if="queue_number > 0" class="badge badge-info float-right"
                                              style="font-size: 14px">En cola: {{queue_number}}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6 offset-3">
                                        <div class="d-flex flex-column justify-content-center align-items-center text-center text-primary mb-4 mb-md-0">
                                            <small class="mb-2">Quieres cargar</small>
                                            <img :src="getCountryImage(order.currency)" alt="Flag"
                                                 class="img-fluid"
                                                 style="max-height: 26px; max-width: 26px; object-fit: cover">
                                            <h3 class="mb-0">{{order.currency}}
                                                {{order.amount.toLocaleString('en')}}</h3>
                                        </div>
                                    </div>

                                    <div class="__exchange_rate"
                                         v-if="recomended_price > 1">
                                        Tasa de cambio:
                                        <strong>1 {{order.currency}}</strong> =
                                        <strong>{{recommendedUtility()}} USD</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-2">
                                <form class="__form_container d-flex flex-column"
                                      action="">
                                    <div class="__accounts_container">
                                        <span class="__accounts_counter mb-2 mt-2 d-block">
                                            {{accounts_to_pay.length}} cuentas listadas
                                        </span>
                                        <!--Accounts to Pay -->
                                        <div class="__account_data" v-for="(account_to_pay, index) in accounts_to_pay">
                                            <span class="__account_index_n">{{index + 1}}</span>
                                            <div class="body-bg-color rounded py-3 mb-4">
                                                <div class="row no-gutters">
                                                    <div class="col-6 col-md-4 text-primary mb-4 mb-md-0 __account_b_info d-flex align-content-center flex-wrap">
                                                        <div class="text-left">
                                                            <p class="__l_account_title">
                                                                Cuenta
                                                                <br class="d-md-none">
                                                                {{capitalizeString(account_to_pay.bank_name)}}
                                                            </p>
                                                            <h4 class="__l_account_number">
                                                                <small>{{account_to_pay.account_type}}</small>
                                                                <br>
                                                                {{account_to_pay.account_number}}
                                                            </h4>
                                                            <span class="__l_owner_data"
                                                                  v-if="account_to_pay.account_owner">
                                                                Titular:
                                                                {{capitalizeString(account_to_pay.account_owner)}}
                                                            </span>
                                                            <br>
                                                            <span class="__l_owner_data"
                                                                  v-if="account_to_pay.id_number">
                                                                Doc. de Identidad:
                                                                {{capitalizeString(account_to_pay.id_number)}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 d-md-flex d-none small align-content-center flex-wrap">
                                                        <h4 class="__l_deposit_value">
                                                            <span class="d-block">Valor:</span>
                                                            <strong>
                                                                {{order.currency}}
                                                                {{parseFloat(account_to_pay.fiat_amount).toLocaleString('en')}}
                                                            </strong>
                                                        </h4>
                                                    </div>

                                                    <wallets-countdown :account="account_to_pay"
                                                               :accounts="account_to_pay"
                                                               :order="order"
                                                               :operator="operatorID"
                                                               v-on:getMessages="getMessages"></wallets-countdown>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- chat starts -->
                        <aside class="col-md-4 pl-md-0 d-none d-md-block animated fadeIn" id="transaction-chat">
                            <div class="oChat loader--wrapper sticky-top" :class="{ '--loading': loader }">
                                <div class="oChat__header px-3 py-2 py-md-3">
                                    <div class="media">
                                        <img src="/img/landing/msg-light.svg" class="img-fluid mr-3">
                                        <div class="media-body text-primary">
                                            <h6 class="font-weight-bold lh-125 mb-0">Chat</h6>
                                            <h6 class="lh-125 mb-0">de pago asistido.</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="oChat__body p-3" ref="scroller">
                                    <div class="oChat__item mb-4 --announce">
                                        <div class="oChat__item__msg">
                                            <div class="oChat__item__avatar rounded-circle">
                                            </div>
                                            <div class="oChat__item__msgText">
                                                Bienvenido a nuestro chat de pago asistido.
                                                <br><br>
                                                <span v-if="queue_number > 0">
                                                    En este momento te encuentras en cola y en la posicion numero
                                                    {{queue_number}}.
                                                    <br><br>
                                                </span>
                                                En breve recibirás asesoría de parte de uno de nuestros ejecutivos de
                                                atención al cliente, el te enviará una o varías cuentas bancarias a las
                                                que deberas depositar o transferir el dinero que quieres enviar.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="oChat__item mb-4 --announce" v-if="sendNoHoursMessage === 1">
                                        <div class="oChat__item__msg">
                                            <div class="oChat__item__avatar rounded-circle">
                                            </div>
                                            <div class="oChat__item__msgText">
                                                En estos momentos nuestros operadores no se encuentran activos para
                                                atender su solicitud. Nuestro horario de atención es desde las
                                                9:00 AM (EST Miami) hasta las 7:00 PM (EST Miami).
                                            </div>
                                        </div>
                                    </div>
                                    <div class="" v-for="msg in messages">
                                        <div v-if="msg.msg !== '' && msg.type === 1" class="oChat__item mb-4"
                                             :class="{'--sent' : (user.id === msg.sender_id), '--received' : (user.id !== msg.sender_id)}">
                                            <div class="oChat__item__msg">
                                                <div class="oChat__item__avatar rounded-circle">
                                                    <img src="/img/ign_avatar.jpg"
                                                         v-if="user.id !== msg.sender_id"
                                                         class="object-cover rounded-circle">
                                                    <img :src="selfie = profile.selfie ? '/' + profile.selfie : '/img/cb-img/avatar.png'"
                                                         v-else
                                                         class="object-cover rounded-circle">
                                                </div>
                                                <div class="oChat__item__msgText">
                                                    {{msg.msg}}
                                                    <div class="oChat__item__msgText" v-if="msg.attachment_name">
                                                        <br>
                                                        <br>
                                                        <a :class="{'attachment-white' : (user.id !== msg.sender_id)}"
                                                           :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                                           class="font-weight-bold">{{msg.attachment_name}} <i
                                                            class="fa fa-paperclip va-middle"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="msg.attachment_name && msg.msg === '' && msg.type === 1"
                                             class="oChat__item mb-4"
                                             :class="{'--sent' : (user.id === msg.sender_id), '--received' : (user.id !== msg.sender_id)}">
                                            <div class="oChat__item__msg">
                                                <div v-if="msg.msg === ''" class="oChat__item__avatar rounded-circle">
                                                    <img src="/img/ign_avatar.jpg"
                                                         v-if="user.id !== msg.sender_id"
                                                         class="object-cover rounded-circle">
                                                    <img :src="selfie = profile.selfie ? '/' + profile.selfie : '/img/cb-img/avatar.png'"
                                                         v-else
                                                         class="object-cover rounded-circle">
                                                </div>
                                                <div class="oChat__item__msgText">
                                                    <a :class="{'attachment-white' : (user.id !== msg.sender_id)}"
                                                       :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                                       class="font-weight-bold">{{msg.attachment_name}} <i
                                                        class="fa fa-paperclip va-middle"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="msg.type === 2" class="oChat__item mb-4 --received">
                                            <div class="oChat__item__msg" style="width: 85%">
                                                <div class="oChat__item__avatar rounded-circle">
                                                    <img src="/img/ign_avatar.jpg"
                                                         class="object-cover rounded-circle">
                                                </div>
                                                <div class="oChat__item__msgText">
                                                    <div class="text-center">
                                                        <div class="font-weight-bold">
                                                            <span class="d-block mb-2">Cuenta Bancaria</span>
                                                            {{order.sender_fiat_amount.toLocaleString('en')}}
                                                            {{order.sender_fiat}}
                                                        </div>
                                                        <div>
                                                            Cuenta {{msg.json_data.bank_name}}
                                                            {{msg.json_data.account_number}}
                                                        </div>
                                                        <div class="clearfix mt-2"
                                                             v-if="!msg.json_data.canceled && !msg.payed && !msg.failed">
                                                            <div class="float-left text-orange">Tiempo limite</div>
                                                            <div class="float-right text-orange">
                                                                <div class="d-flex justify-content-center">
                                                                    <div class="text-orange mx-2">
                                                                        <small class="d-block">horas</small>
                                                                        {{msg.hours}}
                                                                    </div>
                                                                    <div class="text-orange mx-2">
                                                                        <small class="d-block">minutos</small>
                                                                        {{msg.minutes}}
                                                                    </div>
                                                                    <div class="text-orange mx-2">
                                                                        <small class="d-block">segundos</small>
                                                                        {{msg.seconds}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix mt-2" v-if="msg.payed">
                                                            <div class="text-center text-orange">Pagada</div>
                                                        </div>
                                                        <div class="clearfix mt-2" v-if="msg.failed">
                                                            <div class="text-center text-orange">Fallida</div>
                                                        </div>
                                                        <div class="clearfix mt-2" v-if="msg.json_data.canceled">
                                                            <div class="text-center text-orange">Cancelada</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="oChat__tools p-3">
                                    <div class="oChat__tools__textarea--wrapper">
                                        <textarea id="" rows="2" v-model="message" class="form-control lh-125 pr-5"
                                                  placeholder="Su mensaje..."></textarea>
                                        <button v-on:click="sendMessage"
                                                class="oChat__tools__sendBtn btn btn-transparent btn-rounded my-auto">
                                            <img src="/img/landing/send-secondary.svg" class="img-fluid">
                                        </button>
                                    </div>
                                </div>
                                <div class="loader">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                         role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>

                    <!-- mobile buttons for upload document and switch to chat  -->
                </div>

                <!--                <div class="d-none d-md-block text-primary small px-3 px-md-0 wow fadeInUp">-->
                <!--                    <h6 class="text-center">Información importante</h6>-->
                <!--                    <p>1 Date available will be displayed on receipt for international transfers over $15. Service and-->
                <!--                        funds may be delayed or unavailable depending on certain factors including the Service selected,-->
                <!--                        the selection of delayed delivery options, special terms applicable to each Service, amount-->
                <!--                        sent, destination country, currency availability, regulatory issues, consumer protection issues,-->
                <!--                        identification requirements, delivery restrictions, agent location hours, and differences in-->
                <!--                        time zones (collectively, “Restrictions”). Additional restrictions may apply; see our terms and-->
                <!--                        conditions for details.</p>-->
                <!--                </div>-->
            </div>
        </div>
        <div class="__blackout d-md-none"></div>
        <a class="__flike_chat_btn"
           @click="switchChat"
           id="__chat_switch_button">
            <img src="/img/landing/akb-logo-chat.svg" class="img-fluid">
            <span id="talkAdvisor-icon"
                  class="__talkAdvisor-icon"
                  v-if="new_messages > 0">
                {{new_messages}}
            </span>
            <span class="__talk_helper">Toca el ícono para ocultar el chat -></span>
        </a>
        <section class="animated fadeIn __transaction_chat_mobile" id="transaction-chat-mobile">
            <div class="oChat loader--wrapper sticky-top" :class="{ '--loading': loader }">
                <div class="oChat__header px-3 py-2 py-md-3">
                    <div class="media">
                        <img src="/img/landing/msg-light.svg" class="img-fluid mr-3">
                        <div class="media-body text-primary">
                            <h6 class="font-weight-bold lh-125 mb-0">Chat</h6>
                            <h6 class="lh-125 mb-0">de pago asistido.</h6>
                        </div>
                    </div>
                </div>
                <div class="oChat__body p-3" id="oChat__mobile" ref="scroller">
                    <div class="oChat__item mb-4 --announce">
                        <div class="oChat__item__msg">
                            <div class="oChat__item__avatar rounded-circle">
                            </div>
                            <div class="oChat__item__msgText">
                                Bienvenido a nuestro chat de pago asistido.
                                <br><br>
                                En segundos recibirás asesoría de parte de uno de nuestros ejecutivos de atención al
                                cliente, el te enviará una o varías cuentas bancarias a las que deberas depositar o
                                transferir el dinero que quieres enviar.
                            </div>
                        </div>
                    </div>
                    <div class="" v-for="msg in messages">
                        <div v-if="msg.msg !== '' && msg.type === 1" class="oChat__item mb-4"
                             :class="{'--sent' : (user.id === msg.sender_id), '--received' : (user.id !== msg.sender_id)}">
                            <div class="oChat__item__msg">
                                <div class="oChat__item__avatar rounded-circle">
                                    <img src="/img/ign_avatar.jpg"
                                         v-if="user.id !== msg.sender_id"
                                         class="object-cover rounded-circle">
                                    <img :src="selfie = profile.selfie ? '/' + profile.selfie : '/img/cb-img/avatar.png'"
                                         v-else
                                         class="object-cover rounded-circle">
                                </div>
                                <div class="oChat__item__msgText">
                                    {{msg.msg}}
                                    <div class="oChat__item__msgText" v-if="msg.attachment_name">
                                        <br>
                                        <br>
                                        <a :class="{'attachment-white' : (user.id !== msg.sender_id)}"
                                           :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                           class="font-weight-bold">{{msg.attachment_name}} <i
                                            class="fa fa-paperclip va-middle"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="msg.attachment_name && msg.msg === '' && msg.type === 1"
                             class="oChat__item mb-4"
                             :class="{'--sent' : (user.id === msg.sender_id), '--received' : (user.id !== msg.sender_id)}">
                            <div class="oChat__item__msg">
                                <div v-if="msg.msg === ''" class="oChat__item__avatar rounded-circle">
                                    <img src="/img/ign_avatar.jpg"
                                         v-if="user.id !== msg.sender_id"
                                         class="object-cover rounded-circle">
                                    <img :src="selfie = profile.selfie ? '/' + profile.selfie : '/img/cb-img/avatar.png'"
                                         v-else
                                         class="object-cover rounded-circle">
                                </div>
                                <div class="oChat__item__msgText">
                                    <a :class="{'attachment-white' : (user.id !== msg.sender_id)}"
                                       :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                       class="font-weight-bold">{{msg.attachment_name}} <i
                                        class="fa fa-paperclip va-middle"></i></a>
                                </div>
                            </div>
                        </div>
                        <div v-if="msg.type === 2" class="oChat__item mb-4 --received">
                            <div class="oChat__item__msg" style="width: 85%">
                                <div class="oChat__item__avatar rounded-circle">
                                    <img src="/img/ign_avatar.jpg"
                                         class="object-cover rounded-circle">
                                </div>
                                <div class="oChat__item__msgText">
                                    <div class="text-center">
                                        <div class="font-weight-bold">
                                            <span class="d-block mb-2">Cuenta Bancaria</span>
                                            {{order.sender_fiat_amount.toLocaleString('en')}}
                                            {{order.sender_fiat}}
                                        </div>
                                        <div>
                                            Cuenta {{msg.json_data.bank_name}}
                                            {{msg.json_data.account_number}}
                                        </div>
                                        <div class="clearfix mt-2"
                                             v-if="!msg.json_data.canceled && !msg.payed && !msg.failed">
                                            <div class="float-left text-orange">Tiempo limite</div>
                                            <div class="float-right text-orange">
                                                <div class="d-flex justify-content-center">
                                                    <div class="text-orange mx-2">
                                                        <small class="d-block">horas</small>
                                                        {{msg.hours}}
                                                    </div>
                                                    <div class="text-orange mx-2">
                                                        <small class="d-block">minutos</small>
                                                        {{msg.minutes}}
                                                    </div>
                                                    <div class="text-orange mx-2">
                                                        <small class="d-block">segundos</small>
                                                        {{msg.seconds}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix mt-2" v-if="msg.payed">
                                            <div class="text-center text-orange">Pagada</div>
                                        </div>
                                        <div class="clearfix mt-2" v-if="msg.failed">
                                            <div class="text-center text-orange">Fallida</div>
                                        </div>
                                        <div class="clearfix mt-2" v-if="msg.json_data.canceled">
                                            <div class="text-center text-orange">Cancelada</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="oChat__tools p-3">
                    <div class="oChat__tools__textarea--wrapper">
                        <textarea id="__text_area_mobile" rows="2" v-model="message" class="form-control lh-125 pr-5"
                                  placeholder="Su mensaje..."></textarea>
                        <button v-on:click="sendMessage"
                                class="oChat__tools__sendBtn btn btn-transparent btn-rounded my-auto">
                            <img src="/img/landing/send-secondary.svg" class="img-fluid">
                        </button>
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
    </div>
</template>
