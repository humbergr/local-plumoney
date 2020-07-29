<template>
    <div class="row" v-show="showDashboard==true">
        <div class="col-12">
            <tickets-count></tickets-count>
            <tickets-chart></tickets-chart>
        </div>
    </div>
</template>

<script>
import bus from '../../support-event-bus';
const globalDataHoy = [];
export default {

    created(){
        bus.$on('showDashboard', data =>{
            this.showDashboard = true;
        });
        bus.$on('showTickets', data =>{
            this.showDashboard = false;
        });
        bus.$on('showConfig', data =>{
            this.showDashboard = false;
        });
    },
    data(){
        return {
            showDashboard: true,
            }
        },
    }
</script>

<style lang="css">
    .small {
        max-width: 800px;
        /* max-height: 500px; */
        margin:  50px auto;
    }
</style>
