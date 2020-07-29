<template lang="html">

<div class="">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">Payment Data</div>
                <div class="card-body">

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Bank Name</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text" class="form-control" value="" name="payment[bank_name]" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">It is a corporate account</label>
                    </div>
                    <div class="col-md-4">
                      <div class="input-group">
                        <input type="checkbox" v-model="is_corporate" value="" name="is-corporate"></label>
                      </div>
                    </div>
                  </div><br>

                  <div v-if="is_corporate" class="row">
                    <div class="col-md-2">
                      <label for="visible">Written authorization to buy</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                      <input type="file" class="" value="" name="authorization-document" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Street Address of Bank</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text" class="form-control" value="" name="payment[bank_street]" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Country of Bank</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text" class="form-control" value="" name="payment[bank_country]" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">State of Bank</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text" class="form-control" value="" name="payment[bank_state]" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Zip or Postal Code of Bank</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="number" class="form-control" value="" name="payment[zip-postal_code]" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Bank SWIFT code</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="number" class="form-control" value="" name="payment[swift_code]" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Account Number</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="number" class="form-control" value="" name="payment[account_number]" required></label>
                      </div>
                    </div>
                  </div><br>

                </div>
            </div>
        </div>
    </div><br>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">Information</div>
                <div class="card-body">

                  <p>- Bank account owner name and LocalBitcoins account username must match.</p>
                  <p>- If the bank account is a corporate account, the account representative must authorize the purchase in writing.</p>
                  <p>- We only accept WIRE TRANSFERS.</p>
                  <p>- We do not trade with people who are in the following states: CT, GA, HI, NM, NY, PA, WA.</p>
                  <p>- If it's the first time you trade with PluMoney you must perform the WIRE TRANSFER in the bank branch in USA.</p>
                  <p>- We do not accept any form of non-bank payment. The refund can take 3 days.</p>

                </div>
            </div>
        </div>
    </div><br>

</div>

</template>

<script>
export default {
  data(){
    return {
      is_corporate: false,
    }
  },

  methods: {
    getUrl(endpoint){
      return window.location.origin+endpoint;
    },
  },
    changeCorporate(){
      this.is_corporate = !this.is_corporate;
    }
}
</script>

<style lang="css">
</style>
