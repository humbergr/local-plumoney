<template>
        <tr v-bind:class="{ 'table-danger' : ( transaction.user_person_profile.approval_status == 4  )  }">
            <td class="text-center">
                <button v-if="transaction.user_person_profile.approval_status != 4 " data-toggle="modal" 
                data-target="#transactions-details-modaaaaal" class="btn btn-danger" @click="showModalConfirmation(transaction)">Mark as suspicious</button>
                <button v-else data-toggle="modal" 
                    data-target="#transactions-details-modaaaaal" class="btn btn-warning" @click="showModalConfirmation(transaction)">Desmark as suspicious</button>
            </td>
            <td class="text-center">
                <a :href="getEndpoint('/user-profile/' + transaction.user_account.id)" target="_blank">
                    {{ transaction.user_account.name }}
                </a>
            </td>
            <td class="text-center">
                {{ transaction.currency }}
                {{ parseFloat(transaction.day).toLocaleString('en') }}
            </td>
            <td class="text-center">
                {{ transaction.currency }}
                {{ parseFloat(transaction.week).toLocaleString('en') }}
            </td>
            <td class="text-center">
                {{ transaction.currency }}
                {{ parseFloat(transaction.month).toLocaleString('en') }}
            </td>
            <td class="text-center">
                {{ transaction.currency }}
                {{ parseFloat(transaction.trimester).toLocaleString('en') }}
            </td>
            <td class="text-center">
                {{ transaction.currency }}
                {{ parseFloat(transaction.year).toLocaleString('en') }}
            </td>


            <td class="text-center">
                <div class="progress">
                    <div v-if="transaction.user_person_profile.tier_level == 0" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Tier: {{transaction.user_person_profile.tier_level}}</div>
                    <div v-if="transaction.user_person_profile.tier_level == 1" class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Tier: {{transaction.user_person_profile.tier_level}}</div>
                    <div v-if="transaction.user_person_profile.tier_level == 2" class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">Tier: {{transaction.user_person_profile.tier_level}}</div>
                    <div v-if="transaction.user_person_profile.tier_level == 3" class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">Tier: {{transaction.user_person_profile.tier_level}}</div>
                    <div v-if="transaction.user_person_profile.tier_level >= 4" class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Tier: {{transaction.user_person_profile.tier_level}}</div>
                </div>
            </td>

            <td  class="text-center">
                <button class="btn btn-outline-secondary btn-sm btn-pill text-muted" 
                data-toggle="modal" 
                data-target="#transactions-details-modal"
                @click="showModal()">
                    View Details
            </button>

            </td>
        </tr>
 
    
</template>

<script>
    export default {
        props: ['transaction', 'type'],
        data() {
            return {
                dataTransactions: null,
                check: (this.transaction.user_person_profile.approval_status == 4 ) ? true : false
            };
        },
        mounted() {
            console.log('Component mounted lidys.');
        },
        methods: {

            getEndpoint(endpoint) {
                return window.location.origin + endpoint;
            },
            sendData(dataTransaction){

                if (this.check == 0 || this.check == false){

                    console.log("Bloqueo");
                    
                    axios.post(window.location.origin+'/send-to-revision', {
                        user: this.transaction.user_account.id,
                        tier_review: true
                    }).then(re => {
                        this.$emit('notification', this.transaction.user_account.id, this.check);
                        console.log(re.data);
                    }).catch((error) => {
                        // Error 😨
                        if (error.response) {
                            this.check = !this.check;
                            this.$emit('notification');
                        } 
                    });
                    
                }else{

                    console.log("desbloqueo");
                    
                    axios.post(window.location.origin+'/send-to-revision', {
                        user: this.transaction.user_account.id,
                        tier_review: false
                    }).then(re => {
                        this.$emit('notification', this.transaction.user_account.id, this.check);
                        console.log(re.data);
                    }).catch((error) => {
                        // Error 😨
                        if (error.response) {
                            console.log(error.response);
                            
                            this.check = !this.check;
                            this.$emit('notification');
                        }
                    });
                }
                
            },
            showModalConfirmation(transacation){
                if (this.check == 0 || this.check == false){

                    this.check = !this.check;
                    this.$emit('showModalConfirmationUserTier', transacation);
                    
                }else{

                    console.log("desbloqueo");
                    
                    
                
                }
            },
            showModal(){
                console.log(this.transaction);
                axios.get(window.location.origin+'/get-data-to-user-review',  {
                    params: {
                        transaction: this.transaction,
                        tier_review: true,
                        type: this.type,
                        tracking: this.tracking,
                        ip: this.searchTraking,
                    }
                }).then(re => {
                    console.log(re.data);
                    this.dataTransactions = re.data
                    this.$emit('showModal', this.transaction, this.dataTransactions);
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
