<template lang="html">

    <div class="container">
        <vue-toastr ref="toastr"></vue-toastr>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group d-block mr-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="" v-model="transaction_id" class="form-control"
                                   placeholder="Search by ID" value="">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-secondary btn-pill px-md-3" v-on:click="searchByID"
                                    name="button">Search
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group d-block">
                                <label for="sender-select" class="mb-1">Sender</label>
                                <select name="" v-model="sender" id="sender-select"
                                        class="custom-select flag-selector flag-selector--full">
                                    <option value="All">All Currrencies</option>
                                    <optgroup label="Latino América">
                                        <option value="CLP" data-flag="img/landing/flags/cl.svg">
                                            Chile
                                        </option>
                                        <option value="VES" data-flag="img/landing/flags/ve.svg">
                                            Venezuela
                                        </option>
                                        <option value="ARS" data-flag="img/landing/flags/ar.svg">
                                            Argentina
                                        </option>
                                        <option value="COP" data-flag="img/landing/flags/co.svg">
                                            Colombia
                                        </option>
                                        <option value="PEN" data-flag="img/landing/flags/pe.svg">
                                            Perú
                                        </option>
                                        <option value="MXN" data-flag="img/landing/flags/mx.svg">
                                            Mexico
                                        </option>
                                        <option value="DOP" data-flag="img/landing/flags/do.svg">
                                            República Dominicana
                                        </option>
                                        <option value="CRC" data-flag="img/landing/flags/cr.svg">
                                            Costa Rica
                                        </option>
                                        <option value="PAB" data-flag="img/landing/flags/pa.svg">
                                            Panama
                                        </option>
                                    </optgroup>
                                    <optgroup label="Otros">
                                        <option value="USD" data-flag="img/landing/flags/us.svg"
                                                selected>United States
                                        </option>
                                        <option value="CAD" data-flag="img/landing/flags/ca.svg">
                                            Canada
                                        </option>
                                        <option value="EUR" data-flag="img/landing/flags/es.svg">
                                            España
                                        </option>
                                        <option value="EUR" data-flag="img/landing/flags/pt.svg">
                                            Portugal
                                        </option>
                                        <option value="EUR" data-flag="img/landing/flags/it.svg">
                                            Italia
                                        </option>
                                        <option value="EUR" data-flag="img/landing/flags/fr.svg">
                                            Francia
                                        </option>
                                        <option value="GBP" data-flag="img/landing/flags/gb.svg">
                                            Reino Unido
                                        </option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group d-block">
                                <label for="receiver-select" class="mb-1">Receiver</label>
                                <select name="" v-model="receiver" id="receiver-select"
                                        class="custom-select flag-selector flag-selector--full">
                                    <option value="All">All Currrencies</option>
                                    <optgroup label="Latino América">
                                        <option value="CLP" data-flag="img/landing/flags/cl.svg">
                                            Chile
                                        </option>
                                        <option value="VES" data-flag="img/landing/flags/ve.svg">
                                            Venezuela
                                        </option>
                                        <option value="ARS" data-flag="img/landing/flags/ar.svg">
                                            Argentina
                                        </option>
                                        <option value="COP" data-flag="img/landing/flags/co.svg">
                                            Colombia
                                        </option>
                                        <option value="PEN" data-flag="img/landing/flags/pe.svg">Perú</option>
                                    </optgroup>
                                    <optgroup label="Otros">
                                        <option value="USD" data-flag="img/landing/flags/us.svg">United States
                                        </option>
                                        <option value="EUR" data-flag="img/landing/flags/es.svg">
                                            España
                                        </option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group d-block">
                                <label for="transactions-select" class="mb-1">Status</label>
                                <select name="" v-model="transactions_status" id="transactions-select"
                                        class="custom-select">
                                    <option value="All">All Status</option>
                                    <option value="0">Open</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>
                                    <option value="3">Failed</option>
                                    <option value="4">In Process</option>
                                    <option value="5">Refund</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="bank_name" class="mb-1">Bank Name</label>
                            <input name="bank_name"
                                   id="bank_name"
                                   v-model="bank_name"
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="user_name" class="mb-1">User Name</label>
                            <input name="user_name"
                                   id="user_name"
                                   v-model="user_name"
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="creation-date-filter" class="mb-1">Date</label>
                            <div class="input-group">
                                <input type="text"
                                       id="creation-date-filter"
                                       class="form-control"
                                       aria-label="Creation date filter"
                                       aria-describedby="creation-date-filter">
                                <div class="input-group-append">
                                <span class="input-group-text bg-white text-muted">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="to_send" class="mb-1">Amount to send</label>
                            <input name="to_send"
                                   id="to_send"
                                   @keyup="formatToSend"
                                   @blur="formatToSend"
                                   placeholder="000,000.00"
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="to_receive" class="mb-1">Amount to receive</label>
                            <input name="to_receive"
                                   id="to_receive"
                                   @keyup="formatToReceive"
                                   @blur="formatToReceive"
                                   placeholder="000,000.00"
                                   class="form-control">
                        </div>
                        <div class="col-md-1 offset-8">
                            <button type="button" v-on:click="filterHander()"
                                    class="btn btn-secondary btn-pill px-md-3" style="margin-top:38%" name="button">
                                Filter
                            </button>
                        </div>
                        <div class="col-md-1">
                            <form :action="getEndpoint('/get-csv')" method="get">
                                <input type="hidden" v-model="receiver" name="receiver">
                                <input type="hidden" v-model="sender" name="sender">
                                <input type="hidden" v-model="transactions_status" name="transactions_status">
                                <input type="hidden" v-model="bank_name" name="bank_name">
                                <input type="hidden" v-model="user_name" name="user_name">
                                <input type="hidden" v-model="startDate" name="start_date">
                                <input type="hidden" v-model="endDate" name="end_date">
                                <input type="hidden" v-model="to_send" name="to_send">
                                <input type="hidden" v-model="to_receive" name="to_receive">
                                <button type="submit" class="btn btn-secondary btn-pill px-md-3"
                                        style="margin-top:38%"
                                        name="button">
                                    CSV
                                </button>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-secondary btn-pill px-md-3" style="margin-top:15%"
                                    v-on:click="orderByTrader"
                                    name="button">Order by Trader
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card loader--wrapper" :class="{ '--loading': loader }">
                    <table class="table table-striped table-borderless mb-0">
                        <thead>
                        <tr>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Trader</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Nombre</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Amount Sent</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Amount Received</h5>
                            </th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Bank</h5>
                            </th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Payment Method</h5>
                            </th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Status</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Date</h5></th>
                            <th class="text-center"><h5 class="text-muted font-weight-bold mb-0">Actions</h5></th>
                            <th class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="transaction in transactions.data"
                            :class="transaction.status === 0 || transaction.status === 4 ? transaction.payment_way : ''">
                            <th class="text-center" style="vertical-align:middle">
                                <a class="d-block"
                                   href="#!"
                                   v-if="!transaction.trader_id"
                                   v-on:click="assignExchange(transaction.id)">
                                    <img src="/img/icons/attended.svg" class="img-fluid __reclaim_image" alt="Atender"
                                         style="height: 38px">
                                    <br>
                                    <span style="font-size:10px;color:red">unassigned</span>
                                </a>
                                <p v-else style="color:green;font-size:15px">
                                    <a :href="getEndpoint('/trader-details/' + transaction.trader_id)">
                                        {{transaction.trader_info.name}}
                                    </a>
                                </p>
                            </th>
                            <th class="text-center" style="vertical-align:middle">
                                <a :href="getEndpoint('/user-profile/' + transaction.user_account.id)"
                                    target="_blank">
                                    {{transaction.user_account.name}}
                                </a>
                                <br>
                                <span v-if="transaction.user_account.score === null">
                                    <img src="/img/icons/aprobado.png"
                                         class="__score_icon"
                                         alt="Cliente Seguro"
                                         title="Cliente Seguro">
                                    <br>
                                    ({{transaction.user_account.score.totalOperations}}; {{transaction.user_account.score.successPercent}}%)
                                </span>
                                <span v-else>
                                    <span v-if="transaction.user_account.score.failurePercent < 20">
                                        <img src="/img/icons/aprobado.png"
                                             class="__score_icon"
                                             alt="Cliente Seguro"
                                             title="Cliente Seguro">
                                        <br>
                                        ({{transaction.user_account.score.totalOperations}}; {{transaction.user_account.score.successPercent}}%)
                                    </span>
                                    <span v-if="transaction.user_account.score.failurePercent >= 20 &&
                                    transaction.user_account.score.failurePercent < 50">
                                        <img src="/img/icons/menor-riesgo.png"
                                             class="__score_icon"
                                             alt="Cliente de Riesgo Menor"
                                             title="Cliente de Riesgo Menor">
                                        <br>
                                        ({{transaction.user_account.score.totalOperations}}; {{transaction.user_account.score.successPercent}}%)
                                    </span>
                                    <span v-if="transaction.user_account.score.failurePercent >= 50 &&
                                    transaction.user_account.score.failurePercent < 80">
                                        <img src="/img/icons/riesgo.png"
                                             class="__score_icon"
                                             alt="Cliente de Riesgo"
                                             title="Cliente de Riesgo">
                                        <br>
                                        ({{transaction.user_account.score.totalOperations}}; {{transaction.user_account.score.successPercent}}%)
                                    </span>
                                    <span v-if="transaction.user_account.score.failurePercent >= 80">
                                        <img src="/img/icons/alto-riesgo.png"
                                             class="__score_icon"
                                             alt="Alto Riesgo"
                                             title="Cliente de Alto Riesgo">
                                        <br>
                                        ({{transaction.user_account.score.totalOperations}}; {{transaction.user_account.score.successPercent}}%)
                                    </span>
                                </span>
                            </th>
                            <td class="text-center" style="vertical-align:middle">
                                {{transaction.sender_fiat}}
                                {{parseFloat(transaction.sender_fiat_amount).toLocaleString('en')}}
                            </td>
                            <td class="text-center" style="vertical-align:middle">
                                {{transaction.receiver_fiat}}
                                {{parseFloat(transaction.receiver_fiat_amount).toLocaleString('en')}}
                            </td>
                            <td class="text-center" v-if="transaction.destination_account !== null"
                                style="vertical-align:middle">
                                {{transaction.destination_account.bank_name}}
                                <br>
                                <img src="/img/icons/pago_movil.png"
                                     alt="Pago Movil"
                                     v-if="transaction.destination_account.pago_movil !== null">
                            </td>
                            <td class="text-center" v-if="transaction.destination_account === null && transaction.destination_account_json !== null"
                                style="vertical-align:middle">
                                {{transaction.destination_account_json.bank_name}}
                                <br>
                                <img src="/img/icons/pago_movil.png"
                                     alt="Pago Movil"
                                     v-if="transaction.destination_account_json.pago_movil !== null">
                            </td>
                            <td class="text-center"
                                v-if="transaction.destination_account === null && transaction.destination_account_json === null"
                                style="vertical-align:middle">
                                Borrada
                            </td>
                            <td style="vertical-align:middle" class="text-center __payment_data_place " :class="getStatusColor2(transaction.is_payed )">
                                {{getPaymentMethod(transaction.payment_way)}}  
                            </td>
                            <td class="text-center" style="vertical-align:middle"
                                :class="getStatusColor(transaction.status)" :id="'status-' + transaction.id">
                                {{getStatus(transaction.status)}}
                            </td>
                            <td class="text-center" style="vertical-align:middle">{{getDate(transaction.edtDate)}}</td>
                            <td v-if="(transaction.is_payed === 1 && transaction.payed_at) && transaction.status === 0"
                                class="text-center"
                                style="vertical-align:middle">
                                <select id="" :id="'select'+transaction.id" v-on:change="changeStatus(transaction.id)"
                                        class="custom-select">
                                    <option value="1">Approve</option>
                                    <option value="2">Reject</option>
                                    <option value="" selected disabled>Action</option>
                                </select>
                            </td>
                            <td v-if="transaction.status === 1" class="text-center" style="vertical-align:middle">
                                Completed
                            </td>
                            <td v-if="transaction.status === 2" class="text-center" style="vertical-align:middle">
                                Rejected
                            </td>
                            <td v-if="transaction.status === 3" class="text-center" style="vertical-align:middle">
                                Failed
                            </td>
                            <td v-if="transaction.status === 4" class="text-center" style="vertical-align:middle">
                                In Process
                            </td>
                            <td v-if="transaction.status === 5" class="text-center" style="vertical-align:middle">
                                Refunded
                            </td>
                            <td v-if="transaction.is_payed !== 1 && transaction.status === 0" class="text-center"
                                style="vertical-align:middle">
                                Not paid yet
                                <span v-if="transaction.payment_support !== null" class="badge badge-success">Soporte Cargado</span>
                            </td>
                            <td class="text-center" style="vertical-align:middle">
                                <a v-if="transaction.trader_id === user_id || user_role === 1 || user_role === 2 || user_role === 8 || user_role === 3 || user_role === 6 || user_role === 7"
                                   :href="getUrl(transaction.id)"
                                   target="_blank">
                                    View Transaction
                                </a>
                                <br>
                                <span style="font-size: 14px">{{transaction.tracking_id}}</span>
                            </td>
                        </tr>
                        <tr v-if="(sender !== 'All' && total_to_send > 0) || (receiver !== 'All' && total_to_receive > 0)">
                            <th></th>
                            <th></th>
                            <td v-if="sender !== 'All'" class="text-center"
                                style="background: #90c8ff; color: black; border: 1px solid black">{{sender}}
                                {{total_to_send.toLocaleString('en')}}
                            </td>
                            <td v-else></td>
                            <td v-if="receiver !== 'All'" class="text-center"
                                style="background: #90c8ff; color: black; border: 1px solid black">{{receiver}}
                                {{total_to_receive.toLocaleString('en')}}
                            </td>
                            <td v-else></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="loader">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div v-if="transactions.current_page" class="d-flex justify-content-center mt-3">
                    <a v-if="transactions.current_page != 1" class="page-link px-3" href="javascript:void(0);"
                       aria-label="Load more"
                       style="width: unset; height: unset; border-radius: 10rem !important;"
                       v-on:click="transactionsPagination(transactions.current_page - 1)">
                        <span aria-hidden="true">Prev</span>
                    </a>
                    <a v-if="transactions.current_page != transactions.last_page" class="page-link px-3"
                       href="javascript:void(0);" aria-label="Load more"
                       style="width: unset; height: unset; border-radius: 10rem !important;"
                       v-on:click="transactionsPagination(transactions.current_page + 1)">
                        <span aria-hidden="true">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['transactions_init_value', 'user_id', 'user_role'],

        data() {
            return {
                transactions: this.transactions_init_value,
                loader: false,
                transaction_id: '',
                receiver: 'All',
                sender: 'All',
                transactions_status: 0,
                order_by_trader: 0,
                bank_name: null,
                user_name: null,
                startDate: null,
                endDate: null,
                to_send: null,
                to_receive: null,
                total_to_send: 0,
                total_to_receive: 0,
            }
        },

        methods: {
            getStatus(id) {
                if (id == 1) {
                    return 'Approved';
                } else if (id == 2) {
                    return 'Rejected';
                } else if (id == 3) {
                    return 'Failed';
                } else if (id == 4) {
                    return 'In Process';
                } else if (id == 5) {
                    return 'Refund';
                } else {
                    return 'Open';
                }
            },

            gettingTotals() {
                this.total_to_receive = 0;
                this.total_to_send    = 0;
                let that              = this;

                this.transactions.data.map(function (index) {
                    that.total_to_receive += parseFloat(index['receiver_fiat_amount']);
                    that.total_to_send += parseFloat(index['sender_fiat_amount']);
                });
            },

            orderByTrader() {
                this.order_by_trader     = 1;
                this.sender              = 'All';
                this.receiver            = 'All';
                this.transactions_status = 0;
                this.loader              = true;
                this.user_name           = null;
                this.bank_name           = null;
                this.transactionsPagination(1);
            },

            filterHander() {
                this.order_by_trader = 0;
                this.transactionsPagination(1);
            },

            searchByID() {
                this.loader = true;
                axios.get(window.location.origin + '/search-transaction', {
                    params: {
                        trans_id: this.transaction_id,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    }
                }).then(re => {
                    if (re.data.error) {
                        this.loader = false;
                        this.$refs.toastr.e('Transaction not found.');
                        return;
                    }

                    this.loader       = false;
                    this.transactions = re.data;
                })
            },

            assignExchange(id) {
                this.loader = true;
                axios.post(window.location.origin + '/assign-exchange/' + id
                ).then(re => {
                    if (re.data.status === 'error') {
                        this.$refs.toastr.e(re.data.msg);
                    } else {
                        this.$refs.toastr.s(re.data.msg);
                    }
                    this.transactionsPagination(transactions.current_page);
                    this.loader = false;
                })
            },

            getPaymentMethod(meth) {
                let paymentMethods = {
                    'cash_deposit': 'Pago en Efectivo',
                    'Stripe': 'Tarjeta de Crédito o Débito',
                    'QuickBook': 'Tarjeta de Crédito o Débito QB',
                    'Pago123': 'Tarjeta de Crédito o Débito',
                    'zelle': 'Zelle',
                    'venmo': 'Venmo',
                    'cashapp': 'Cash App',
                    'payoneer': 'Payoneer',
                    'popmoney': 'PopMoney',
                    'pandco': 'Pandco',
                    'userWallet': 'Billetera AKB'
                };

                return paymentMethods[meth];
            },

            getDate(date) {
                let newDate = new Date(date);

                return (newDate.getMonth() + 1)
                    + '/' + newDate.getDate()
                    + '/' + newDate.getFullYear()
                    + ' - ' + (newDate.getHours() < 10 ? '0' : '') + newDate.getHours()
                    + ':' + (newDate.getMinutes() < 10 ? '0' : '') + newDate.getMinutes();
            },

            getStatusColor(id) {
                if (id == 1) {
                    return 'text-success';
                } else if (id == 2) {
                    return 'text-danger';
                } else if (id == 3) {
                    return 'text-warning';
                } else if (id == 4) {
                    return 'text-info';
                } else {
                    return 'text-info';
                }
            },

             getStatusColor2(id) {
                if (id == 1) {
                    return 'bg-success text-white';
                }  
            },

            formatToSend(event) {
                let vueObject    = this,
                    intentAmount = 0;

                if (event.type === 'keyup') {
                    vueObject.formatCurrency($(event.target));
                    vueObject.to_send = this.parseValue($(event.target).val());
                } else {
                    vueObject.formatCurrency($(event.target), 'blur');
                    vueObject.to_send = this.parseValue($(event.target).val());
                }
            },

            formatToReceive(event) {
                let vueObject    = this,
                    intentAmount = 0;

                if (event.type === 'keyup') {
                    vueObject.formatCurrency($(event.target));
                    vueObject.to_receive = this.parseValue($(event.target).val());
                } else {
                    vueObject.formatCurrency($(event.target), 'blur');
                    vueObject.to_receive = this.parseValue($(event.target).val());
                }
            },

            formatNumber(n) {
                // format number 1000000 to 1,234,567
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            parseValue(value) {
                let floatValue = parseFloat(value.replace(/,/g, ''));

                if (!isNaN(floatValue)) {
                    return floatValue;
                }

                return 0;
            },

            formatCurrency(input, blur) {
                let input_val = input.val();

                if (input_val === "") {
                    return;
                }

                let original_len = input_val.length,
                    caret_pos    = input.prop("selectionStart");

                if (input_val.indexOf(".") >= 0) {

                    let decimal_pos = input_val.indexOf("."),
                        left_side   = input_val.substring(0, decimal_pos),
                        right_side  = input_val.substring(decimal_pos);

                    left_side  = this.formatNumber(left_side);
                    right_side = this.formatNumber(right_side);

                    if (blur === "blur") {
                        right_side += "00";
                    }

                    right_side = right_side.substring(0, 2);
                    input_val  = left_side + "." + right_side;

                } else {
                    input_val = this.formatNumber(input_val);
                    if (blur === "blur") {
                        input_val += ".00";
                    }
                }
                input.val(input_val);

                let updated_len = input_val.length;

                caret_pos = updated_len - original_len + caret_pos;
                input[0].setSelectionRange(caret_pos, caret_pos);
            },

            changeStatus(id) {
                let sel     = $('#select' + id).val();
                this.loader = true;
                axios.post(window.location.origin + '/change-status/' + id, {
                    params: {
                        status: sel,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    }
                }).then(re => {
                    this.transactionsPagination(this.transactions.current_page);
                })
            },

            transactionsPagination(page) {
                this.loader = true;
                axios.get(window.location.origin + '/exchange-pagination', {
                    params: {
                        page: page,
                        receiver: this.receiver,
                        sender: this.sender,
                        transactions_status: this.transactions_status,
                        order_by_trader: this.order_by_trader,
                        bank_name: this.bank_name,
                        user_name: this.user_name,
                        start_date: this.startDate,
                        end_date: this.endDate,
                        to_send: this.to_send,
                        to_receive: this.to_receive,
                    }
                }).then(re => {
                    this.transactions = re.data;
                    this.loader       = false;
                    this.gettingTotals();
                })
            },

            getUrl(id) {
                return window.location.origin + '/exchange-transaction/' + id;
            },

            getEndpoint(endpoint) {
                return window.location.origin + endpoint;
            },

            subscribe() {
                let pusher = new Pusher('889fce6a69a9c7050bd3', {cluster: 'us2'});
                pusher.subscribe('my-channel');
                pusher.bind('transaction-order', data => {
                    if (this.transactions.current_page == 1) {
                        this.transactionsPagination(1);
                    }
                });
            },

            changeFilterDate(start, end) {
                this.startDate = start.format('YYYY-MM-D HH:mm:ss');
                this.endDate   = end.format('YYYY-MM-D HH:mm:ss');
            },
        },

        created() {
            this.subscribe();
        },

        mounted: function () {
            console.log(this.user_id, this.user_role);
            this.$nextTick(function () {
                // let vueObject = this;
                //
                // $.each(this.walletsObject, function (index, value) {
                //     if (value.currency === 'USD') {
                //         vueObject.activeWallet = value;
                //     }
                // });
                let vueObject  = this,
                    startDate  = moment().startOf('month'),
                    endDate    = moment().utc();
                this.startDate = startDate.format('YYYY-MM-D HH:mm:ss');
                this.endDate   = endDate.format('YYYY-MM-D HH:mm:ss');

                function cb(start, end) {
                    if (start !== vueObject.startDate || end !== vueObject.endDate) {
                        vueObject.changeFilterDate(start, end);
                    }
                }

                $('#creation-date-filter').daterangepicker({
                    opens: 'center',
                    startDate: startDate,
                    endDate: endDate
                }, cb);

                cb(startDate, endDate);

                //this.filterTransactions();
            });
        }
    }
</script>

<style lang="scss">
    tr {
        &.cash_deposit {
            border: 1px solid #d35f5f;
            border-left: 3px solid #d35f5f;

            .__payment_data_place {
                background: #d35f5f;
                color: #fff;
            }
        }

        &.zelle,
        &.venmo,
        &.cashapp,
        &.payoneer,
        &.popmoney,
        &.pandco {
            border: 1px solid #ffdd55;
            border-left: 3px solid #ffdd55;

            .__payment_data_place {
                background: #ffdd55;
                color: #333;
            }
        }

        &.Stripe,
        &.QuickBook,
        &.userWallet {
            border: 1px solid #8dd35f;
            border-left: 3px solid #8dd35f;

            .__payment_data_place {
                background: #8dd35f;
                color: #333;
            }
        }

    }

    .__score_icon {
        max-height: 20px;
    }
</style>
