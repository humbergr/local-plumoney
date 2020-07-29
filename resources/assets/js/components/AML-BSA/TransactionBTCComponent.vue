<template>
    
        
        <tr>
            <td class="text-center">{{ transaction.transaction_id }}</td>
            <td class="text-center">
                <a :href="getEndpoint('/user-profile/' + transaction.account_name)" target="_blank">
                    {{ transaction.account_name }}
                </a>
            </td>
            <td class="text-center text-primary font-weight-bold">--</td>
            <td class="text-center text-primary font-weight-bold">--</td>

            <td class="text-center">
                BTC
                {{ parseFloat(transaction.amount_btc).toLocaleString('en') }}
            </td>


            <!-- <td class="text-center">
                <input type="checkbox" v-on:click="sendData(transaction)" v-bind:value="check" v-model="check" >
            </td> -->
            <td  class="text-center">
                <button class="btn btn-outline-secondary btn-sm btn-pill text-muted" 
                data-toggle="modal" 
                data-target="#transactions-details-modal"
                @click="showModal()">
                    View Details {{type}}
            </button>

            </td>
        </tr>
 
    
</template>

<script>
    export default {
        props: ['transaction', 'type'],
        data() {
            return {
                dataAmount: null,
            };
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {

            getEndpoint(endpoint) {
                return window.location.origin + endpoint;
            },
            showModal(){
                axios.get(window.location.origin+'/get-data-to-user-review',  {
                    params: {
                        transaction: this.transaction,
                        tier_review: true,
                        type: this.type
                    }
                }).then(re => {
                    //Vue.set(this, transactions, re.data);
                    console.log(re.data);
                    this.dataAmount = re.data
                    this.$emit('showModal', this.transaction, this.dataAmount);
                });      
            },
            getPaymentMethod(meth) {
                let paymentMethods = {
                    cash: "Pago en Efectivo",
                    Stripe: "Tarjeta de Crédito o Débito",
                    QuickBook: "Tarjeta de Crédito o Débito QB",
                    zelle: "Zelle",
                    venmo: "Venmo",
                    cashapp: "Cash App",
                    payoneer: "Payoneer",
                    popmoney: "PopMoney",
                    pandco: "Pandco",
                    userWallet: "Billetera AKB",
                    amaz_prepaid: "Tarjeta VISA pre. Amazon",
                    ath_prepaid: "Tarjeta Gift Card American Time Holding"
                };
                return paymentMethods[meth];
            },
        },
    }

</script>
