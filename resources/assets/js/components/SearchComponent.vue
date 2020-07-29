<template>
  <div class="">
      <div class="container">
        <h3>Advertisements</h3>
        <hr>
        <div class="text-center">
          <button type="button" v-on:click="buyBtc" :class="{ grey: active, white : !active }" class="btn btn-default" name="button">Buy Bitcoins</button>
          <button type="button" v-on:click="sellBtc" :class="{ grey: !active, white : active }" class="btn btn-default" name="button">Sell Bitcoins</button>
        </div>
        Search Advertisements:
        <div class="row">
          <div class="col-md-3">
            <select v-model="country" class="form-control" name="">
              <option  value="" selected disabled>Select Country</option>
              <option v-for="country in countries" v-bind:value="country.code">{{ country.name.replace(new RegExp('-', 'g'), " ") }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <input type="text" v-model="bank_string" v-on:change="regExp" class="form-control" name="" value="" placeholder="Bank Name">
          </div>
          <div class="col-md-2">
            <input v-model="amount" type="number" class="form-control" name="" value="" placeholder="Type Amount">
          </div>
          <div class="col-md-1">
            <button v-on:click="getAdvertisements(country)" class="btn btn-default" name="button">Filter</button>
          </div>
        </div>
      </div><br>
      <div class="container">
        <div class="row justify-content-center">
            <div v-for="advertisement in advertisements" class="col-md-6" style="margin-bottom:15px;">
                <div class="card card-default">
                    <a style="color:black" :href="advertisement.actions.public_view">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-md-6">
                        {{ advertisement.data.profile.username }}({{ advertisement.data.profile.feedback_score }}%, {{ advertisement.data.profile.trade_count }})
                        </div>
                        <div class="col-md-6 text-right">
                          <span style="text-align:right">{{parseFloat(advertisement.data.temp_price).toLocaleString('en')}} {{advertisement.data.currency}}/BTC</span>
                        </div>
                      </div>
                    </div>
                    </a>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">Bank: {{advertisement.data.bank_name}}</div><div class="col-md-6 text-right"> <span style="text-align:right">Min-max: {{advertisement.data.min_amount}}-{{advertisement.data.max_amount}}</span></div>
                      </div>
                    </div>
                  </div>
                  <div v-if="advertisement.data.currency == 'VES'" class="row">
                    <div class="col-md-6" style="padding-right:0px;">
                      <div class="card card-default">
                          <div class="card-body">
                            <span style="text-align:right">Rate: {{ parseFloat(advertisement.data.temp_price / bitstamp).toLocaleString('en') }} VES/USD</span>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="padding-left:0px;">
                      <div class="card card-default">
                          <div class="card-body">
                            <span style="text-align:right">Dollars: {{parseFloat((advertisement.data.max_amount_available / advertisement.data.temp_price) * bitstamp).toLocaleString('en')}} USD</span>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div v-if="user_role == 1" class="row">
                    <div style="margin:auto;margin-top:2px;">
                      <button type="button" class="btn btn-deafult grey" data-toggle="modal" data-target="#myModal" v-on:click="adToAssign(advertisement.data.ad_id)">Assign</button>
                    </div>
                  </div>
              </div>
          </div>
        </div>


    <!--    <div class="container">
          <div class="row justify-content-center">
              <div class="col-md-6" style="margin-bottom:15px;">
                  <div class="card card-default">
                      <a style="color:black" :href="'#!'">
                      <div class="card-header">
                        <div class="row">
                          <div class="col-md-6">
                          ivanlecointere(100%, 10000+)
                          </div>
                          <div class="col-md-6 text-right">
                            <span style="text-align:right">9,300,000.00/BTC</span>
                          </div>
                        </div>
                      </div>
                      </a>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-6">Bank: Banesco</div><div class="col-md-6 text-right"> <span style="text-align:right">Min-max: 500-800,000,000</span></div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6" style="padding-right:0px;">
                        <div class="card card-default">
                            <div class="card-body">
                              <span style="text-align:right">Rate: 3,000.00 VES/USD</span>
                            </div>
                          </div>
                      </div>
                      <div class="col-md-6" style="padding-left:0px;">
                        <div class="card card-default">
                            <div class="card-body">
                              <span style="text-align:right">Dollars: 12,500.00 USD</span>
                            </div>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div style="margin:auto;margin-top:2px;">
                        <button type="button" class="btn btn-deafult grey" data-toggle="modal" data-target="#myModal" v-on:click="changeTrader(854521)">Assign</button>
                      </div>
                    </div>
                </div>
            </div>
          </div> -->


        <hr>
          <div class="text-center">
            <div v-if="loader" class="loader"></div>
            <button v-if="url != '' && !loader" type="button" v-on:click="getNextPage" class="btn btn-default" name="button">Load more</button>
          </div>
          <br>
    <!--  <advertisements-component v-if="bool" :res="res"></advertisements-component> -->

    <!-- Modal -->
      <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Assign advertisement</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              Select Trader
            </div>
            <div class="col-md-6">
              <select class="form-control" v-model="trader" name="">
                <option value="" disabled>Select Trader</option>
                <option :value="trader.id" v-for="trader in traders">{{trader.name}}</option>
              </select>
            </div>
          </div><br>
          <div class="row">
            <div class="col-md-4">
              Type amount
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" name="" v-model="amount_to_trade" value="">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" v-on:click="assignAd" data-dismiss="modal">Assing</button>
          <button type="button" class="btn" data-dismiss="modal">Close</button>
        </div>
      </div>

      </div>
      </div>

  </div>
</template>

<script>
    export default {
      props: ['res', 'countries', 'advertisements2', 'url2', 'bank2', 'amount2', 'operation2', 'active2', 'bitstamp', 'traders2', 'user_role'],
      data() {
        return {
          country: 'VE',
          bank: this.bank2,
          amount: this.amount2,
          operation: this.operation2,
          active: this.active2,
          url : this.url2,
          advertisements: this.advertisements2,
          order: '',
          loader: false,
          bank_string: '',
          traders: this.traders2,
          trader: '',
          ad_to_assign: '',
          amount_to_trade: ''
        }
      },
        mounted() {
            console.log('Component active.')
        },
        methods: {
          adToAssign(id){
            this.ad_to_assign = id;
            this.amount_to_trade = '';
          },

          assignAd(){
            axios.post(window.location.origin+'/assign-ad/', {
              params: {
                trader:this.trader,
                advertisement:this.ad_to_assign,
                amount:this.amount_to_trade,
              }
            }).then(
              re => {

              }
            )
          },

          getAdvertisements(country){
            this.loader = true;
            axios.get(window.location.origin+'/get-ads/'+country, {
              params: {
                bank:this.bank,
                amount:this.amount,
                operation:this.operation
              }
            })
            .then(
              re => {
                if (typeof re.data.error !== 'undefined') {
                  this.loader = false;
                }
                this.advertisements = re.data.ads;
                var j = Object.keys(this.advertisements).length;
                this.url = re.data.next_page;
                if (j < 20) {
                  this.syncNetx(j);
                } else {
                  this.loader = false;
                }
              }
            )
          },

          getNextPage(){
            this.loader = true;
            var j = 0;
            this.syncNetx(j);
          },

          syncNetx(count){
            var j = count;
            var lenght = Object.keys(this.advertisements).length;
            axios.get(window.location.origin+'/get-next/', {
              params: {
                bank:this.bank,
                amount:this.amount,
                url:this.url
              }
            })
            .then(
              re => {
                if (typeof re.data.error !== 'undefined') {
                  this.loader = false;
                }
                for (var i = 0; i < re.data.ads.length; i++) {
                  this.advertisements[lenght + i] = re.data.ads[i];
                  j++;
                }
                this.url = re.data.next_page;
                if (j < 20) {
                  this.syncNetx(j);
                } else {
                  this.loader = false;
                }
              }
            )
          },

          sellBtc(){
            this.active = false;
            this.operation = 'sell';
            this.getAdvertisements(this.country);
          },

          buyBtc(){
            this.active = true;
            this.operation = 'buy';
            this.getAdvertisements(this.country);
          },

          orderBy(){
            if (this.order == 'bank') {
              this.advertisements = this.advertisements.sort(function (a, b){
                var nameA=a.data.bank_name.toLowerCase(), nameB=b.data.bank_name.toLowerCase()
                if (nameA < nameB) //sort string ascending
                    return -1
                if (nameA > nameB)
                    return 1
                return 0 //default return value (no sorting)
              })
            }
          },

          regExp(){
            this.bank = '(' + this.bank_string.toLowerCase().split('').join('.*') + ')i';
          }
        }
    }
</script>

<style media="screen">
  .grey {
    background-color: buttonface;
  }

  .white {
    background-color: #f8fafc;
  }

  .loader {
    border: 5px solid #f3f3f3; /* Light grey */
    border-top: 5px solid #848484; /* Blue */
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
    margin: 0 auto;
  }
</style>
