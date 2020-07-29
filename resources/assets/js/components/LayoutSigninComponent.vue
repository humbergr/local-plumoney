<template lang="html">
  <div class="py-3 px-4">
      <vue-toastr ref="toastr"></vue-toastr>
      <form method="POST" v-on:submit="loginFirstStep($event)" ref="login_form" :action="getUrl('/login')">
          <input type="hidden" name="_token" v-model="laravel_token">
          <h6 class="text-primary font-weight-bold text-center mb-3">{{lang.login_account}}</h6>
          <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text">
                  <i class="fa fa-user-o text-muted"></i>
              </span>
              </div>
              <input type="email" name="email"
                     class="form-control"
                     placeholder="Email" v-model="email" v-on:change="verify2F">
          </div>
          <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text">
                  <i class="fa fa-lock text-muted"></i>
              </span>
              </div>
              <input type="password" name="password" class="form-control"
                     placeholder="Password">
          </div>
          <div v-if="google2fa" class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text">
                  <i class="fa fa-lock text-muted"></i>
              </span>
              </div>
              <input type="text" name="" class="form-control"
                     placeholder="2FA Code" v-model="google2f_code" required>
          </div>
          <div class="text-center">
              <button type="submit" class="btn btn-secondary btn-pill">{{lang.login}}</button>
          </div>
          <div class="clearfix mt-3">
              <a :href="getUrl('/signin')" class="text-secondary float-left small">{{lang.create_account}}</a>
              <a :href="getUrl('/forgotten-password')" class="text-secondary float-right small">{{lang.forgot_password}}</a>
          </div>
      </form>
  </div>
</template>

<script>
export default {
  props: ['lang'],

  data() {
    return {
      laravel_token: $('meta[name="csrf-token"]').attr("content"),
      email: '',
      google2fa: null,
      google2f_code: ''
    }
  },

  methods: {

    getUrl(endpoint) {
      return window.location.origin + endpoint;
    },

    verify2F(){
      axios.get(window.location.origin + '/verify-2fa', {
          params: {
              email: this.email,
          }
      })
      .then(
          re => {
              if (re.data !== null) {
                  this.google2fa = re.data;
              }
          }
      )
    },

    loginFirstStep(event){
      if (this.google2fa) {
        event.preventDefault();
        axios.get(window.location.origin + '/verify-2fa-code', {
            params: {
                email: this.email,
                code: this.google2f_code,
            }
        })
        .then(
            re => {
                if (re.data.valid) {
                  this.$refs.login_form.submit();
                } else {
                  this.$refs.toastr.e('El codigo de autenticacion a dos pasos es incorrecto.');
                }
            }
        )
      }
    }
  }
}
</script>

<style lang="css">
</style>
