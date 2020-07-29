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
                      <label for="visible">Account Name</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text" class="form-control" value="" name="payment[account_name]" required></label>
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

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Account Routing</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text" class="form-control" value="" name="payment[account_routing]" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Account Address</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text" class="form-control" value="" name="payment[account_address]" required></label>
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

                  <p>- We only accept WIRE TRANSFERS.</p>
                  <p>- We do not trade with people who are in the following states: CT, GA, HI, NM, NY, PA, WA.</p>
                  <p>- If it's the first time you trade with PluMoney you must perform the WIRE TRANSFER in the bank branch in USA.</p>

                </div>
            </div>
        </div>
    </div><br>

</div>

</template>

<script>
export default {
}
</script>

<style lang="css">
</style>
