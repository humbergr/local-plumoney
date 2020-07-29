<template lang="html">
  <div class="col-md-6 px-lg-5 mb-4 mb-md-0">
      <vue-toastr ref="toastr"></vue-toastr>
      <form method="POST"  v-on:submit="loginFirstStep($event)" ref="login_form" :action="getUrl('/login')">
          <input type="hidden" name="_token" v-model="laravel_token">
          <h6 class="text-primary font-weight-bold mb-4">Ingresa si tienes una cuenta</h6>
          <div class="input-group mb-3">
              <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-envelope-o text-muted"></i>
                    </span>
              </div>
              <input type="email" name="email"
                     class="form-control" placeholder="Email" v-model="email" v-on:change="verify2F" required>
          </div>
          <div class="input-group mb-3">
              <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-lock text-muted"></i>
                    </span>
              </div>
              <input type="password" name="password" class="form-control"
                     placeholder="Contraseña" required>
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
          <div class="text-right mb-4">
              <a :href="getUrl('/forgotten-password')" class="text-secondary font-14">¿Olvido
                  su contraseña?</a>
          </div>
          <button type="submit" class="btn btn-outline-secondary btn-pill btn-block py-2">Ingresar
          </button>
      </form>
      <hr class="d-md-none">
  </div>
</template>

<script>
export default {
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
