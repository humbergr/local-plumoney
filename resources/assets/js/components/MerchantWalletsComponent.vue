<template lang="html">
    <section id="wallets" class="container mb-n5 d-none d-md-block">
        <div class="d-flex flex-column flex-md-row justify-content-end">
            <div class="wallet__item media align-items-center mb-3 mb-md-0">
                <img src="/img/cb-img/cb-wallet-dollar.png" class="img-fluid mr-2">
                <div v-if="!loader" class="media-body">
                    <h6 class="wallet__text small mb-0">In your wallet</h6>
                    <h4 class="wallet__amount mb-0">{{usd.toLocaleString('en')}} USD</h4>
                </div>
                <div v-else class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="wallet__item media align-items-center mb-3 mb-md-0">
                <img src="/img/cb-img/cb-wallet-btc.png" class="img-fluid mr-2">
                <div v-if="!loader" class="media-body">
                    <h6 class="wallet__text small mb-0">In your wallet</h6>
                    <h4 class="wallet__amount mb-0">{{btc}} BTC</h4>
                </div>
                <div v-else class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                usd: 0,
                btc: 0,
                loader: true
            }
        },

        methods: {
            walletsData() {
                axios.get(window.location.origin + '/wallets-data')
                    .then(re => {
                        this.usd    = re.data.usd;
                        this.btc    = re.data.btc;
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
