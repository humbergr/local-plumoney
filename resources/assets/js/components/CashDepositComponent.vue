<template lang="html">

<div class="">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">Payment Data</div>
                <div class="card-body">

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Teller Business Card</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="file" class="" value="" name="teller-business-card" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Bank Deposit Photo</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="file" class="" value="" name="bank-deposit-photo" required></label>
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

                  <p>- We do not accept payments through: Check, Cashier Check, Money Order, Wire Transfer, ACH.</p>
                  <p>- We only accept CASH PAYMENT.</p>
                  <p>- If the user has less than 30 operations completed or low reputation, BTCs will be released after 24 hours.</p>

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
