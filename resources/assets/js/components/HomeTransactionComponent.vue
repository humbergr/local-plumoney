<template lang="html">
    <div class="row">
        <form class="" :action="getUrl('/new-transaction')" v-on:submit="storeData" method="post">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="recomended_price" v-model="recomended_price" value="">
            <div class="col-lg-8 col-xl-9 mx-auto px-0 px-md-3">
                <div class="card card--alpha">
                    <div class="card-body px-md-5">
                        <div class="card mb-3 wow fadeIn">
                            <div class="card-body text-left">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5 class="text-primary font-weight-bold mb-0 mb-md-2">Monto a enviar</h5>
                                        <div class="input-group input-group-lg mb-3">
                                            <input type="number" name="to_send" class="form-control" v-model="to_send"
                                                   placeholder="Ingrese monto a enviar" required>
                                            <div class="input-group-append select-appended">
                                                <select v-model="sender" name="sender" v-on:change="getPrice"
                                                        id="amount-to-send"
                                                        class="custom-select flag-selector border-left"
                                                        style="background-repeat: no-repeat">
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
                                    <div class="col-lg-6">
                                        <h5 class="text-primary font-weight-bold mb-0 mb-md-2">Entregamos</h5>
                                        <div class="input-group input-group-lg mb-3">
                                            <input type="text" class="form-control" name="to_receive" :value="toReceive"
                                                   placeholder="Ingrese monto a enviar" value="BsS 180.000.00" readonly>
                                            <div class="input-group-append select-appended">
                                                <select v-model="receiver" name="receiver" v-on:change="getPrice"
                                                        class="custom-select flag-selector border-left"
                                                        style="background-repeat: no-repeat">
                                                    <optgroup label="Latino América">
                                                        <option value="VES" data-flag="img/landing/flags/ve.svg">
                                                            Venezuela
                                                        </option>
                                                        <option value="ARS" data-flag="img/landing/flags/ar.svg">
                                                            Argentina
                                                        </option>
                                                        <!--  <option value="BO" data-flag="img/landing/flags/bo.svg">Bolivia</option>
                                                          <option value="BR" data-flag="img/landing/flags/br.svg">Brasil</option> -->
                                                        <option value="CLP" data-flag="img/landing/flags/cl.svg">Chile
                                                        </option>
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
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <button class="btn btn-secondary btn-lg btn-pill pl-md-3 pr-md-4 wow fadeIn"><img
                                    src="img/landing/send-money-btn.svg" type="submit" class="img-fluid mr-3">Enviar
                                dinero
                            </button>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="custom-control custom-checkbox text-left" style="max-width: 360px">
                                <input type="checkbox" name="certification" class="custom-control-input"
                                       id="certification-checkbox" required>
                                <label class="custom-control-label" for="certification-checkbox"
                                       style="line-height: 1.25">Certifico que soy mayor de edad y que el dinero de esta
                                    Transacción cumple con las <a href="" class="font-weight-bold">normas legales
                                        vigentes.</a></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import AutoNumeric from "autonumeric";

    export default {
        data() {
            return {
                to_send: '',
                to_receive: '',
                sender: 'USD',
                receiver: 'VES',
                recomended_price: '',
                csrf: $('meta[name="csrf-token"]').attr("content"),
            }
        },

        computed: {
            // toSend: function() {
            //
            // },
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
                        let tcUtility = this.recomended_price + (this.recomended_price * (-0) / 100);
                        return (Math.round(this.to_send / tcUtility * 100) / 100)
                            .toLocaleString('en');
                    } else {
                        let tcUtility = this.recomended_price - (this.recomended_price * (-0) / 100);
                        return (Math.round(this.to_send * tcUtility * 100) / 100)
                            .toLocaleString('en');
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
                    return (Math.round(this.to_send * tcUtility * 100) / 100)
                        .toLocaleString('en');
                }
            }
        },

        methods: {
            getPrice() {
                this.to_send = '';
                axios.get(window.location.origin + '/get-price', {
                    params: {
                        sender: this.sender,
                        receiver: this.receiver
                    }
                })
                    .then(
                        re => {
                            this.recomended_price = re.data;
                        }
                    )
            },

            getUrl(endpoint) {
                return window.location.origin + endpoint;
            },

            storeData() {
                localStorage.setItem("to_send", this.to_send);
                localStorage.setItem("to_receive", this.toReceive.replace(/,/g, ''));
                localStorage.setItem("sender", this.sender);
                localStorage.setItem("receiver", this.receiver);
                localStorage.setItem("recomended_price", this.recomended_price);
            }
        },

        mounted() {
            this.$nextTick(() => {
                this.getPrice();
                new AutoNumeric('#amount-to-send').northAmerican();
            })
        }
    }
</script>

<style lang="css">
</style>
