<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                  <div class="card-header">Bitcoin pricing</div>
                    <div class="card-body">
                      <div class="row">
                        <table style="width:100%">
                          <tr class="text-center">
                            <th>Currency</th>
                            <th>1h ago</th>
                            <th>6h ago</th>
                            <th>12h ago</th>
                            <th>24h ago</th>
                          </tr>
                          <tr class="text-center">
                            <td>LocalBitcoins(BsS)</td>
                            <td>{{ bs1h }}</td>
                            <td>{{ bs6h }}</td>
                            <td>{{ bs12h }}</td>
                            <td>{{ bs24h }}</td>
                          </tr>
                          <tr class="text-center">
                            <td>LocalBitcoins(USD)</td>
                            <td>{{ usd1h }}</td>
                            <td>{{ usd6h }}</td>
                            <td>{{ usd12h }}</td>
                            <td>{{ usd24h }}</td>
                          </tr>
                          <tr class="text-center">
                            <td>Bitstamp(USD)</td>
                            <td>{{ p_1h || 'NA' }}</td>
                            <td>{{ p_6h || 'NA' }}</td>
                            <td>{{ p_12h || 'NA' }}</td>
                            <td>{{ p_24h || 'NA' }}</td>
                          </tr>
                        </table>
                      </div>
                    </div>
                </div>
            </div><hr>
            <div class="col-md-12">
                <div class="card card-default">
                  <div class="card-header">BsS/USD Relation</div>
                    <div class="card-body">
                      <div class="row">
                        <table style="width:100%">
                          <tr class="text-center">
                            <th>1h ago</th>
                            <th>6h ago</th>
                            <th>12h ago</th>
                            <th>24h ago</th>
                          </tr>
                          <tr class="text-center">
                            <td>{{ rel_1h || 'NA' }} BsS/USD</td>
                            <td>{{ rel_6h || 'NA' }} BsS/USD</td>
                            <td>{{ rel_12h || 'NA' }} BsS/USD</td>
                            <td>{{ rel_24h || 'NA' }} Bsf/USD</td>
                          </tr>
                        </table>
                      </div>
                      <hr>
                      <div class="row">
                        <table style="width:100%">
                          <tr class="text-center">
                            <th>Bitstamp now</th>
                            <th>BTC volume VE</th>
                            <th>BTC volume US</th>
                          </tr>
                          <tr class="text-center">
                            <td>
                              {{ bitNow }} USD
                            </td>
                            <td>
                              <div>{{ volVe }}  BTC</div>
                            </td>
                            <td>
                              <div>{{ volUs }}  BTC</div>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                </div>
            </div>

            <div class="row">
              <div class="col-sm-3 col-sm-offset-9">
                <button type="button" v-on:click="getMarketData" style="margin-top:15px"  class="btn btn-default pull-right" name="button">Refresh</button>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['marketD'],
        data () {
          return {
            loader_0: false,
            loader_1: false,
            bs24h : null,
            bs6h : null,
            bs1h : null,
            bs12h : null,
            bs24h : null,
            usd1h : null,
            usd6h : null,
            usd12h : null,
            usd24h : null,
            volVe : null,
            volUs : null,
            p_1h: null,
            p_6h: null,
            p_12h: null,
            p_24h: null,
            bitNow: null,
            rel_1h: null,
            rel_6h: null,
            rel_12h: null,
            rel_24h: null,
          }
        },

        methods: {
          getMarketData() {
            axios.get(window.location.origin+'/market-data')
              .then(re => {
                this.bs1h = parseFloat(re.data.ticker.VES.avg_1h).toLocaleString('en');
                this.bs6h = parseFloat(re.data.ticker.VES.avg_6h).toLocaleString('en');
                this.bs12h = parseFloat(re.data.ticker.VES.avg_12h).toLocaleString('en');
                this.usd1h = parseFloat(re.data.ticker.USD.avg_1h).toLocaleString('en');
                this.usd6h = parseFloat(re.data.ticker.USD.avg_6h).toLocaleString('en');
                this.usd12h = parseFloat(re.data.ticker.USD.avg_12h).toLocaleString('en');
                this.bs24h = parseFloat(re.data.ticker.VES.avg_24h).toLocaleString('en');
                this.usd24h = parseFloat(re.data.ticker.USD.avg_24h).toLocaleString('en');
                this.volVe = parseFloat(re.data.volumes.ve_vol).toLocaleString('en');
                this.volUs = parseFloat(re.data.volumes.us_vol).toLocaleString('en');
                this.bitNow = parseFloat(re.data.bsd.bitNow).toLocaleString('en');
                this.p_1h = parseFloat(re.data.bsd.p_1h.price).toLocaleString('en');
                this.rel_1h = (Math.round((re.data.ticker.VES.avg_1h / re.data.bsd.p_1h.price) * 100) / 100).toLocaleString('en');
                this.p_6h = parseFloat(re.data.bsd.p_6h.price).toLocaleString('en');
                this.rel_6h = (Math.round((re.data.ticker.VES.avg_6h / re.data.bsd.p_6h.price) * 100) / 100).toLocaleString('en');
                this.p_12h = parseFloat(re.data.bsd.p_12h.price).toLocaleString('en');
                this.rel_12h = (Math.round((re.data.ticker.VES.avg_12h / re.data.bsd.p_12h.price) * 100) / 100).toLocaleString('en');
                this.p_24h = parseFloat(re.data.bsd.p_24h.price).toLocaleString('en');
                this.rel_24h = (Math.round((re.data.ticker.VES.avg_24h / re.data.bsd.p_24h.price) * 100) / 100).toLocaleString('en');
              })
          },
          getMarketDataNow() {
            axios.get(window.location.origin+'/market-data-now')
              .then(re => {
                this.bsNow = re.data.bsNow;
                this.usdNow = re.data.usdNow;
              })
          }
        },

        mounted() {
            console.log('Component mounted.');
            this.getMarketData();
        },

    }
</script>

<style media="screen">
.loader {
  border: 4px solid #f3f3f3; /* Light grey */
  border-top: 4px solid #848484; /* Blue */
  border-radius: 50%;
  width: 25px;
  height: 25px;
  animation: spin 2s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
