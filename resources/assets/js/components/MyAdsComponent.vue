<template lang="html">
  <div class="container">
      <div class="text-center">
        <button type="button" v-on:click="openTrades" :class="{ grey: ads, white : !ads }" class="btn btn-default" name="button">Open trades & advertisements</button>
        <button type="button" v-on:click="closedTrades" :class="{ grey: closed, white : !closed }" class="btn btn-default" name="button">All closed trades</button>
        <button type="button" v-on:click="completedTrades" :class="{ grey: completed, white : !completed }" class="btn btn-default" name="button">Completed trades</button>
        <button type="button" v-on:click="cancelledTrades" :class="{ grey: cancelled, white : !cancelled }" class="btn btn-default" name="button">Cancelled trades</button>
      </div><br>
      <div class="row justify-content-center">
          <div class="col-md-12">
              <div class="card card-default">
                <div class="card-header">Your advertisements</div>
                  <div class="card-body">
                    <div v-if="loader" class="row">
                      <div class="loader"></div>
                    </div>
                    <div v-else class="row">
                      <table v-if="this.ads" style="width:100%">
                        <tr class="text-center">
                          <th>#</th>
                          <th>Status</th>
                          <th>Info</th>
                          <th>Price</th>
                          <th>Equation</th>
                          <th>Created at</th>
                          <th></th>
                        </tr>
                        <tr v-for="advertisement in advertisements" class="text-center">
                          <th><a href="">{{ advertisement.data.ad_id }}</a></th>
                          <th v-if="advertisement.data.visible" style="color:green">Active</th>
                          <th v-else style="color:red">Disabled</th>
                          <th>{{ advertisement.data.trade_type }} {{ settingPaymentMethod(advertisement.data.online_provider) }} </th>
                          <th>{{ advertisement.data.temp_price }}  {{ advertisement.data.currency }}</th>
                          <th>{{ advertisement.data.price_equation }}</th>
                          <th>{{ getDate(advertisement.data.created_at) }}</th>
                          <th><a :href="getEditUrl(advertisement.data.ad_id)" class="btn btn-default grey" style="color:black">Edit</a></th>
                        </tr>
                      </table>

                      <table v-else style="width:100%">
                        <tr class="text-center">
                          <th>#</th>
                          <th>Created at</th>
                          <th>Trade type</th>
                          <th>Trading partner</th>
                          <th>Fiat</th>
                          <th>Trade amount</th>
                          <th>Trading fee</th>
                          <th>Total BTC</th>
                          <th>Exchange rate</th>
                        </tr>
                        <tr v-for="trade in trades" class="text-center">
                          <th>{{ trade.data.contact_id }}</th>
                          <th>{{ getTradeDate(trade.data.created_at) }}</th>
                          <th>{{ trade.data.advertisement.trade_type }} {{ settingPaymentMethod(trade.data.advertisement.payment_method) }}</th>
                          <th>{{ getPartner(trade.data.is_buying, trade.data.seller.username, trade.data.buyer.username) }}</th>
                          <th>{{ trade.data.amount }} {{ trade.data.currency }}</th>
                          <th>{{ trade.data.amount_btc }} BTC</th>
                          <th>{{ trade.data.fee_btc }} BTC</th>
                          <th>{{ trade.data.amount_btc }} BTC</th>
                          <th>{{ (Math.round((trade.data.amount / trade.data.amount_btc) * 100)) / 100 }} {{ trade.data.currency }}</th>
                        </tr>
                      </table>
                    </div>
                  </div>
              </div>
            </div>
      </div><br>
      <div class="text-center">
        <button v-if="url_prev != ''" type="button" v-on:click="getPage(url_prev)" class="btn btn-default" name="button">Previous</button>
        <button v-if="url_next != ''" type="button" v-on:click="getPage(url_next)" class="btn btn-default" name="button">Next</button>
      </div><br>
  </div>
</template>

<script>
export default {

  props: ['advertisements2', 'paymentmethods2'],

  data(){
    return {
      advertisements: this.advertisements2,
      paymentMethods: this.paymentmethods2,
      ads: true,
      closed: false,
      completed: false,
      cancelled: false,
      trades: [],
      url_next: '',
      url_prev: '',
      loader:false
    }
  },

  methods: {
    getDate(date){
      var newDate = new Date(date);
      var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
      return months[newDate.getMonth()] + ', ' + newDate.getDate() + ', ' + newDate.getFullYear() + ', ' + newDate.getHours() + ':' + newDate.getMinutes();
    },

    getTradeDate(date){
      var newDate = new Date(date);
      return (newDate.getMonth() + 1) + '/' + newDate.getDate() + '/' + newDate.getFullYear();
    },

    settingPaymentMethod(meth){
      for (var key in this.paymentMethods) {
        if (this.paymentMethods[key].code == meth) {
          return this.paymentMethods[key].name;
        }
      }
    },

    getEditUrl(id){
      return window.location.origin+'/edit-advertisement/'+id;
    },

    openTrades(){
      this.ads = true;
      this.closed = false;
      this.completed = false;
      this.cancelled = false;
      this.url_next = '';
      this.url_prev = '';
    },

    closedTrades(){
      this.loader = true;
      axios.post(window.location.origin+'/post-closed-trades')
        .then(re => {
          this.trades = re.data.data.contact_list;

          this.ads = false;
          this.closed = true;
          this.completed = false;
          this.cancelled = false;

          if (re.data.hasOwnProperty('pagination') && re.data.pagination.hasOwnProperty('next')) {
          this.url_next = re.data.pagination.next;
          }else {
          this.url_next = '';
          }
          if (re.data.hasOwnProperty('pagination') && re.data.pagination.hasOwnProperty('prev')) {
          this.url_prev = re.data.pagination.prev;
          } else {
          this.url_prev = '';
          }
          this.loader = false;
        })
    },

    completedTrades(){
      this.loader = true;
      axios.post(window.location.origin+'/post-completed-trades')
        .then(re => {
          this.trades = re.data.data.contact_list;

          this.ads = false;
          this.closed = false;
          this.completed = true;
          this.cancelled = false;

          if (re.data.hasOwnProperty('pagination') && re.data.pagination.hasOwnProperty('next')) {
          this.url_next = re.data.pagination.next;
          }else {
          this.url_next = '';
          }
          if (re.data.hasOwnProperty('pagination') && re.data.pagination.hasOwnProperty('prev')) {
          this.url_prev = re.data.pagination.prev;
          } else {
          this.url_prev = '';
          }
          this.loader = false;
        })
    },

    cancelledTrades(){
      this.loader = true;
      axios.post(window.location.origin+'/post-cancelled-trades')
        .then(re => {
          this.trades = re.data.data.contact_list;

          this.ads = false;
          this.closed = false;
          this.completed = false;
          this.cancelled = true;

          if (re.data.hasOwnProperty('pagination') && re.data.pagination.hasOwnProperty('next')) {
          this.url_next = re.data.pagination.next;
          }else {
          this.url_next = '';
          }
          if (re.data.hasOwnProperty('pagination') && re.data.pagination.hasOwnProperty('prev')) {
          this.url_prev = re.data.pagination.prev;
          } else {
          this.url_prev = '';
          }
          this.loader = false;
        })
    },

    getPartner(is_buying, seller, buyer){
      if (is_buying) {
        return seller+' is selling'
      } else {
        return buyer+' is buying'
      }
    },

    getStatus(){
      if (this.closed) {
        return 'Closed';
      } else if (this.completed) {
        return 'Completed'
      } else if (this.cancelled) {
        return 'Canceled'
      }
    },

    getPage(url){
      this.loader = true;
      axios.get(window.location.origin+'/trades-page', {
        params: {
          url: url
        }
      })
        .then(re => {
          this.trades = re.data.data.contact_list;

          if (re.data.hasOwnProperty('pagination') && re.data.pagination.hasOwnProperty('next')) {
          this.url_next = re.data.pagination.next;
          }else {
          this.url_next = '';
          }
          if (re.data.hasOwnProperty('pagination') && re.data.pagination.hasOwnProperty('prev')) {
          this.url_prev = re.data.pagination.prev;
          } else {
          this.url_prev = '';
          }
          this.loader = false;
        })
    },

  },

  mounted() {
      console.log('Component mounted.');
  },

}
</script>

<style lang="css">
  .grey {
    background-color: buttonface;
  }

  .white {
    background-color: #f8fafc;
  }

  .loader {
    border: 10px solid #f3f3f3; /* Light grey */
    border-top: 10px solid #848484; /* Blue */
    border-radius: 50%;
    width: 70px;
    height: 70px;
    animation: spin 2s linear infinite;
    margin: 0 auto;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
