<template>

  <div class="">
    <div class="grid-x">
            <div class="cell btc-pricing">
                <div class="card card-default">
                    <div class="card-header">
                        <span>Bitcoin pricing</span>
                        <button type="button" v-on:click="getMarketData" name="button" class="button refresh-button">
                            <img src="img/icons/refresh-icon.png" alt="Refresh">
                            Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table style="width: 100%;">
                                <tr class="text-center">
                                    <th>Currency</th>
                                    <th><img src="img/icons/clock-icon.png" alt="By Time"> 1h ago</th>
                                    <th><img src="img/icons/clock-icon.png" alt="By Time"> 6h ago</th>
                                    <th><img src="img/icons/clock-icon.png" alt="By Time"> 12h ago</th>
                                    <th><img src="img/icons/clock-icon.png" alt="By Time"> 24h ago</th>
                                </tr>
                                <tr class="w-background">
                                    <td class="label-td">
                                        <img src="img/icons/label-icon.png" alt="Label">
                                        <strong>LocalBitcoins(VES)</strong>
                                    </td>
                                    <td class="text-center recent">{{ bs1h }}</td>
                                    <td class="text-center">{{ bs6h }}</td>
                                    <td class="text-center">{{ bs12h }}</td>
                                    <td class="text-center">{{ bs24h }}</td>
                                </tr>
                                <tr class="w-background">
                                    <td class="label-td">
                                        <img src="img/icons/label-icon.png" alt="Label">
                                        <strong>LocalBitcoins(USD)</strong>
                                    </td>
                                    <td class="text-center recent">{{ usd1h }}</td>
                                    <td class="text-center">{{ usd6h }}</td>
                                    <td class="text-center">{{ usd12h }}</td>
                                    <td class="text-center">{{ usd24h }}</td>
                                </tr>
                                <tr class="w-background">
                                    <td class="label-td">
                                        <img src="img/icons/label-icon.png" alt="Label">
                                        <strong>Bitstamp(USD)</strong>
                                    </td>
                                    <td class="text-center recent">{{ p_1h || 'NA' }}</td>
                                    <td class="text-center">{{ p_6h || 'NA' }}</td>
                                    <td class="text-center">{{ p_12h || 'NA' }}</td>
                                    <td class="text-center">{{ p_24h || 'NA' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-x">
            <div class="cell btc-volume">
                <div class="card card-default">
                    <div class="card-header">
                        <span>VES/USD Relation</span>
                    <!--    <button type="button" v-on:click="getMarketData" name="button" class="button refresh-button">
                            <img src="img/icons/refresh-icon.png" alt="Refresh">
                            Refresh
                        </button> -->
                    </div>
                    <div class="card-body">
                        <div class="grid-x medium-up-2">
                            <div class="cell">
                                <table>
                                    <tr>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td class="label-td">
                                                        <img src="img/icons/clock-icon.png" alt="Label">
                                                        <strong>1h ago</strong>
                                                    </td>
                                                    <td>{{ rel_1h || 'NA' }} VES/USD</td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td">
                                                        <img src="img/icons/clock-icon.png" alt="Label">
                                                        <strong>12h ago</strong>
                                                    </td>
                                                    <td>{{ rel_12h || 'NA' }} VES/USD</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td class="label-td">
                                                        <img src="img/icons/clock-icon.png" alt="Label">
                                                        <strong>6h ago</strong>
                                                    </td>
                                                    <td>{{ rel_6h || 'NA' }} VES/USD</td>
                                                </tr>
                                                <tr>
                                                    <td class="label-td">
                                                        <img src="img/icons/clock-icon.png" alt="Label">
                                                        <strong>24h ago</strong>
                                                    </td>
                                                    <td>{{ rel_24h || 'NA' }} VES/USD</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="cell">
                                <table>
                                    <tr class="text-center">
                                        <td>
                                            <strong>Bitstamp now</strong>
                                            {{ parseFloat(bitNow).toLocaleString('en') }} USD
                                        </td>
                                        <td>
                                            <strong>BTC volume VE</strong>
                                            <div>{{ volVe }}  BTC</div>
                                        </td>
                                        <td>
                                            <strong>BTC volume US</strong>
                                            <div>{{ volUs }}  BTC</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid-x">
            <div class="cell btc-volume">
                <div class="card card-default">
                    <div class="card-header">
                        <span>VES/USD Calculated Relation</span>
                    <!--    <button type="button" v-on:click="getMarketData" name="button" class="button refresh-button">
                            <img src="img/icons/refresh-icon.png" alt="Refresh">
                            Refresh
                        </button> -->
                    </div>
                    <div class="card-body">
                        <div class="grid-x">
                            <div class="cell">
                                <table>
                                    <tr class="text-center">
                                        <td>
                                            <strong>VES/USD Relation</strong>
                                            {{(Math.round((usd_bss) * 100) / 100).toLocaleString('en')}} VES/USD   <a href="#!" v-on:click="openModal"><img src="img/icons/edit.png" style="max-width: 20px !important" alt="Edit"></a>
                                        </td>
                                        <td>
                                            <strong>VES/BTC Relation</strong>
                                            {{(Math.round((btc_bss) * 100) / 100).toLocaleString('en')}}  VES/BTC <a href="#!" v-on:click="openModal"><img src="img/icons/edit.png" style="max-width: 20px !important" alt="Edit"></a>
                                        </td>
                                        <td>
                                            <strong>BTC Calculated cost</strong>
                                            <div>{{(Math.round((btc_bss / usd_bss) * 100) / 100).toLocaleString('en')}} USD/BTC</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Price Modal -->
        <div v-if="modal_visibility" class="modal">

          <!-- Modal content -->
          <div class="modal-content">
            <span v-on:click="closeModal" class="close">&times;</span>
            <div class="card card-default">
              <div class="card-header"><span>Update data</span></div>
                <div class="card-body"><br>

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">USD/VES Relation</label>
                      </div>
                      <div class="col-md-5">
                        <div class="input-group">
                          <input type="number" v-model="usd_bss_form" class="form-control" step="0.01" value="" name="usd_bss" required></label>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">BTC/VES Relation</label>
                      </div>
                      <div class="col-md-5">
                        <div class="input-group">
                          <input type="number" v-model="btc_bss_form" class="form-control" step="0.01" value="" name="btc_bss" required></label>
                        </div>
                      </div>
                    </div><br>

                    <div class="text-center">
                      <button type="submit" v-on:click="updateManualData" class="button refresh-button" style="margin:auto !important">Update</button>
                    </div>

                </div>
            </div>
          </div>

        </div>

  </div>

</template>

<script>
    export default {
        props: ['marketD', 'btc_bss_2', 'usd_bss_2'],
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
            modal_visibility: false,
            usd_bss_form: this.usd_bss_2,
            btc_bss_form: this.btc_bss_2,
            btc_bss: this.btc_bss_2,
            usd_bss: this.usd_bss_2
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
                this.bitNow = re.data.bsd.bitNow;
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
          },

          openModal() {
            this.modal_visibility = true;
          },

          closeModal() {
            this.modal_visibility = false;
          },

          updateManualData() {
            axios.get(window.location.origin+'/update-traders-data', {
              params: {
                usd_bss: this.usd_bss_form,
                btc_bss: this.btc_bss_form
              }
            })
              .then(re => {
                console.log(re.data);
                if (re.data.status == 'success') {
                  this.usd_bss = this.usd_bss_form;
                  this.btc_bss = this.btc_bss_form;
                }
              })

            this.closeModal();
          }
        },

        mounted() {
            console.log('Component mounted.');
            this.getMarketData();
        },

    }
</script>

<style>
  td, th {
    padding: .5rem .625rem .625rem;
  }
</style>
