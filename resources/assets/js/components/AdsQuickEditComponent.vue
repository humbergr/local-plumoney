<template lang="html">
  <div class="grid-x">
    <div class="cell btc-pricing">
        <div class="card card-default">
            <div class="card-header">
                <span>My advertisements</span>
            </div>
            <div class="card-body">
                <div v-if="ads_loader" class="loader2"></div>
                <div v-else class="row">
                    <table style="width: 100%;">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Status</th>
                            <th>Info</th>
                            <th>Price</th>
                            <th>Equation</th>
                        </tr>
                        <tr v-for="advertisement in my_ads" class="w-background">
                          <th><a :href="getEditUrl(advertisement.data.ad_id)">{{ advertisement.data.ad_id }}</a></th>
                          <th v-if="advertisement.data.visible" style="color:green">Active</th>
                          <th v-else style="color:red">Disabled</th>
                          <th style="color:black">{{ advertisement.data.trade_type }} {{ settingPaymentMethod(advertisement.data.online_provider) }} </th>
                          <th style="color:black">{{ advertisement.data.temp_price }}  {{ advertisement.data.currency }}</th>
                          <th style="color:black"  :ref="'button-' + advertisement.data.ad_id"><a href="#!" v-on:click="quickEdit(advertisement.data.ad_id)">{{ advertisement.data.price_equation }}</a></th>
                          <th :ref="'th-' + advertisement.data.ad_id" hidden><input type="text" :ref="'input-' + advertisement.data.ad_id" name="" :value="advertisement.data.price_equation" v-on:change="changeEquation(advertisement.data.ad_id)" v-on:blur="focusOut(advertisement.data.ad_id)"></th>
                        </tr>
                    </table>
                </div>
                <div class="text-center">
                  <p v-if="resp == 'Ad changed successfully!'" style="color:green">{{resp}}</p>
                  <p v-else style="color:red">{{resp}}</p>
                </div>
            </div>
        </div>
    </div>

  </div>
</template>

<script>
export default {
  props: [],

  mounted() {
    console.log(this.$refs);
    this.myAds();
  },

  data() {
    return{
      my_ads: [],
      resp: null,
      ads_loader: true,
    }
  },

  methods:{
    settingPaymentMethod(meth){
      for (var key in this.paymentMethods) {
        if (this.paymentMethods[key].code == meth) {
          return this.paymentMethods[key].name;
        }
      }
    },

    changeEquation(referencia){
      var new_eq = this.$refs['input-' + referencia][0].value;

      axios.post(window.location.origin+'/edit-equation/'+referencia, {
        params: {
          price_equation: this.$refs['input-' + referencia][0].value
        }
      })
        .then(re => {
        this.resp = re.data;
        if (re.data == 'Ad changed successfully!') {
          this.$refs['button-' + referencia][0].hidden = false;
          this.$refs['th-' + referencia][0].hidden = true;
          this.updateAds(referencia, new_eq);
        }

        })
    },

    focusOut(referencia){
      this.$refs['button-' + referencia][0].hidden = false;
      this.$refs['th-' + referencia][0].hidden = true;
    },

    quickEdit(referencia){
      this.$refs['button-' + referencia][0].hidden = true;
      this.$refs['th-' + referencia][0].hidden = false;
      this.$refs['input-' + referencia][0].focus();
    },

    myAds(){
      axios.post(window.location.origin+'/my-ads')
        .then(re => {
          this.my_ads = re.data.data.ad_list;
          this.ads_loader = false;
        })
    },

    updateAds(referencia, eq){
      this.my_ads.forEach(function(index){
        if (index.data.ad_id == referencia) {
          index.data.price_equation = eq;
        }
      })
    },

    getEditUrl(id){
      return window.location.origin+'/edit-advertisement/'+id;
    },
  }
}
</script>

<style lang="css">
.loader2 {
  border: 10px solid #f3f3f3; /* Light grey */
  border-top: 10px solid #46953f; /* Blue */
  border-radius: 50%;
  width: 70px;
  height: 70px;
  animation: spin 2s linear infinite;
  margin: 100px auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
