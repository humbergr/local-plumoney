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
          <div class="col-md-2">
            <button v-on:click="getAdvertisements(country)" class="btn btn-default" name="button">Filter</button>
          </div>
        </div>
      </div><br>
      <div class="container">
        <div class="row justify-content-center">
            <div v-for="advertisement in advertisements" class="col-md-6" style="margin-bottom:15px;">
                <div class="card card-default">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-md-6">
                        User:  {{ advertisement.data.profile.username }}
                        </div>
                        <div class="col-md-6 text-right">
                          <span style="text-align:right">{{advertisement.data.temp_price}} {{advertisement.data.currency}}/BTC</span>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">Bank: {{advertisement.data.bank_name}}</div><div class="col-md-6 text-right"> <span style="text-align:right">Min-max: {{advertisement.data.min_amount}}-{{advertisement.data.max_amount}}</span></div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <hr>
          <div class="text-center">
            <button v-if="url_prev != ''" type="button" v-on:click="getPage(url_prev)" class="btn btn-default" name="button">Previous</button>
            <button v-if="url_next != ''" type="button" v-on:click="getPage(url_next)" class="btn btn-default" name="button">Next</button>
          </div>
          <br>
    <!--  <advertisements-component v-if="bool" :res="res"></advertisements-component> -->
  </div>
</template>

<script>
    export default {
      props: ['res', 'countries', 'advertisements2', 'url2', 'baseurl2'],
      data() {
        return {
          country: 'VE',
          operation: 'sell',
          active: false,
          url_next: this.url2,
          url_prev: '',
          baseurl: this.baseurl2,
          advertisements: this.advertisements2
        }
      },
        mounted() {
             this.$nextTick(function () {
                  window.setInterval(() => {
                      this.getUpdated(this.baseurl);
                  },10000);
              })
        },
        methods: {

          getPage(url){
            this.baseurl = url;
            axios.get(window.location.origin+'/get-next-advertisements/', {
              params: {
                url: url
              }
            })
            .then(
              re => {
                this.advertisements = re.data.data.ad_list;
                if (re.data.pagination.next) {
                this.url_next = re.data.pagination.next;
                }else {
                this.url_next = '';
                }
                if (re.data.pagination.prev) {
                this.url_prev = re.data.pagination.prev;
                } else {
                this.url_prev = '';
                }
              }
            )
          },

          getUpdated(url){
            this.baseurl = url;
            axios.get(window.location.origin+'/get-next-advertisements/', {
              params: {
                url: url
              }
            })
            .then(
              re => {
                this.advertisements = re.data.data.ad_list;
              }
            )
          },

          getAdvertisements(country){
            axios.get(window.location.origin+'/get-ads-code/'+this.operation+'/'+country, {
            })
            .then(
              re => {
                this.baseurl = re.data.url;
                this.advertisements = re.data.ads.data.ad_list;
                if (re.data.ads.pagination.next) {
                this.url_next = re.data.ads.pagination.next;
                }else {
                this.url_next = '';
                }
                if (re.data.ads.pagination.prev) {
                this.url_prev = re.data.ads.pagination.prev;
                } else {
                this.url_prev = '';
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
</style>
