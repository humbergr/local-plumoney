<template lang="html">
    <div class="card-body px-md-4">
        <form class="" :action="getUrl('/new-transaction')" v-on:submit="storeData" method="post">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="recomended_price" v-model="recomended_price" value="">
            <h4 class="font-weight-bold">Te acercamos a los tuyos</h4>
            <p class="font-weight-light lh-125">Envía dinero a cualquier lugar del mundo con la tasa justa y desde la
                comodidad de tu silla.</p>
            <div class="card rounded mb-3">
                <div class="card-body py-2 text-left">
                    <div class="row">
                        <div class="col-lg-12">
                            <h6 class="text-primary font-weight-bold mb-0 mb-md-2">Monto a enviar</h6>
                            <div class="input-group input-group-lg mb-3">
                                <input type="text" class="form-control form-control-country-big"
                                       id="amount-to-send"
                                       placeholder="Ingrese monto a enviar"
                                       name="to_send"
                                       v-model="formatted_to_send"
                                       @keyup="formattedToSend"
                                       required>
                                <div class="input-group-append select-appended">
                                    <select class="custom-select flag-selector border-left" v-model="sender"
                                            name="sender" v-on:change="getPrice" style="background-repeat: no-repeat">
                                        <optgroup label="Latino América">
                                            <option value="VES" data-flag="img/landing/flags/ve.svg"
                                                    selected>Venezuela
                                            </option>
                                            <!--    <option value="ARS" data-flag="img/landing/flags/ar.svg">Argentina</option>
                                                <option value="BO" data-flag="img/landing/flags/bo.svg">Bolivia</option>
                                                <option value="BR" data-flag="img/landing/flags/br.svg">Brasil</option>
                                                <option value="CLP" data-flag="img/landing/flags/cl.svg">Chile</option> -->
                                            <option value="COP" data-flag="img/landing/flags/co.svg">
                                                Colombia
                                            </option>
                                            <option value="PEN" data-flag="img/landing/flags/pe.svg">Perú
                                            </option>
                                            <!--    <option value="PY" data-flag="img/landing/flags/py.svg">Paraguay</option>
                                                <option value="UY" data-flag="img/landing/flags/uy.svg">Uruguay</option> -->
                                        </optgroup>
                                        <optgroup label="Others">
                                            <option value="USD" data-flag="img/landing/flags/us.svg">United
                                                States
                                            </option>
                                            <!--    <option value="CA" data-flag="img/landing/flags/ca.svg">Canada</option> -->
                                            <option value="EUR" data-flag="img/landing/flags/es.svg">
                                                España
                                            </option>
                                            <!--    <option value="PT" data-flag="img/landing/flags/pt.svg">Portugal</option> -->
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h6 class="text-primary font-weight-bold mb-0 mb-md-2">Entregamos</h6>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control form-control-country-big"
                                       id="amount-to-receive"
                                       placeholder="Ingrese monto a enviar"
                                       name="to_receive"
                                       v-model="formatted_to_receive"
                                       @keyup="formattedToReceive"
                                       required>
                                <div class="input-group-append select-appended">
                                    <select class="custom-select flag-selector border-left" v-model="receiver"
                                            name="receiver" v-on:change="getPrice">
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
                                            <!--  <option value="BO" data-flag="img/landing/flags/bo.svg">Bolivia</option>
                                              <option value="BR" data-flag="img/landing/flags/br.svg">Brasil</option> -->
                                            <option value="COP" data-flag="img/landing/flags/co.svg">
                                                Colombia
                                            </option>
                                            <!--  <option value="PE" data-flag="img/landing/flags/pe.svg">Perú</option>
                                              <option value="PY" data-flag="img/landing/flags/py.svg">Paraguay</option>
                                              <option value="UY" data-flag="img/landing/flags/uy.svg">Uruguay</option> -->
                                        </optgroup>
                                        <optgroup label="Otros">
                                            <option value="USD" data-flag="img/landing/flags/us.svg"
                                                    selected>United States
                                            </option>
                                            <!--  <option value="CA" data-flag="img/landing/flags/ca.svg">Canada</option> -->
                                            <option value="EUR" data-flag="img/landing/flags/es.svg">
                                                España
                                            </option>
                                            <!--  <option value="PT" data-flag="img/landing/flags/pt.svg">Portugal</option> -->
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h6 class="text-primary text-center font-weight-bold mb-0 mb-md-2">Tasa de cambio:
                                1 {{sender}} = {{recommendedUtility(recomended_price)}} {{receiver}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mb-3">
                <div class="custom-control custom-checkbox text-left" style="max-width: 360px">
                    <input type="checkbox" class="custom-control-input" id="certification-checkbox">
                    <label class="custom-control-label small" for="certification-checkbox" style="line-height: 1.25">Certifico
                        que soy mayor de edad y que el dinero de esta Transacción cumple con las <a href=""
                                                                                                    class="text-light font-weight-bold">normas
                            legales vigentes.</a></label>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-secondary btn-lg btn-pill pl-md-3 pr-md-4"><img
                        src="img/landing/send-money-btn.svg" class="img-fluid mr-3">Enviar dinero
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import * as AutoNumeric from "autonumeric";

    export default {
        data() {
            return {
                formattedToSendObject: '',
                formattedToReceiveObject: '',
                formatted_to_send: '',
                formatted_to_receive: '',
                to_send: '',
                to_receive: '',
                sender: 'USD',
                receiver: 'CLP',
                recomended_price: '',
                csrf: $('meta[name="csrf-token"]').attr("content"),
            }
        },

        computed: {},

        methods: {
            recommendedUtility(price) {
                if (this.receiver === 'VES') {
                    if (this.recomended_price < 1) {
                        return (Math.round((this.recomended_price + (this.recomended_price * (-0) / 100)) * 100) / 100)
                            .toLocaleString('en');
                    } else {
                        return (Math.round((this.recomended_price - (this.recomended_price * (-0) / 100)) * 100) / 100)
                            .toLocaleString('en');
                    }
                } else {
                    return (Math.round((this.recomended_price - (this.recomended_price * 4 / 100)) * 100) / 100)
                        .toLocaleString('en');
                }
            },
            getPrice() {
                this.to_send              = '';
                this.formatted_to_send    = '';
                this.to_receive           = '';
                this.formatted_to_receive = '';
                this.formattedToReceiveObject.set('');
                this.formattedToSendObject.set('');
                axios.get(window.location.origin + '/get-price', {
                    params: {
                        sender: this.sender,
                        receiver: this.receiver
                    }
                })
                    .then(
                        re => {
                            this.recomended_price = re.data;
                            this.storeData();
                        }
                    )
            },

            getUrl(endpoint) {
                return window.location.origin + endpoint;
            },

            storeData() {
                localStorage.setItem("to_send", this.to_send);
                localStorage.setItem("to_receive", this.to_receive);
                localStorage.setItem("sender", this.sender);
                localStorage.setItem("receiver", this.receiver);
                localStorage.setItem("recomended_price", this.recomended_price);
            },

            formattedToSend: function () {
                if (this.formattedToSendObject) {
                    this.to_send           = this.formattedToSendObject.getNumericString();
                    this.formatted_to_send = this.formattedToSendObject.getFormatted();

                    this.toReceive();
                }
            },
            formattedToReceive: function () {
                if (this.formattedToReceiveObject) {
                    this.to_receive           = this.formattedToReceiveObject.getNumericString();
                    this.formatted_to_receive = this.formattedToReceiveObject.getFormatted();

                    this.toSend();
                }
            },
            toReceive: function () {
                if (this.to_send === '') {
                    return '';
                }
                //TODO - El 2% debe poder definirse desde la administración, luego.
                // if (this.recomended_price < 1) {
                //     let tcUtility = this.recomended_price + (this.recomended_price * (-4) / 100);
                //     return (Math.round(this.to_send / tcUtility * 100) / 100)
                //         .toLocaleString('en');
                // } else {
                //     let tcUtility = this.recomended_price - (this.recomended_price * (-4) / 100);
                //     return (Math.round(this.to_send * tcUtility * 100) / 100)
                //         .toLocaleString('en');
                // }

                if (this.receiver === 'VES') {
                    if (this.recomended_price < 1) {
                        let tcUtility   = this.recomended_price + (this.recomended_price * (-0) / 100);
                        this.to_receive = Math.round(this.to_send / tcUtility * 100) / 100;
                        this.formattedToReceiveObject.set(this.to_receive);
                    } else {
                        let tcUtility   = this.recomended_price - (this.recomended_price * (-0) / 100);
                        this.to_receive = Math.round(this.to_send * tcUtility * 100) / 100;
                        this.formattedToReceiveObject.set(this.to_receive);
                    }
                } else {
                    // if (this.sender === 'VES') {
                    //     let tcUtility = this.recomended_price - (this.recomended_price * (2) / 100);
                    //     return (Math.round(this.to_send * tcUtility * 100) / 100)
                    //         .toLocaleString('en');
                    // } else {
                    //     let tcUtility = this.recomended_price - (this.recomended_price * (4) / 100);
                    //     return (Math.round(this.to_send * tcUtility * 100) / 100)
                    //         .toLocaleString('en');
                    // }
                    let tcUtility   = this.recomended_price - (this.recomended_price * 4 / 100);
                    this.to_receive = Math.round(this.to_send * tcUtility * 100) / 100;
                    this.formattedToReceiveObject.set(this.to_receive);
                }

                this.storeData();
            },
            toSend: function () {
                if (this.to_receive === '') {
                    return '';
                }
                //TODO - El 2% debe poder definirse desde la administración, luego.
                // if (this.recomended_price < 1) {
                //     let tcUtility = this.recomended_price + (this.recomended_price * (-4) / 100);
                //     return (Math.round(this.to_send / tcUtility * 100) / 100)
                //         .toLocaleString('en');
                // } else {
                //     let tcUtility = this.recomended_price - (this.recomended_price * (-4) / 100);
                //     return (Math.round(this.to_send * tcUtility * 100) / 100)
                //         .toLocaleString('en');
                // }

                if (this.receiver === 'VES') {
                    if (this.recomended_price < 1) {
                        let tcUtility = this.recomended_price + (this.recomended_price * (-0) / 100);
                        this.to_send  = Math.round(this.to_receive * tcUtility * 100) / 100;
                        this.formattedToSendObject.set(this.to_send);
                    } else {
                        let tcUtility = this.recomended_price - (this.recomended_price * (-0) / 100);
                        this.to_send  = Math.round(this.to_receive / tcUtility * 100) / 100;
                        this.formattedToSendObject.set(this.to_send);
                    }
                } else {
                    // if (this.sender === 'VES') {
                    //     let tcUtility = this.recomended_price - (this.recomended_price * (2) / 100);
                    //     return (Math.round(this.to_send * tcUtility * 100) / 100)
                    //         .toLocaleString('en');
                    // } else {
                    //     let tcUtility = this.recomended_price - (this.recomended_price * (4) / 100);
                    //     return (Math.round(this.to_send * tcUtility * 100) / 100)
                    //         .toLocaleString('en');
                    // }
                    let tcUtility = this.recomended_price - (this.recomended_price * 4 / 100);
                    this.to_send  = Math.round(this.to_receive / tcUtility * 100) / 100;
                    this.formattedToSendObject.set(this.to_send);
                }

                this.storeData();
            } //582,484.66
        },
        updated: function () {
            // this.$nextTick(function () {
            //     this.storeData();
            // });
        },
        mounted() {
            this.$nextTick(function () {
                this.formattedToSendObject    = new AutoNumeric('#amount-to-send', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                });
                this.formattedToReceiveObject = new AutoNumeric('#amount-to-receive', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                });
                this.getPrice();
            });
        }
    }
</script>

<style lang="css">
</style>
