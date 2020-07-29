<template lang="html">
  <div class="card-body">

      <h3>Trade type</h3><hr>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">I want to...</label>
        </div>
        <div class="col-md-4">
          <select class="form-control" name="ad-trade_type" required>
            <option value="ONLINE_BUY">Buy bitcoins online</option>
            <option value="ONLINE_SELL">Sell bitcoins online</option>
          </select>
        </div>
        <div class="col-md-6">
          <p>What kind of trade advertisement do you wish to create? If you wish to sell bitcoins make sure you have bitcoins in your LocalBitcoins wallet.</p>
        </div>
      </div>

      <google-places-component></google-places-component>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Payment method</label>
        </div>
        <div class="col-md-4">
          <select class="form-control" v-model="selectedMethod" name="ad-online_provider" v-on:change="paymentCurrencies(selectedMethod)" required>
            <option value="" selected disabled>Select payment method</option>
            <option v-for="(payment_method, index) in payment_methods" :value="payment_method.code">{{payment_method.name}}</option>
          </select>
        </div>
        <div class="col-md-6">
          <p></p>
        </div>
      </div><br>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Currency</label>
        </div>
        <div class="col-md-4">
          <select class="form-control" name="currency" required>
            <option value="" selected disabled>Select currency</option>
            <option v-for="currency in currencies" :value="currency">{{currency}}</option>
          </select>
        </div>
        <div class="col-md-6">
          <p></p>
        </div>
      </div><br>

      <div class="row" v-if="selectedMethod != '' && methods_bank_name.includes(selectedMethod)">
        <div class="col-md-2">
          <label for="visible">Bank Name or ID code</label>
        </div>
        <div class="col-md-4">
          <div>
            <input type="text" class="form-control" name="ad-bank_name" value="" required>
          </div>
        </div>
        <div class="col-md-6">
          <p>Required. The name or identifying code of the bank you are using.</p><br>
        </div>
      </div>
      <div v-else class="row">
        <input type="hidden" name="ad-bank_name" value="">
      </div>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Price equation</label>
        </div>
        <div class="col-md-4">
          <div>
            <input type="text" class="form-control" name="ad-price_equation" value="" required>
          </div>
        </div>
        <div class="col-md-6">
          <p>How the trade price is determined from the hourly market price.</p>
        </div>
      </div><br>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Min. transaction limit</label>
        </div>
        <div class="col-md-4">
          <div>
            <input type="number" class="form-control" name="ad-min_amount" value="" required>
          </div>
        </div>
        <div class="col-md-6">
          <p>Optional. Minimum transaction limit in one trade.</p>
        </div>
      </div><br>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Max. transaction limit</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="number" class="form-control" name="ad-max_amount" value="" required>
          </div>
        </div>
        <div class="col-md-6">
          <p>Optional. Maximum transaction limit in one trade. For online sells, your LocalBitcoins.com wallet balance may limit the maximum fundable trade also.</p>
        </div>
      </div><br>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Restrict amounts to</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="number" class="form-control" name="ad-limit_to_fiat_amounts" value="">
          </div>
        </div>
        <div class="col-md-6">
          <p>Optional. Restrict trading amounts to specific comma-separated integers, for example 20,50,100. In fiat currency (USD/EUR/etc). Handy for coupons, gift cards, etc.</p>
        </div>
      </div><br>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Terms of trades</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <textarea name="ad-msg" class="form-control" rows="8" cols="80"></textarea>
          </div>
        </div>
        <div class="col-md-6">
          <p>Other information you wish to tell about your trade. </p>
        </div>
      </div><br>

      <h3>Online buying options</h3><hr>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Payment window</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="number" class="form-control" name="ad-payment_window_minutes" value="">
          </div>
        </div>
        <div class="col-md-6">
          <p>Other information you wish to tell about your trade. </p>
        </div>
      </div><br>

      <h3>Liquidity options</h3><hr>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Track liquidity</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="checkbox" value="" name="ad-track_max_amount"></label>
          </div>
        </div>
        <div class="col-md-6">
          <p>This option limits the liquidity of this advertisement to the max. transaction limit. Buyers cannot open and complete trades for more than this amount.</p>
        </div>
      </div><br>

      <h3>Security options</h3><hr>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Identified people only</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="checkbox" value="" name="ad-require_identification"></label>
          </div>
        </div>
        <div class="col-md-6">
          <p>To contact your advertisement, users need to verify their identity by sending IDs, driver's licence or passport.</p>
        </div>
      </div><br>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">SMS verification required</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="checkbox" value="" name="ad-sms_verification_required"></label>
          </div>
        </div>
        <div class="col-md-6">
          <p>Only contacts with a verified mobile phone number can contact you from the advertisement.</p>
        </div>
      </div><br>

      <div class="row">
        <div class="col-md-2">
          <label for="visible">Trusted people only</label>
        </div>
        <div class="col-md-4">
          <div class="input-group">
            <input type="checkbox" value="" name="ad-require_trusted_by_advertiser"></label>
          </div>
        </div>
        <div class="col-md-6">
          <p>Restrict your advertisement to be shown only to users that you have marked as Trusted.</p>
        </div>
      </div><br>

      <div class="text-center">
        <button type="submit" class="btn btn-default" name="button">Create Advertisement</button>
        <a href="#!" class="btn" style="color:black">Cancel</a>
      </div>

  </div>
</template>

<script>
export default {
  props: ['url', 'methods'],

  data(){
    return {
      'post_url': this.url,
      'payment_methods': this.methods,
      'currencies':[],
      'selectedMethod':'',
      'methods_bank_name': ['NATIONAL_BANK', 'SEPA', 'SPECIFIC_BANK', 'INTERNATIONAL_WIRE_SWIFT', 'OTHER', 'CASH_DEPOSIT', 'OTHER_ONLINE_WALLET', 'OTHER_ONLINE_WALLET_GLOBAL', 'OTHER_REMITTANCE', 'OTHER_PRE_PAID_DEBIT']
    }
  },

  methods:{

    paymentCurrencies(method){

        var payments = this.payment_methods;
        var the_currencies = [];

        Object.keys(payments).forEach(function(pay_method){
         if (payments[pay_method]['code'] == method) {
            the_currencies = payments[pay_method].currencies;
          }
        });

        this.currencies = the_currencies;
    }

  }
}
</script>

<style lang="css">
</style>
