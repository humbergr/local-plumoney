<template lang="html">

<div class="">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">Payment Data</div>
                <div class="card-body">

                  <div class="row">
                    <div class="col-md-2">
                      <label for="visible">Card Type</label>
                    </div>
                    <div class="col-md-6">
                      <select class="form-control" v-model="card_type" name="" required>
                        <option value="Digital Card">Digital Card</option>
                        <option value="Card">Card</option>
                      </select>
                    </div>
                  </div><br>

                  <div v-if="card_type == 'Digital Card'" class="row">
                    <div class="col-md-2">
                      <label for="visible">Card Code</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="text" class="form-control" value="" name="payment[card_code]" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div v-if="card_type == 'Card'" class="row">
                    <div class="col-md-2">
                      <label for="visible">Card Photo</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="file" class="" value="" name="gift-card-photo" required></label>
                      </div>
                    </div>
                  </div><br>

                  <div v-if="card_type == 'Card'" class="row">
                    <div class="col-md-2">
                      <label for="visible">Invoice Picture</label>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <input type="file" class="" value="" name="invoice-picture" required></label>
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

                  <p>- The codes must be valid only for USA.</p>

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
      card_type: 'Digital Card'
    }
  },
}
</script>

<style lang="css">
</style>
