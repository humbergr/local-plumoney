<template lang="html">
    <div class="loader--wrapper" :class="{ '--loading': loader }">
        <ul class="navbar-nav align-items-center flex-row">
            <li class="nav-item mr-3">
                <span class="badge badge-light border-0 text-uppercase font-13 py-2 px-2">BTC <b>{{btc}}</b></span>
            </li>
        </ul>
        <div class="loader">
            <div class="spinner-border text-primary" style="width: 1.3rem; height: 1.3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                usd: '',
                btc: '',
                loader: true
            }
        },

        methods: {
            walletsData(){
                axios.get(window.location.origin+'/wallets-data')
                    .then(re => {
                        this.usd = re.data.usd;
                        this.btc = re.data.btc;
                        this.loader = false;
                    })
            }
        },

        mounted() {
            this.walletsData();
        }
    }
</script>

<style lang="css">
</style>
