<template lang="html">
    <div class="container">
        <vue-toastr ref="toastr"></vue-toastr>


        <section id="analytics" class="pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 v-if="type == 6" class="sections-titles">Compra BTC</h3>
                        <h3 v-if="type == 7" class="sections-titles">Venta BTC</h3>
                    </div>
                </div>
            </div>
        </section>

        <section id="transactions" class="py-section-3">
            <div class="container-fluid">

                <div class="card" id="list">
                    <div class="loader--wrapper" :class="{ '--loading': loader }">
                        <table class="table table-striped table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Contact ID LB
                                        </h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            User LB
                                        </h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Name
                                        </h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Client ID
                                        </h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Amount BTC
                                        </h5>
                                    </th>
                                    <th class="text-center">
                                        <h5 class="text-muted font-weight-bold mb-0">
                                            Details
                                        </h5>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <transaction-btc-component
                                    v-for="(transaction, index) in transactions.data"
                                    @showModal="openModal"
                                    :key="transaction.id"
                                    :transaction="transaction"
                                    :type="type">
                                </transaction-btc-component>
                                
                            </tbody>
                        </table>
                        <div class="loader">
                            <div class="spinner-border text-primary"
                                style="width: 3rem; height: 3rem;"
                                role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
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
        </section>
             

        <!-- Modal -->
        <div v-if="modal_visibility">
            <div class="modal fade" id="transactions-details-modal" tabindex="-1" role="dialog"
                aria-labelledby="transactionsDetailsModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content loader--wrapper" :class="{ '--loading': loaderModal }">
                        <div class="modal-body p-md-4">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary font-weight-bold mb-0">User ID:</h5>
                                    <h5 class="text-secondary font-weight-bold">{{dataModal.user_account.id}}</h5>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary font-weight-bold mb-0">Nombre y Apellido</h5>
                                    <h5 class="text-secondary font-weight-bold">
                                        <a :href="getEndpoint('/user-profile/' + dataModal.user_account.id)" class="text-secondary font-weight-bold" target="_blank">
                                            {{ dataModal.user_account.name }}
                                        </a>
                                    </h5>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary font-weight-bold mb-0">Traking ID:</h5>
                                    <h5 class="text-secondary font-weight-bold">{{ dataModal.tracking_id }}</h5>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary font-weight-bold mb-0">Monto Diario</h5>
                                    <h5 class="text-secondary font-weight-bold">{{ parseFloat(dataModalAmount.daily).toLocaleString('en') }}</h5>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary font-weight-bold mb-0">Monto Semanal</h5>
                                    <h5 class="text-secondary font-weight-bold">{{ parseFloat(dataModalAmount.weekly).toLocaleString('en') }}</h5>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary font-weight-bold mb-0">Monto Mensual</h5>
                                    <h5 class="text-secondary font-weight-bold">{{ parseFloat(dataModalAmount.monthly).toLocaleString('en') }}</h5>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary font-weight-bold mb-0">Monto Anual</h5>
                                    <h5 class="text-secondary font-weight-bold">{{ parseFloat(dataModalAmount.yearly).toLocaleString('en') }}</h5>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <h5 class="text-primary font-weight-bold mb-0">Operación Sospechosa</h5>
                                    <h5 class="text-secondary font-weight-bold"><input type="checkbox" v-on:click="sendData()" v-bind:value="check" v-model="check" ></h5>
                                </div>
                            </div>                    
                        </div>
                        <button type="button" class="custom-close-modal" data-dismiss="modal" aria-label="Close">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: ['transactions', 'type'],
    data() {
        return {
            cont: 1,
            transactions: this.transactions,
            type: this.type,
            modal_visibility:false,
            loader: false,
            loaderModal: false,
            dataModal: null,
            dataModalAmount: null,
            check: null,
        };
    },
    methods: {
        di: function (mensaje) {
            alert(mensaje)
            console.log(this.transaction);
        },
        showNotificacion(id, check){
            if(check == 0 || check == false) {
                this.$refs.toastr.s('Se marco la operación: ' + id);
            }else{
                this.$refs.toastr.w('Se desmarco la operación: ' + id);
            }          
        },
        transactionsPagination(page) {
            this.loader = true;
            axios.get(window.location.origin + '/pagination', {
                params: {
                    page: page,
                    type: this.type
                }
            }).then(re => {
                //Vue.set(this, transactions, re.data);
                console.log(re.data);
                this.transactions = re.data;
                this.loader = false;
            });
        },
        getEndpoint(endpoint) {
            return window.location.origin + endpoint;
        },
        openModal(data, dataAmount) {
            this.loaderModal      = true;
            this.dataModalAmount  = dataAmount;
            this.dataModal        = data;
            this.check            = data.tier_review,
            this.modal_visibility = true;
        },
        sendData(){
                console.log(this.dataModal.id);

                if (this.check == 0 || this.check == false){

                    axios.post(window.location.origin+'/send-to-revision', {
                        transaction: this.dataModal.id,
                        tier_review: true
                    }).then(re => {
                        console.log(re.data);
                    });
                    
                }else{
                    axios.post(window.location.origin+'/send-to-revision', {
                        transaction: this.dataModal.id,
                        tier_review: false
                    }).then(re => {
                        console.log(re.data);
                    });
                }
                this.showNotificacion(this.dataModal.tracking_id, this.check);
                this.transactionsPagination(this.transactions.current_page)
        }
    }
};
</script>


<style lang="scss">
.graph-ranges {
    font-size: 14px;
    color: #777;
    border-color: #303392;
    padding: 6px;
}

.sections-titles {
    font-size: 45px;
    text-align: center;
    color: #f7941d;
    font-weight: 700;
}

.calculator-btc-sale {
    background: #fff;
    padding: 1rem 12px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 1rem;

    input,
    select {
        margin: 0 10px;
    }

    label {
        margin: 0 1rem;
    }
}

.calculator-results {
    .row {
        margin: 0;
        background: #fff;
        padding: 1rem 12px;
        border-radius: 8px;
    }

    .alert-no-btc {
        display: none;
    }

    .suggestions {
        display: flex;
        align-items: center;
        justify-content: center;

        .suggested-rate {
            text-align: center;

            span {
                display: block;
                font-weight: 700;
            }
        }
    }

    h6 {
        text-align: center;
        margin-bottom: 1rem;
    }
}
</style>
