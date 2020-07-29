<template lang="html">

  <div class="grid-container adds-wrapper">
        <div class="grid-x">
            <div class="medium-8 cell medium-offset-2">
                <h2 class="text-center body-title">
                    Advertisements
                </h2>
                <div class="filters">
                    <ul class="tabs" data-tabs id="actions-tabs">
                        <li class="tabs-title is-active">
                            <a href="#toSell" v-on:click="sellBtc" aria-selected="true">
                                <img src="img/icons/to-sell-icon.png">
                                To Sell
                            </a>
                        </li>
                        <li class="tabs-title">
                            <a data-tabs-target="toBuy" v-on:click="buyBtc" href="#toBuy">
                                <img src="img/icons/to-buy-icon.png">
                                To Buy
                            </a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="actions-tabs">
                        <div class="tabs-panel is-active">
                            <label>
                                Select a Country
                                <select name="" v-model="country" class="form-control">
                                    <option value="" selected="selected" disabled="disabled">Select Country</option>
                                    <option v-for="country in countries" v-bind:value="country.code">{{ country.name.replace(new RegExp('-', 'g'), " ") }}</option>
                                </select>
                            </label>
                            <div class="action">
                                <button name="button" v-on:click="getAdvertisements(country)" class="button">Filter Advertisements</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="adds-list">
                    <div class="grid-x medium-up-2 grid-margin-x">
                        <div v-for="advertisement in advertisements" class="cell add">
                            <div class="card card-default">
                                <div class="card-header">
                                    <div class="grid-x">
                                        <div class="cell user-info">
                                            <img src="img/icons/user-icon.png" alt="User icon">
                                            User: {{ advertisement.data.profile.username }}
                                        </div>
                                        <div class="cell btc-price-info">
                                            <span>{{advertisement.data.temp_price}} {{advertisement.data.currency}}/BTC</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="grid-x">
                                        <div class="cell bank-info">Bank: {{advertisement.data.bank_name}}</div>
                                        <div class="cell">
                                            <div class="grid-x medium-up-2 prices-values">
                                                <div class="cell">
                                                    <p class="value-info">
                                                        Min
                                                        <span class="value">{{advertisement.data.min_amount}}</span>
                                                    </p>
                                                </div>
                                                <div class="cell">
                                                    <p class="value-info">
                                                        Max
                                                        <span class="value">{{advertisement.data.max_amount}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="cell align-center is-flex">
                      <button v-if="url_prev != ''" type="button" v-on:click="getPage(url_prev)" name="button" class="button refresh-button">
                          Previous
                      </button>
                        <button type="button" v-if="url_next != ''" v-on:click="getPage(url_next)" name="button" class="button refresh-button">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
                if (re.data.ads.hasOwnProperty('pagination') && re.data.ads.pagination.hasOwnProperty('next')) {
                this.url_next = re.data.ads.pagination.next;
                }else {
                this.url_next = '';
                }
                if (re.data.ads.hasOwnProperty('pagination') && re.data.ads.pagination.hasOwnProperty('prev')) {
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

<style lang="css">
</style>
