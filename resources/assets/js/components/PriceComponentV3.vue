<template lang="html">

  <section id="btc-summary">
        <div class="container">
            <div class="card card--alpha">
                <div class="card-body p-md-5">
                    <div class="section-heading d-flex justify-content-between pb-2">
                        <h4 class="text-primary font-weight-bold mt-auto mb-0">Bitcoin Pricing</h4>
                        <div><button class="btn btn-secondary btn-pill btn-sm" v-on:click="getMarketData"><i class="fa fa-refresh mr-2"></i>Refresh</button></div>
                    </div>

                    <!-- add "--loading" to "loader--wrapper" class when loading -->
                    <div class="loader--wrapper" :class="{ '--loading': loader }">
                        <table class="table mb-4">
                            <thead class="thead-none">
                                <tr>
                                    <th class="text-primary text-center" scope="col">Currency</th>
                                    <th class="text-primary text-center" scope="col"><i class="fa fa-clock-o mr-2"></i>1h ago</th>
                                    <th class="text-primary text-center" scope="col"><i class="fa fa-clock-o mr-2"></i>6h ago</th>
                                    <th class="text-primary text-center" scope="col"><i class="fa fa-clock-o mr-2"></i>12h ago</th>
                                    <th class="text-primary text-center" scope="col"><i class="fa fa-clock-o mr-2"></i>24h ago</th>
                                </tr>
                            </thead>
                            <tbody class="tbody-white">
                                <tr>
                                    <th scope="row"><i class="text-muted fa fa-tag mr-2"></i> <span class="h5 text-primary font-weight-bold mb-0">LocalBitcoins(VES)</span></th>
                                    <td class="text-center"><h5 class="mb-0">{{bs1h || '-'}}</h5></td>
                                    <td class="text-center"><span class="text-muted">{{bs6h || '-'}}</span></td>
                                    <td class="text-center"><span class="text-muted">{{bs12h || '-'}}</span></td>
                                    <td class="text-center"><span class="text-muted">{{bs24h || '-'}}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="text-muted fa fa-tag mr-2"></i> <span class="h5 text-primary font-weight-bold mb-0">LocalBitcoins(USD)</span></th>
                                    <td class="text-center"><h5 class="mb-0">{{usd1h || '-'}}</h5></td>
                                    <td class="text-center"><span class="text-muted">{{usd6h || '-'}}</span></td>
                                    <td class="text-center"><span class="text-muted">{{usd12h || '-'}}</span></td>
                                    <td class="text-center"><span class="text-muted">{{usd24h || '-'}}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="text-muted fa fa-tag mr-2"></i> <span class="h5 text-primary font-weight-bold mb-0">Bitstamp(USD)</span></th>
                                    <td class="text-center"><h5 class="mb-0">
                                      {{ p_1h || '-' }}
                                    </h5></td>
                                    <td class="text-center"><span class="text-muted">
                                      {{ p_6h || '-' }}
                                    </span></td>
                                    <td class="text-center"><span class="text-muted">
                                      {{ p_12h || '-' }}
                                    </span></td>
                                    <td class="text-center"><span class="text-muted">
                                      {{ p_24h || '-' }}
                                    </span></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="loader">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="section-heading d-flex justify-content-between pb-2 border-bottom-0 mb-2">
                        <h4 class="text-primary font-weight-bold mt-auto mb-0">BsS/USD Relation</h4>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- add "--loading" to "loader--wrapper" class when loading -->
                            <div class="loader--wrapper" :class="{ '--loading': loader }">
                                <table class="table mb-0">
                                    <tbody class="tbody-white">
                                        <tr>
                                            <td class="border-right">
                                                <table class="table mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="px-1 py-2">
                                                                <h6 class="text-primary font-weight-bold mb-0"><i class="fa fa-clock-o mr-1"></i>1h ago</h6>
                                                            </td>
                                                            <td class="px-1 py-2 text-right">{{ rel_1h || '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-1 py-2">
                                                                <h6 class="text-primary font-weight-bold mb-0"><i class="fa fa-clock-o mr-1"></i>12h ago</h6>
                                                            </td>
                                                            <td class="px-1 py-2 text-right">{{ rel_12h || '-' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <table class="table mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="px-1 py-2">
                                                                <h6 class="text-primary font-weight-bold mb-0"><i class="fa fa-clock-o mr-1"></i>6h ago</h6>
                                                            </td>
                                                            <td class="px-1 py-2 text-right">{{ rel_6h || '-' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-1 py-2">
                                                                <h6 class="text-primary font-weight-bold mb-0"><i class="fa fa-clock-o mr-1"></i>24h ago</h6>
                                                            </td>
                                                            <td class="px-1 py-2 text-right">{{ rel_24h || '-' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="loader">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <!-- add "--loading" to "loader--wrapper" class when loading -->
                            <div class="loader--wrapper" :class="{ '--loading': loader }">
                                <div class="card rounded h-100">
                                    <div class="card-body d-flex flex-column justify-content-center px-1">
                                        <div class="row">
                                            <div class="col-md-4 my-auto text-center border-right --strong">
                                                <h5 class="text-primary font-weight-bold">Bitstamp now</h5>
                                                <div class="h5 mb-0">{{ parseFloat(bitNow).toLocaleString('en') || '-' }} USD</div>
                                            </div>
                                            <div class="col-md-4 my-auto text-center border-right --strong">
                                                <h5 class="text-primary font-weight-bold">BTC volume VE</h5>
                                                <div class="h5 mb-0">{{ volVe || '-'  }} BTC</div>
                                            </div>
                                            <div class="col-md-4 my-auto text-center">
                                                <h5 class="text-primary font-weight-bold">BTC volume US</h5>
                                                <div class="h5 mb-0">{{ volUs || '-'  }} BTC</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="loader">
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
export default {
  data() {
    return {
      loader: false,
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
        this.loader = true;
        axios.get(window.location.origin+'/market-data')
          .then(re => {
            this.loader = false;
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
  },

  mounted() {
      this.getMarketData();
  },
}
</script>

<style lang="css">
</style>
