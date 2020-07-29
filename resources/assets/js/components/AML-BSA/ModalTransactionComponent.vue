<template>
    <tr v-bind:class="{ 'table-danger' : ( dataTransaction.tier_review == 1 || dataTransaction.tier_review == true )  }">
        <td class="text-center">
            {{getDate(dataTransaction.approved_at)}}
        </td>
        <td class="text-center">
            <a :href="getEndpoint('/wallets/transaction-details/' + dataTransaction.id)" target="_blank">
            {{dataTransaction.tracking_id}}
            </a>
        </td>
        <td class="text-center">
            {{dataTransaction.currency}} {{ parseFloat(dataTransaction.amount).toLocaleString('en')}}
        </td>
        <td class="text-center">
            {{getType(dataTransaction.type)}} {{getWithdraw_mode(dataTransaction.withdraw_mode)}}
        </td>
        <td v-if="type == 4" class="text-center">
            {{ dataTransaction.metadata.related_email}}
        </td>
        <td class="text-center">
             190.103.28.94
        </td>
        <td class="text-center">
            <button v-if="dataTransaction.tier_review == 0 || dataTransaction.tier_review == false" data-toggle="modal" 
                data-target="#transactions-details-modaaaaal" class="btn btn-danger" @click="showModalConfirmation(dataTransaction)">Mark as suspicious</button>
            <button v-else data-toggle="modal" 
                data-target="#transactions-unlock-details-modaaaaal" class="btn btn-warning" @click="showModalConfirmation(dataTransaction)">Desmark as suspicious</button>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['dataTransaction', 'type'],
        data() {
            return {
                dataTransactions: null,
                check: this.dataTransaction.tier_review,
                date: null
            };
        },
        mounted() {
            console.log('Component mounted.');
            console.log(this.dataTransaction);
            
        },
        methods: {

            getEndpoint(endpoint) {
                return window.location.origin + endpoint;
            },
            sendData(dataTransaction){
                console.log(this.dataTransaction.id);

                if (this.check == 0 || this.check == false){

                    console.log("Bloqueo");
                    
                    axios.post(window.location.origin+'/send-to-revision', {
                        transaction: this.dataTransaction.id,
                        tier_review: true
                    }).then(re => {
                        this.$emit('notification', this.dataTransaction.tracking_id, this.check);
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
                        transaction: this.dataTransaction.id,
                        tier_review: false
                    }).then(re => {
                        this.$emit('notification', this.dataTransaction.tracking_id, this.check);
                        console.log(re.data);
                    }).catch((error) => {
                        // Error 😨
                        if (error.response) {
                            this.check = !this.check;
                            this.$emit('notification');
                        }
                    });
                }
                
            },
            getDate(date){
                return moment(date).format('MM-DD-YYYY');

            },
            getType(meth) {
                let type = {
                    1: "Add Founds",
                    2: "Withdraw Founds",
                    3: "Hold Founds",
                    4: "Transfer between wallets"
                };
                return type[meth];
            },
            getWithdraw_mode(meth) {
                let type = {
                    1: "Normal exchange transaction",
                    2: "Withdraw to bank account",
                    3: "Withdraw to BTC",
                    4: "Withdraw to Employee",
                    5: "Withdraw to service",
                    6: "- reception",
                };
                return type[meth];
            },
            showModalConfirmation(transacation){
                if (this.check == 0 || this.check == false){

                    this.check = !this.check;
                    this.$emit('showModalConfirmation', transacation);
                    
                }else{
                    this.check = !this.check;
                    this.$emit('showModalUnlockConfirmation', transacation);                    
                }
                
            }
        },
    }

</script>
