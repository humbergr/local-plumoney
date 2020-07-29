<template lang="html">

<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card card-default">
            <div class="card-header">Contact Chat</div>
              <div class="card-body loader--wrapper" :class="{ '--loading': loader }">

                <div class="" ref="scroller" style="width: 100%; height: 300px; overflow-y: auto;">
                  <div v-for="msg in messages" class="container">
                      <h5>{{msg.sender.username}}:</h5>
                      <p>{{msg.msg}}</p>
                      <div v-if="msg.attachment_name" class="">
                        <a :href="getAttachmentUrl(msg.attachment_name)" target="_blank">
                          <div class="">
                            {{msg.attachment_name}}
                          </div>
                        </a>
                      </div>
                      <span class="time-right">{{getDate(msg.created_at)}}</span>
                      <hr>
                  </div>
                </div>

                <div class="loader">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

              </div>
            </div>
        </div>
    </div><br>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-body loader--wrapper" :class="{ '--loading': loader }">
                  <div class="row">
                    <div class="col-md-1">
                      <label for="visible">Message:</label>
                    </div>
                    <div class="col-md-8">
                      <textarea class="form-control" v-model="message" name="name" rows="4" cols="80"></textarea>
                      <input type="file" v-on:change="fileHandler" ref="file" name="" value="">
                    </div>
                    <div class="col-md-3">
                      <div class="text-center">
                        <button type="button" v-on:click="sendMessage" class="btn btn-secondary">Send Message</button>
                      </div>
                    </div>
                  </div><br>

                  <div class="loader">
                      <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                          <span class="sr-only">Loading...</span>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </div><br>
</div>

</template>

<script>
export default {
  props: ['contact_id'],

  data() {
    return {
      messages: {},
      loader: true,
      message: '',
      file: ''
    }
  },

  methods: {
    getAttachmentUrl(name){
      return window.location.origin+'/img/from-lcb-files/'+name;
    },

    getDate(date){
      var newDate = new Date(date);
      return (newDate.getMonth() + 1) + '/' + newDate.getDate() + '/' + newDate.getFullYear();
    },

    fileHandler(){
      this.file = this.$refs.file.files[0];
    },

    sendMessage(){
      this.loader = true;
      let formData = new FormData();
      formData.append('file', this.file);
      axios.post(window.location.origin+'/api/send-message/'+this.contact_id, formData, {
        params: {
          message: this.message,
          _token: $('meta[name="csrf-token"]').attr("content")
        }
      })
      .then(re => {
        this.getMessages();
      })
    },

    getMessages(){
      this.loader = true;
      axios.get(window.location.origin+'/api/contact-messages/'+this.contact_id)
      .then(re => {
        this.messages = re.data;
        this.message = '';
        this.loader = false;
        var container = this.$refs.scroller;
        container.scrollTop = container.scrollHeight;
      })
    }
  },

  mounted(){
    this.getMessages();
  }
}
</script>

<style lang="css">
</style>
