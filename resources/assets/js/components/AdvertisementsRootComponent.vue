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
        <!--  <div class="col-md-3">
            <select v-model="bank" class="form-control" name="">
              <option  value="" selected disabled>Select Bank</option>
              <option  value="">None</option>
              <option value="(b.*a.*n.*e.*s.*c.*o)i">Banesco</option>
              <option value="(m.*e.*r.*c.*a.*n.*t.*i.*l)i">Banco Mercantil</option>
              <option value="(v.*e.*n.*e.*z.*u.*e.*l.*a)i">Banco de Venezuela</option>
              <option value="(p.*r.*o.*v.*i.*n.*c.*i.*a.*l)i">Banco Provincial</option>
            </select>
          </div> -->
        <!--  <div class="col-md-2">
            <input v-model="amount" type="number" class="form-control" name="" value="" placeholder="Type Amount">
          </div> -->
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
            <button v-if="url != ''" type="button" v-on:click="getNextPage" class="btn btn-default" name="button">Load more</button>
          </div>
          <br>
    <!--  <advertisements-component v-if="bool" :res="res"></advertisements-component> -->
  </div>
</template>

<script>
    export default {
      props: ['res', 'countries', 'advertisements2', 'url2'],
      data() {
        return {
          country: 'VE',
          bank: '',
          amount: '',
          operation: 'sell',
          active: false,
          url : this.url2,
          advertisements: this.advertisements2
        }
      },
        mounted() {
            console.log('Component active.')
        },
        methods: {
          getAdvertisements(country){
            axios.get(window.location.origin+'/get-ads/'+country, {
              params: {
                bank:this.bank,
                amount:this.amount,
                operation:this.operation
              }
            })
            .then(
              re => {
                this.advertisements = re.data.ads;
                this.url = re.data.next_page;
              }
            )
          },

          getNextPage(){
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
                for (var i = 0; i < re.data.ads.length; i++) {
                  this.advertisements[lenght + i] = re.data.ads[i];
                }
                this.url = re.data.next_page;
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
