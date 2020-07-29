<template lang="html">
    <div class="col-md-5 col-6">
        <h4 class="__l_deposit_value d-md-none">
            <span class="d-block">Valor:</span>
            <strong>
                {{order.sender_fiat}}
                {{parseFloat(account_to_pay.fiat_amount).toLocaleString('en')}}
            </strong>
        </h4>
        <div class="d-md-block __timer"
             v-if="remainingTime[3] >= 0 && verifyDate() && account_to_pay.canceled !== 1 && account_to_pay.payed !== 1">
            <div class="text-center">
             <span class="d-block text-primary">
                 Tiempo limite para pagar
             </span>
                <div class="d-flex justify-content-center mt-2 mb-1">
                    <div class="text-orange mx-2">
                        <small class="d-block">horas</small>
                        <h4 class="font-weight-light mb-0 mt-n1">
                            {{remainingTime[0]}}
                        </h4>
                    </div>
                    <div class="text-orange mx-2">
                        <small class="d-block">minutos</small>
                        <h4 class="font-weight-light mb-0 mt-n1">
                            {{remainingTime[1]}}
                        </h4>
                    </div>
                    <div class="text-orange mx-2">
                        <small class="d-block">segundos</small>
                        <h4 class="font-weight-light mb-0 mt-n1">
                            {{remainingTime[2]}}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="__pay_report_button"
             v-if="remainingTime[3] >= 0 && verifyDate() && account_to_pay.canceled !== 1 && account_to_pay.payed !== 1">
            <div class="input-group flex-nowrap mb-0">
                <div class="input-group-prepend">
                    <div class="inputFile--selected d-none"
                         :id="'inputFile--selected-' + account_to_pay.id"
                    >
                        <i class="fa fa-check"></i>
                    </div>
                </div>
                <label :id="'upload-voucher-' + account_to_pay.id"
                       type="button"
                       :for="'photo-voucher-input-' + account_to_pay.id"
                       class="btn btn-light text-truncate rounded-0 _dynamic_file_input"
                       style="margin: 0"
                       title="Cargar foto del comprobante">
                    Cargar Foto del Comprobante
                </label>
            </div>
            <div :id="'selected-file-name' + account_to_pay.id"
                 class="__filename mt-1"></div>
            <input :id="'photo-voucher-input-' + account_to_pay.id"
                   :data-account-id="account_to_pay.id"
                   class="_dynamic_file_input"
                   type="file"
                   accept="image/*,application/pdf"
                   style="display: none"
                   v-on:change="fileHandler2(account_to_pay.id)"
                   ref="file">

            <div class="" style="display: none"
                 :id="'pay-input-' + account_to_pay.id">
                <a href="javascript:void(0)"
                   class="btn btn-secondary btn-block btn-pill"
                   @click="sendFile2(account_to_pay.id)">
                    Pagar
                </a>
            </div>
        </div>

        <div class="d-flex __status_board justify-content-center"
             style="height: 100%"
             v-if="account_to_pay.payed === 1">
            <div class="__success_bank_account">
                <p>Se ha registrado como pagada.</p>
                <span class="__success_title">
                  Pagada
              </span>
            </div>
        </div>
        <div class="d-flex __status_board justify-content-center"
             style="height: 100%"
             v-if="(remainingTime[3] < 0 || !verifyDate()) && account_to_pay.canceled !== 1 && account_to_pay.payed !== 1">
            <div class="__failed_bank_account">
                <p>El tiempo de pago se ha agotado.</p>
                <span class="__failed_title">
                  Tiempo agotado
              </span>
            </div>
        </div>
        <div class="d-flex __status_board justify-content-center"
             style="height: 100%"
             v-if="account_to_pay.canceled === 1">
            <div class="__failed_bank_account">
                <p>La cuenta se ha cancelado. ¡No deposite o transfiera!</p>
                <span class="__failed_title">
                  Cuenta Cancelada
              </span>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['account', 'accounts', 'order', 'operator'],

        data() {
            return {
                count: 0,
                created: this.account.created_at,
                limit: this.account.limit_date,
                utc_now: moment.utc().format('YYYY-MM-DD HH:mm:ss'),
                account_to_pay: this.account,
                accounts_to_pay: this.accounts,
                loader: false
            }
        },

        computed: {
            remainingTime: function () {
                var created_date = new Date(this.created);
                var utc_date     = new Date(this.utc_now);
                var limit_date   = new Date(this.limit);

                var res = (Math.abs(limit_date - utc_date) / 1000) - this.count;

                return [
                    Math.floor(res / 3600) % 24,
                    (Math.floor(res / 60) % 60 < 10 ? '0' : '') + Math.floor(res / 60) % 60,
                    (res % 60 < 10 ? '0' : '') + res % 60,
                    res
                ];
            }
        },

        methods: {
            momentCountdown() {
                let vueObject = this;
                setInterval(() => {
                    this.count++;
                }, 1000)
            },

            verifyDate() {
                var utc_date   = new Date(this.utc_now),
                    limit_date = new Date(this.limit);

                if (limit_date - utc_date > 0) {
                    return true;
                }

                return false;
            },

            sendFile2(ID) {
                let vueObject    = this,
                    fileToUpload = vueObject.$refs.file.files[0];

                if (fileToUpload) {
                    this.loader  = true;
                    let formData = new FormData();
                    formData.append('file', fileToUpload);
                    axios.post(window.location.origin + '/api/wallets/create-order-message/' + this.order.id, formData, {
                        params: {
                            bankAccountID: ID,
                            message: '',
                            operatorID: this.operator,
                            _token: $('meta[name="csrf-token"]').attr("content")
                        }
                    }).then(re => {
                        if (re.data[0] === 'success') {
                            this.account_to_pay.payed = 1;
                        }
                        this.loader = false;
                        this.file   = '';
                        this.$emit('getMessages');
                    })
                }
            },

            fileHandler2(ID) {
                let activeInputFile = this.$refs.file.files[0];

                $('#upload-voucher-' + ID).text('Archivo Seleccionado');
            },
        },

        mounted() {
            this.momentCountdown();
        },
    }
</script>

<style lang="css">
</style>
