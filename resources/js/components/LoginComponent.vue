<template>
  <div class="w-100 d-flex justify-content-center align-items-center" style="margin-top: 15%;">
      <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-sm-12 col-md-7 col-lg-7">
          <img src="assets/img/latinMobile.png" alt="logo" class="w-25">
          <form class="w-50 d-flex flex-column gap-3">
              <div class="form-group">
                  <label for="user">Usuario</label>
                  <input type="text" class="form-control" id="user" placeholder="Escriba usuario" aria-describedby="userHelp" v-model="objLogin.email" @keyup.enter="login">
              </div>
              <div class="form-group">
                  <label for="pass">Contraseña</label>
                  <input type="password" class="form-control" id="pass" placeholder="Escriba contraseña" aria-describedby="passHelp" v-model="objLogin.password" @keyup.enter="login">
              </div>
              <div class="form-group d-flex flex-row justify-content-center">
                  <button type="button" class="btn btn-primary" @click="login">
                      Iniciar sesión
                  </button>
              </div>
          </form>
      </div>
  </div>
</template>
  <script>
  import axios from 'axios';
    export default {
    data() {
      return {
        objLogin: { email: '', password: '' },
      };
    },
    methods: {
      async login() {
        if (!this.objLogin.email) {
          this.showToast('Ingrese usuario');
          return;
        }
          if (!this.objLogin.password) {
            this.showToast('Ingrese contraseña');
            return;
        }
  
        try {
          const response = await axios.post('login', this.objLogin, { headers: { 'Content-type': 'application/json' } });
          const user = response.data.Usuario;
  
          localStorage.setItem('user', JSON.stringify(user));
          this.$store.dispatch('logins', user);
          this.$router.push('/business');
        } catch (error) {
          if (error.response && error.response.status === 401) {
            this.showToast('Credenciales incorrectas. Intente de nuevo');
          } else {
            console.log(error)
            this.showToast('Error en el servidor. Por favor, inténtelo de nuevo más tarde');
          }
        }
      },
      showToast(message) {
        this.$toast.error(message, {
          timeout: 3000,
          position: 'bottom-center',
          icon: true,
        });
      },
    },
  };
  </script>
  
  <style>

     .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to{
        opacity: 0;
    }

    .effect{
        filter: opacity(.5);
    }
    
    .paddingOff{
        padding-left: 0px;
        padding-right: 0px;
    }
  </style>
  