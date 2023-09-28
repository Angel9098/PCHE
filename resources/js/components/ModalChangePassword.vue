
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
        v-model="changesPassowrd.oldPassword"
      />
      <small id="emailHelp" class="form-text text-muted"
        >We'll never share your email with anyone else.</small
      >
    </div>
    <div class="form-group">
      <label for="InputPasswordNueva">Nueva contraseña</label>
      <input v-model="changesPassowrd.newPassword" type="password" class="form-control" id="InputPasswordNueva" required />
    </div>
    <div class="form-group">
      <label for="InputPasswordRepita">Repita la contraseña</label>
      <input v-model="changesPassowrd.confirmPassword" type="password" class="form-control" id="InputPasswordRepita" required />
    </div>
    <button @click="sendPassword()" type="submit" class="btn btn-dark my-4">
      Actualizar credenciales
    </button>
  </form>
</template>

  <script>
import axios from "axios";
export default {
  data() {
    return {
      changesPassowrd: {
        idUsuario: "",
        oldPassword: "",
        newPassword: "",
        confirmPassword: "",
      },
    };
  },
  methods: {
    sendPassword() {
    const {id} = JSON.parse(localStorage.getItem("user"));
    this.changesPassowrd.idUsuario = id;
      axios.post('/actualizarcontra',this.changesPassowrd).then((response)=>{
        console.log(response)
       }).catch((error) => {
        console.log(error)
       })
    },
  },
};
</script>
