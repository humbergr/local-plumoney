<script src="../AdminOrderTransactionChatComponent.js"></script>

<template lang="html">
    <div class="chat__tab--wrapper"
         @click="clearVisualHelpers"
         :id="'chat-' + order.tracking_id + '-window'">
        <div class="chat__tab mx-2" :class="{'collapsed' : collapsed, '--newMsg' : incoming_msg}">
            <!-- chat header -->
            <a href="javascript:void(0);" v-if="attended === 0" class="__reclaim_link" v-on:click="reclaim">
                <img src="/img/icons/attended.svg" class="img-fluid __reclaim_image" alt="Atender">
            </a>
            <header class="chat__header" :class="{'--newMsg' : incoming_msg}">
                <div class="__alert_extra" v-if="order.forced_transfer">
                    <span>
                        Transferred operation
                    </span>
                </div>
                <div class="flex-grow-1"
                     @click="collapsedFunc">
                    <a href="#!" class="chat__headerAvatar mr-1" :title="merchant.name">
                        <img :src="'/' + order.merchant.person_profile.selfie"
                             :alt="order.merchant.name"
                             class="object-cover rounded-circle"/>
                    </a>
                    <a v-if="user.role_id === 5" href="#!" class="chat__headerName text-truncate">
                        American Kryptos Bank Team
                    </a>
                    <a v-else href="#!" class="chat__headerName text-truncate">{{merchant.name}}</a>
                    <small style="display:block; font-size: 10px;"
                           data-toggle="tooltip"
                           :title="order.operator.email"
                           v-if="order.operator">
                        ^- Attended by: {{order.operator.name}}
                    </small>
                    <small style="font-size: 9px" v-else>Unattended</small>
                </div>
                <button type="button"
                        class="close"
                        title="Cerrar pestaña"
                        @click="hideChatWindow">
                    <span aria-hidden="true">&times;</span>
                </button>
            </header>

            <!-- chat body content -->
            <div class="chat__body loader--wrapper" :class="{ '--loading': loader }">
                <!-- chat messages -->
                <div id="chat-body" class="chat__msgs py-3 px-2" ref="scroller">

                    <div class="chat__item mr-2">
                        <div class="media">
                            <div class="media-body">
                                <div class="chat__item__message --received">{{merchant.name}} wants to send
                                    {{order.sender_fiat_amount.toLocaleString('en')}} {{order.sender_fiat}} in order to
                                    receive
                                    {{order.receiver_fiat_amount.toLocaleString('en')}} {{order.receiver_fiat}}.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-for="msg in messages" class="chat__item mr-2">
                        <div v-if="msg.user_account.role_id !== 5 && msg.msg !== '' "
                             class="media">
                            <div class="media-body">
                                <div class="chat__item__message --sent">
                                    {{msg.msg}}
                                    <div v-if="msg.attachment_name" class="chat__item__message --file --received"><a
                                            :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                            class="font-weight-bold">{{msg.attachment_name}} <i
                                            class="fa fa-paperclip va-middle"></i></a></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="msg.user_account.role_id === 5 && msg.msg !== ''"
                             class="media">
                            <a href="#!" class="chat__item__avatar mt-auto mr-2" title="Merchant">
                                <img src="/img/cb-img/avatar.png" alt="Profile avatar"
                                     class="object-cover rounded-circle">
                            </a>
                            <div class="media-body">
                                <div class="chat__item__message --received">
                                    {{msg.msg}}
                                    <div v-if="msg.attachment_name" class="chat__item__message --file --received"><a
                                            :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                            class="font-weight-bold">{{msg.attachment_name}} <i
                                            class="fa fa-paperclip va-middle"></i></a></div>
                                </div>
                            </div>
                        </div>
                        <div v-if="msg.user_account.role_id !== 5 && msg.msg === '' && msg.attachment_name"
                             class="media">
                            <div class="media-body">
                                <div class="chat__item__message --file --sent"><a
                                        :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                        class="font-weight-bold">{{msg.attachment_name}} <i
                                        class="fa fa-paperclip va-middle"></i></a></div>
                            </div>
                        </div>
                        <div v-if="msg.user_account.role_id === 5 && msg.msg === '' && msg.attachment_name"
                             class="media">
                            <a href="#!" class="chat__item__avatar mt-auto mr-2" title="Merchant">
                                <img src="/img/cb-img/avatar.png" alt="Profile avatar"
                                     class="object-cover rounded-circle">
                            </a>
                            <div class="media-body">
                                <div class="chat__item__message --file --received"><a
                                        :href="getAttachmentUrl(msg.attachment_name)" target="_blank"
                                        class="font-weight-bold">{{msg.attachment_name}} <i
                                        class="fa fa-paperclip va-middle"></i></a></div>
                            </div>
                        </div>
                        <div v-if="msg.type === 2" class="__bank_acount media">
                            <div class="media-body">
                                <div class="chat__item__message --sent">
                                    <strong>Has envíado la siguiente cuenta a depostiar:</strong>
                                    <br>
                                    <ul>
                                        <li>Nombre del banco: {{msg.json_data.bank_name}}</li>
                                        <li>Número de cuenta: {{msg.json_data.account_number}}</li>
                                        <li>Monto: {{msg.json_data.fiat_amount}}</li>
                                        <li>Nombre del titular: {{msg.json_data.account_owner}}</li>
                                        <li>Documento del titular: {{msg.json_data.id_number}}</li>
                                        <li v-if="!msg.json_data.canceled && !msg.payed && !msg.failed">
                                            Tiempo límite:
                                            {{msg.hours}}:
                                            {{msg.minutes}}:
                                            {{msg.seconds}}
                                        </li>
                                    </ul>
                                    <p v-if="msg.payed" class="text-center">
                                        Pagada
                                    </p>
                                    <p v-if="msg.failed && !msg.payed" class="text-center">
                                        Fallida
                                    </p>
                                    <p v-if="msg.json_data.canceled" class="text-center">
                                        Cancelada
                                    </p>
                                    <a href="javascript:void(0);"
                                       v-if="!msg.json_data.canceled && user.role_id === 1"
                                       @click="cancelBankAccount(msg.json_data.bank_account_db_id)"
                                       class="btn btn-danger btn-pill d-block">
                                        Cancelar cuenta
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="send-bank-account"
                     class="__bank_form chat__slideUp px-3 pt-2 pb-2 border-top border-top border-bottom"
                     :class="{'--show' : send_bank}">
                    <form action="">
                        <h6 class="text-primary font-weight-bold text-center small mb-3">Enviar Cuenta Bancaria</h6>
                        <div class="form-group mb-2">
                            <input type="text" v-model="bank_name" class="form-control form-control-sm"
                                   placeholder="Banco (requerido)">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" v-model="account_type" class="form-control form-control-sm"
                                   placeholder="Tipo de cuenta (requerido)">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" v-model="account_number" class="form-control form-control-sm"
                                   placeholder="Número de cuenta (requerido)">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" v-model="fiat_amount" class="form-control form-control-sm"
                                   placeholder="Monto a depositar (requerido)">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" v-model="account_owner" class="form-control form-control-sm"
                                   placeholder="Titular (opcional)">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" v-model="id_number" class="form-control form-control-sm"
                                   placeholder="Identificación (opcional)">
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" v-model="available_minutes" class="form-control form-control-sm"
                                   placeholder="Minutos para depositar (requerido)">
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" v-on:click="showBankForm"
                                    class="btn btn-light btn-sm dismiss-slideUp btn-pill px-2">Cancelar
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm btn-pill px-4"
                                    v-on:click="sendBankAccount">Enviar
                            </button>
                        </div>
                    </form>
                </div>

                <div id="transfer-chat-modal"
                     class="__transfer_chat_form chat__slideUp px-3 pt-2 pb-2 border-top border-top border-bottom"
                     :class="{'--show' : transfer_chat}">
                    <form action="" v-if="order.operator">
                        <h6 class="text-primary font-weight-bold text-center small mb-3">Pick an operator</h6>
                        <div class="form-group mb-2">
                            <select class="form-control form-control-sm"
                                    id="__transfer_chat_select">
                                <option>-- Pick one --</option>
                                <option v-for="operator in activeUsers"
                                        v-if="operator.role_id === 4 && operator.is_idle !== 1 && operator.id !== order.attended_by"
                                        :value="operator.id">
                                    {{operator.name}}
                                </option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" v-on:click="showTransferChatForm"
                                    class="btn btn-light btn-sm dismiss-slideUp btn-pill px-2">Cancelar
                            </button>
                            <button type="button" v-on:click="getActiveUsers"
                                    class="btn btn-secondary btn-sm btn-pill px-4">
                                Refresh
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm btn-pill px-4"
                                    v-on:click="requestTransferChat">
                                Send
                            </button>
                        </div>
                    </form>
                </div>

                <!-- chat tools -->
                <div class="chat__tools border-top">
                    <!-- <textarea name="name" rows="8" cols="80"></textarea> -->
                    <textarea @keypress.enter="sendMessage" v-model="message" @focus="textFocus"
                              @keyup="textareaSize"
                              class="form-control chat__input mb-1" rows="1" placeholder="Escribir un mensaje..."
                              style="max-height: none; height: 35px"
                              v-if="user.role_id === 1"></textarea>
                    <ul class="list-inline px-2 py-1 mb-0">
                        <li class="list-inline-item" data-toggle="tooltip" data-placement="top"
                            title="Agregar cuenta bancaria" data-delay='{"show": 500, "hide": 50}' data-trigger="hover"
                            v-if="user.role_id === 1">
                            <button id="btnBankAcc-modal" v-on:click="showBankForm" type="button"
                                    class="btn-transparent">
                                <i class="fa fa-bank"></i>
                            </button>
                        </li>
                        <li class="list-inline-item" data-toggle="tooltip" data-placement="top" title="Enviar archivo"
                            data-delay='{"show": 500, "hide": 50}' data-trigger="hover"
                            v-if="user.role_id === 1">
                            <label :for="'file-window-'+order.id" class="mb-0">
                                <!-- <button type="button" class="btn-transparent"> -->
                                <div class="btn-transparent px-1">
                                    <i class="fa fa-paperclip"></i>
                                </div>
                                <!-- </button> -->
                            </label>
                            <input type="file" name="file-window" v-on:change="fileHandler" ref="file"
                                   :id="'file-window-'+order.id" hidden>
                        </li>
                        <li class="list-inline-item" v-if="file !== ''">
                            <p style="font-size:12px">{{file.name}}</p>
                        </li>
                        <li class="list-inline-item" v-if="order.operator"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="Transfer chat"
                            data-delay='{"show": 500, "hide": 50}'
                            data-trigger="hover">
                            <button id="btnTransferChat-modal" v-on:click="showTransferChatForm" type="button"
                                    class="btn-transparent">
                                <i class="fa fa-exchange" aria-hidden="true"></i>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="loader">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

            </div>
        </div>

        <vue-toastr ref="toastr"></vue-toastr>
    </div>

</template>

<style lang="scss">
    .chat__header {
        a {
            pointer-events: none;
        }

        position: relative;
    }

    .__alert_extra {
        position: absolute;
        font-size: 13px;
        line-height: 18px;
        top: -18px;
        right: 0;
        padding: 0 10px;
        background: #fff;
        border-radius: 5px 5px 5px 0;
        color: #e73f25;
    }

    .__bank_form {
        position: absolute;
        width: 350px;
        left: -150px;
        background-color: #fff;
        border: 0;
        border-radius: 10px;
        box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.30);
        bottom: 30px;
        top: -40px;

        &:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 0;
            border: 15px solid transparent;
            border-top-color: #fff;
            border-bottom: 0;
            margin-left: -20px;
            margin-bottom: -20px;
        }
    }

    .__reclaim_link {
        position: absolute;
        right: 0;
        left: auto;
        padding: 4px;
        background: #fff;
        border-radius: 4px;
        top: -36px;
        width: 36px;
        height: 36px;
        display: block;
        border: 2px solid #e73f25;
    }

    .__reclaim_image {
        width: 28px;
        height: 28px;
    }

    .chat__headerName {
        font-size: 13px;
    }

    .collapsed {
        .__reclaim_image,
        .__reclaim_link {
            display: none;
        }
    }

    .__master_name {
        font-size: 13px;
        color: #e73f25;
    }

    .chat__tab {
    }

    .chat__tab--wrapper {
        &.__recent_open {
            header {
                animation-name: new_open;
                animation-duration: 2s;
            }
        }
    }

    @keyframes new_open {
        from {
            background-color: #1DBA44;
        }
        to {
            background-color: #fff;
        }
    }
</style>
