<template lang="html">

  <div class="">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">Create Antifraud Form</div>
                <div class="card-body">

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">Form type:</label>
                      </div>
                      <div class="col-md-6">
                        <select class="form-control" v-on:change="selectType" v-model="form_type" name="type" required>
                          <option value="SIMPLE_FORM">Simple Form</option>
                          <option value="INTERNATIONAL_TRANSFER">International Bank Transfer</option>
                          <option value="NATIONAL_TRANSFER">National Bank Transfer</option>
                          <option value="CASH_DEPOSIT">Cash Bank Deposit</option>
                          <option value="GIFT_CARD">Amazon - Ebay Gift Card</option>
                          <option value="VARO_MONEY">Varo Moeny</option>
                        </select>
                      </div>
                    </div><br>

                    <div v-if="form_type != 'SIMPLE_FORM'" class="row">
                      <div class="col-md-2">
                        <label for="visible">Contact ID:</label>
                      </div>
                      <div class="col-md-6">
                        <div class="input-group">
                          <input type="text" v-model="contact_id" class="form-control" value="" name="contact_id" required></label>
                        </div>
                      </div>
                    </div>

                </div>
            </div>
        </div>
    </div><br>

    <div v-if="url != ''" class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
              <div class="card-header">Form URL</div>
                <div class="card-body">

                    <div class="row">
                      <div class="col-md-2">
                        <label for="visible">URL:</label>
                      </div>
                      <div class="col-md-8">
                        <div class="input-group">
                          <input type="text" v-model="url" class="form-control" value="" name="contact_id" readonly></label>
                        </div>
                      </div>
                    </div><br>

                </div>
            </div>
        </div>
    </div><br>

    <div class="text-center">
      <button type="submit" v-on:click="createAntifraud" class="btn btn-default">Create URL</button>
      <a href="" class="btn" style="color:black">Cancel</a>
    </div>

  </div>

</template>

<script>
export default {

  data() {
    return {
      contact_id: '',
      url: '',
      form_type: 'INTERNATIONAL_TRANSFER'
    }
  },

  methods: {
    createAntifraud() {
      if (this.contact_id != '' || this.form_type == 'SIMPLE_FORM') {
        axios.get(window.location.origin+'/create-antifraud-url', {
          params: {
            contact: this.contact_id,
            type: this.form_type
          }
        }).then(re => {
          this.url = re.data;
        })
      }
    },

    selectType() {
      if (this.form_type == 'SIMPLE_FORM') {
        this.contact_id = '';
      }
    }
  }

}
</script>

<style lang="css">
</style>
