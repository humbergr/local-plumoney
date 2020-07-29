<template lang="html">
    <div class="card-body px-md-4">
        <form class="" :action="getUrl('/new-transaction')" v-on:submit="storeData" method="post">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="recomended_price" v-model="recomended_price" value="">
            <div class="__home_order_head d-none d-md-flex">
                <h4 class="font-weight-bold">{{lang.send_money}}</h4>
                <a href="/send-money" class="btn btn-secondary btn-sm btn-pill">Ir ahora mismo</a>
            </div>
            <p class="font-weight-light lh-125 d-none d-md-block">
                {{lang.send_money_text}}
            </p>
            <h6 class="text-primary font-weight-bold mb-0 mb-md-2 d-md-none text-center mb-4">
                Envía dinero a donde quieras.
            </h6>
            <div class="card rounded mb-3">
                <div class="card-body py-2 text-left">
                    <div class="row">
                        <div class="col-lg-12">
                            <h6 class="text-primary font-weight-bold mb-0 mb-md-2">{{lang.to_send}}</h6>
                            <div class="input-group input-group-lg mb-3">
                                <p style="color: #303392; font-weight: 700; width: 52px; padding: 0 6px 0 0; margin: 0; display: flex; align-items: center; justify-content: flex-start;">
                                    {{sender}}
                                </p>
                                <input type="text"
                                       class="form-control form-control-country-big"
                                       style="background: url('/img/icons/loadExhanges-32px.gif') no-repeat center left;"
                                       v-if="loadingSend">
                                <input type="text" class="form-control form-control-country-big"
                                       id="amount-to-send"
                                       placeholder="0,000.00"
                                       name="to_send"
                                       v-else
                                       @focus="changeCalc('send')"
                                       v-model="formatted_to_send"
                                       required>
                                <div class="input-group-append select-appended">
                                    <select class="custom-select flag-selector border-left"
                                            id="__ho_select_sender"
                                            v-model="sender_country"
                                            name="sender" v-on:change="getPrice('fromSend', true)"
                                            style="background-repeat: no-repeat">
                                        <optgroup label="Latino América">
                                            <option value="Chile" data-flag="img/landing/flags/cl.svg">
                                                Chile
                                            </option>
                                            <option value="Venezuela" data-flag="img/landing/flags/ve.svg">
                                                Venezuela
                                            </option>
                                            <option value="Argentina" data-flag="img/landing/flags/ar.svg">
                                                Argentina
                                            </option>
                                            <option value="Colombia" data-flag="img/landing/flags/co.svg">
                                                Colombia
                                            </option>
                                            <option value="Perú" data-flag="img/landing/flags/pe.svg">
                                                Perú
                                            </option>
                                            <option value="México" data-flag="img/landing/flags/mx.svg">
                                                México
                                            </option>
                                            <option value="Brazil" data-flag="img/landing/flags/br.svg">
                                                Brazil
                                            </option>
                                            <!--<option value="Dominican Republic" data-flag="img/landing/flags/do.svg">
                                                República Dominicana
                                            </option>-->
                                            <!--  <option value="CRC" data-flag="img/landing/flags/cr.svg">
                                                  Costa Rica
                                              </option> -->
                                            <option value="Panamá" data-flag="img/landing/flags/pa.svg">
                                                Panama
                                            </option>
                                        </optgroup>
                                        <optgroup label="Norte America">
                                            <option value="United States" data-flag="img/landing/flags/us.svg"
                                                    selected>United States
                                            </option>
                                            <!--<option value="Canada" data-flag="img/landing/flags/ca.svg">
                                                Canada
                                            </option>-->
                                        </optgroup>
                                        <optgroup label="Europa">
                                            <option value="España" data-flag="img/landing/flags/es.svg">
                                                España
                                            </option>
                                            <option value="Portugal" data-flag="img/landing/flags/pt.svg">
                                                Portugal
                                            </option>
                                            <option value="Italia" data-flag="img/landing/flags/it.svg">
                                                Italia
                                            </option>
                                            <option value="Francia" data-flag="img/landing/flags/fr.svg">
                                                Francia
                                            </option>
                                            <option value="Alemania" data-flag="img/landing/flags/de.svg">
                                                Alemania
                                            </option>
                                            <option value="Reino Unido" data-flag="img/landing/flags/gb.svg">
                                                Reino Unido
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h6 class="text-primary font-weight-bold mb-0 mb-md-2">{{lang.to_receive}}</h6>
                            <div class="input-group input-group-lg">
                                <p style="color: #303392; font-weight: 700; width: 52px; padding: 0 6px 0 0; margin: 0; display: flex; align-items: center; justify-content: flex-start;">
                                    {{receiver}}
                                </p>
                                <input type="text"
                                       class="form-control form-control-country-big"
                                       style="background: url('/img/icons/loadExhanges-32px.gif') no-repeat center left;"
                                       v-if="loadingReceive">
                                <input type="text" class="form-control form-control-country-big"
                                       id="amount-to-receive"
                                       placeholder="0,000.00"
                                       name="to_receive"
                                       v-else
                                       @focus="changeCalc('receive')"
                                       v-model="formatted_to_receive"
                                       required>
                                <div class="input-group-append select-appended">
                                    <select class="custom-select flag-selector border-left"
                                            id="__ho_select_receiver"
                                            v-model="receiver_country"
                                            name="receiver" v-on:change="getPrice('fromReceive', true)">
                                        <optgroup label="Latino América">
                                            <option value="Chile" data-flag="img/landing/flags/cl.svg">
                                                Chile
                                            </option>
                                            <option value="Venezuela" data-flag="img/landing/flags/ve.svg">
                                                Venezuela
                                            </option>
                                            <option value="Argentina" data-flag="img/landing/flags/ar.svg">
                                                Argentina
                                            </option>
                                            <option value="Colombia" data-flag="img/landing/flags/co.svg">
                                                Colombia
                                            </option>
                                            <option value="Perú" data-flag="img/landing/flags/pe.svg">
                                                Perú
                                            </option>
                                            <option value="México" data-flag="img/landing/flags/mx.svg">
                                                México
                                            </option>
                                            <option value="Brazil" data-flag="img/landing/flags/br.svg">
                                                Brazil
                                            </option>
                                            <!--<option value="Dominican Republic" data-flag="img/landing/flags/do.svg">
                                                República Dominicana
                                            </option>-->
                                            <!--  <option value="CRC" data-flag="img/landing/flags/cr.svg">
                                                  Costa Rica
                                              </option> -->
                                            <option value="Panamá" data-flag="img/landing/flags/pa.svg">
                                                Panama
                                            </option>
                                        </optgroup>
                                        <optgroup label="Norte America">
                                            <option value="United States" data-flag="img/landing/flags/us.svg"
                                                    selected>United States
                                            </option>
                                            <!--<option value="Canada" data-flag="img/landing/flags/ca.svg">
                                                Canada
                                            </option>-->
                                        </optgroup>
                                        <optgroup label="Europa">
                                            <option value="España" data-flag="img/landing/flags/es.svg">
                                                España
                                            </option>
                                            <option value="Portugal" data-flag="img/landing/flags/pt.svg">
                                                Portugal
                                            </option>
                                            <option value="Italia" data-flag="img/landing/flags/it.svg">
                                                Italia
                                            </option>
                                            <option value="Francia" data-flag="img/landing/flags/fr.svg">
                                                Francia
                                            </option>
                                            <option value="Alemania" data-flag="img/landing/flags/de.svg">
                                                Alemania
                                            </option>
                                            <option value="Reino Unido" data-flag="img/landing/flags/gb.svg">
                                                Reino Unido
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12"
                             v-if="to_send !== '' && to_send !== 0 && recomended_price !== '' && loadingRate  === false">
                            <h6 class="text-primary text-center font-weight-bold mb-0 mb-md-2"
                                style="font-size: 14px"
                                v-if="recomended_price > 1">
                                {{lang.exchange_rate}}
                                1 {{sender}} = {{recommendedUtility(recomended_price)}} {{receiver}}
                            </h6>
                            <h6 class="text-primary text-center font-weight-bold mb-0 mb-md-2"
                                style="font-size: 14px"
                                v-else>
                                {{lang.exchange_rate}}
                                1 {{receiver}} = {{recommendedUtility(recomended_price)}} {{sender}}
                            </h6>
                        </div>
                        <div class="col-lg-12" v-if="loadingRate">
                            <h6 class="text-primary text-center font-weight-bold mb-0 mb-md-2"
                                style="font-size: 14px">
                                <img src="/img/icons/loadExhanges.gif" alt="Cargando tasa...">
                                Cargando Tasa...
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mb-3">
                <div class="custom-control custom-checkbox text-left" style="max-width: 360px">
                    <input type="checkbox" class="custom-control-input" id="certification-checkbox" required>
                    <label class="custom-control-label small" for="certification-checkbox" style="line-height: 1.25">
                        {{lang.agreement_1}} <a href="/agreement" target="_blank"
                                                class="small-orange text-light font-weight-bold">{{lang.agreement_2}}</a>
                        {{lang.agreement_3}} <a href="/privacy-policies" target="_blank"
                                                class="small-orange text-light font-weight-bold">
                        {{lang.agreement_4}}</a>
                    </label>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-secondary btn-lg btn-pill pl-md-3 pr-md-4 wow fadeIn">
                    <img src="img/landing/send-money-btn.svg" class="img-fluid mr-3">
                    {{lang.send_money}}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import * as numeral from "numeral";

    export default {
        props: ['lang'],

        data() {
            return {
                formattedToSendObject: '',
                formattedToReceiveObject: '',
                formatted_to_send: '',
                formatted_to_receive: '',
                to_send: '',
                to_receive: '',
                recomended_price: '',
                original_price: '',
                calcTo: 'send',//'receive'
                csrf: $('meta[name="csrf-token"]').attr("content"),
                currencies: {
                    'Venezuela': 'VES',
                    'Colombia': 'COP',
                    'Panamá': 'PAB',
                    'Perú': 'PEN',
                    'Chile': 'CLP',
                    'Argentina': 'ARS',
                    'Reino Unido': 'GBP',
                    'España': 'EUR',
                    'Portugal': 'EUR',
                    'Italia': 'EUR',
                    'Francia': 'EUR',
                    'Alemania': 'EUR',
                    'United States': 'USD',
                    "México": "MXN",
                    "Brazil": "BRL"
                    //"Costa Rica": "CRC",
                    //"Dominican Republic": "DOP",
                    //"Canada": "CAD",
                },
                sender_country: 'United States',
                receiver_country: 'Chile',
                getPriceInterval: null,
                loadingRate: false,
                loadingReceive: false,
                loadingSend: false
            }
        },
        computed: {
            sender: function () {
                return this.currencies[this.sender_country];
            },

            receiver: function () {
                return this.currencies[this.receiver_country];
            }
        },
        methods: {
            recommendedUtility() {
                if (this.recomended_price < 1) {
                    return numeral(1 / this.recomended_price).format('0,0.[00]');
                }

                return numeral(this.recomended_price).format('0,0.[00]');
            },
            getPrice(origin, lRates = false) {
                // this.to_send              = '';
                // this.formatted_to_send    = '';
                // this.to_receive           = '';
                // this.formatted_to_receive = '';

                if (this.to_send !== '' || this.to_receive !== '') {
                    if (lRates) {
                        if (origin === 'fromSend') {
                            this.loadingReceive = true;
                        } else {
                            this.loadingSend = true;
                        }
                    }
                    this.loadingRate = true;
                    axios.get(window.location.origin + '/get-price', {
                        params: {
                            amount: this.to_send === '' ? 0 : this.to_send,
                            receiving: this.to_receive === '' ? 0 : this.to_receive,
                            sender: this.sender,
                            receiver: this.receiver,
                            sender_country: this.sender_country,
                            receiver_country: this.receiver_country,
                        }
                    }).then(
                        re => {
                            this.loadingRate      = false;
                            this.loadingReceive   = false;
                            this.loadingSend      = false;
                            this.recomended_price = re.data[0];
                            this.original_price   = re.data['original'];

                            if (origin === 'fromSend') {
                                this.toReceive();
                            } else {
                                this.toSend();
                            }
                        }
                    )
                }
            },

            getUrl(endpoint) {
                return window.location.origin + endpoint;
            },

            storeData() {
                localStorage.setItem("to_send", this.to_send);
                localStorage.setItem("to_receive", this.to_receive);
                localStorage.setItem("sender_country", this.sender_country);
                localStorage.setItem("receiver_country", this.receiver_country);
                localStorage.setItem("recomended_price", this.recomended_price);
            },
            toReceive: function () {
                if (this.to_send === '') {
                    return '';
                }

                if (this.original_price > 1 && this.recomended_price < 1) {
                    this.to_receive = Math.round(this.to_send / this.recomended_price * 100) / 100;
                } else {
                    this.to_receive = Math.round(this.to_send * this.recomended_price * 100) / 100;
                }

                this.formatted_to_receive = numeral(this.to_receive).format('0,0.[00]');
                this.storeData();
            },
            toSend: function () {
                if (this.to_receive === '') {
                    return '';
                }
                this.to_send           = Math.round(this.to_receive / this.recomended_price * 100) / 100;
                this.formatted_to_send = numeral(this.to_send).format('0,0.[00]');
                this.storeData();
            },
            changeCalc(toCalc) {
                this.calcTo = toCalc;
            },
            testPrices() {
                let combinations = require('../tests/combination.json'),
                    dataAr       = [];
                $.when($.each(combinations, function (key, value) {
                    axios.get(window.location.origin + '/get-price', {
                        params: {
                            sender: value[0],
                            receiver: value[1],
                            sender_country: value[2],
                            receiver_country: value[3],
                        }
                    }).then(
                        re => {
                            dataAr.push(re.data[0]);
                        }
                    )
                })).then(function () {
                    localStorage.setItem('testPrices', JSON.stringify(dataAr));
                });
            },
            getCountriesData() {
                axios.get(window.location.origin + '/get-op-countries').then(
                    re => {
                        this.currencies = re.data[0];
                        $('#__ho_select_sender').html(re.data[1]);
                        $('#__ho_select_receiver').html(re.data[2]);
                    }
                )
            }
        },
        updated: function () {
            // this.$nextTick(function () {
            //     this.storeData();
            // });
        },
        watch: {
            formatted_to_send: function () {
                let vueObject = this;
                if (this.calcTo !== 'receive') {
                    this.to_send           = numeral(this.formatted_to_send).value();
                    this.formatted_to_send = numeral(this.formatted_to_send).format('0,0[.]00');

                    clearInterval(this.getPriceInterval);
                    this.getPriceInterval = setInterval(function () {
                        vueObject.getPrice('fromSend');
                        clearInterval(vueObject.getPriceInterval)
                    }, 1000);
                }
            },
            formatted_to_receive: function () {
                let vueObject = this;
                if (this.calcTo !== 'send') {
                    this.to_receive           = numeral(this.formatted_to_receive).value();
                    this.formatted_to_receive = numeral(this.formatted_to_receive).format('0,0[.]00');

                    clearInterval(this.getPriceInterval);
                    this.getPriceInterval = setInterval(function () {
                        vueObject.getPrice('fromReceive');
                        clearInterval(vueObject.getPriceInterval)
                    }, 1000);
                }
            }
        },
        mounted() {
            this.$nextTick(function () {
                // this.formattedToSendObject    = new AutoNumeric('#amount-to-send', {
                //     currencySymbol: '',
                //     decimalCharacter: '.',
                //     digitGroupSeparator: ',',
                // });
                // this.formattedToReceiveObject = new AutoNumeric('#amount-to-receive', {
                //     currencySymbol: '',
                //     decimalCharacter: '.',
                //     digitGroupSeparator: ',',
                // });
                // this.getPrice();
                this.getCountriesData();
                // this.testPrices();
            });
        }
    }
</script>

<style lang="scss">
    .__home_order_head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;

        h4 {
            margin: 0;
        }
    }
</style>
