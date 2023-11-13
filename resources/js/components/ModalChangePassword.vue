
<template>
  <form>
    <div class="form-group">
      <label for="InputActual">Contraseña actual</label>
      <input
        type="password"
        class="form-control"
        id="InputActual"
        aria-describedby="emailHelp"
        required
        v-model="changesPassword.oldPassword"
      />
      <small id="emailHelp" class="form-text text-muted"
        ></small
      >
    </div>
    <div class="form-group">
      <label for="InputPasswordNueva">Nueva contraseña</label>
      <input v-model="changesPassword.newPassword" type="password" class="form-control" id="InputPasswordNueva" required />
    </div>
    <div class="form-group">
      <label for="InputPasswordRepita">Repita la contraseña</label>
      <input v-model="changesPassword.confirmPassword" type="password" class="form-control" id="InputPasswordRepita" required />
    </div>
    <button @click="sendPassword()" type="button" class="btn btn-dark my-4">
      Actualizar credenciales
    </button>
  </form>
</template>

  <script>
import axios from "axios";
export default {
  data() {
    return {
      changesPassword: {
        idUsuario: "",
        oldPassword: "",
        newPassword: "",
        confirmPassword: "",
      },
    };
  },
  methods: {
    sendPassword() {
        if (this.changesPassword.newPassword != this.changesPassword.confirmPassword) {
        this.$swal.fire({
            title: "¡Error!",
            icon: "info",
            text: "Contraseñas no coinciden",
            showCancelButton: false,
            showConfirmButton: false,
            timer: 2000,
          });
      }
      else if (this.changesPassword.oldPassword === this.changesPassword.newPassword) {
        this.$swal.fire({
            title: "¡Error!",
            icon: "info",
            text: "Contraseña actual es igual a nueva contraseña",
            showCancelButton: false,
            showConfirmButton: false,
            timer: 2000,
          });
      }
      else {
          const { id } = JSON.parse(localStorage.getItem("user"));
          this.changesPassword.idUsuario = id;
          axios.post('/actualizarcontra', this.changesPassword).then((response) => {
            if (response.status == 201) {
              this.changesPassword.newPassword = '';
              this.changesPassword.confirmPassword = '';
              this.changesPassword.oldPassword = '';
              this.$swal.fire({
                title: "¡Hecho!",
                icon: "success",
                text: response.data.message,
                showCancelButton: false,
                showConfirmButton: false,
                timer: 2000,
              });
              $('#exampleModal').modal('hide');
            }
          }).catch((error) => {
            if (error.response.status == 400) {
              this.$swal.fire({
                title: "¡Error!",
                icon: "error",
                text: error.response.data.message,
                showCancelButton: false,
                showConfirmButton: false,
                timer: 2000,
              });
            }
          });
      }
    },
  },
};
</script>
