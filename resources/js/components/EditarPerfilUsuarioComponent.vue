

<template>
  <main class="container m-auto">
    <h1 class="text-center my-4 text-uppercase">Actualización de pérfil</h1>
    <article class="card">
      <div class="floatCenter">
        <picture class="mb-3">
          <source
            :srcset="defaultBooleand ? defaultImagen : perfil.imagen"
            type="image"
          />
          <img
            :src="defaultBooleand ? defaultImagen : perfil.imagen"
            class="img-fluid img-thumbnail cicle"
            alt="..."
          />
        </picture>

        <p class="font-weight-bold">NOMBRE DE PÉRFIL :</p>
        <p>{{ perfil.nombres }}</p>
        <p class="font-weight-bold">CARGO O PUESTO :</p>
        <p>{{ perfil.cargo }}</p>
        <p class="font-weight-bold">DESCRIPCIÓN DEL PUESTO :</p>
        <p>{{ perfil.description }}</p>
      </div>
      <div>
        <form class="formContent">
          <!-- CAMBIAR LA IMAGEN -->
          <div class="form-group">
            <label for="changeIMG">CAMBIAR LA IMAGEN</label>
            <input
              type="file"
              class="form-control"
              id="changeIMG"
              placeholder="CAMBIAR LA IMAGEN"
              @change="changesDefauld"
            />
          </div>

          <div class="form-group">
            <label for="changePuesto" class="text-uppercase">Correo electrónico</label>
            <input
              type="fiTEXTe"
              class="form-control"
              id="changePuesto"
              placeholder="example@gmil.com"
              v-model="perfil.email"
              readonly
            />
          </div>
          <!-- CAMBIAR EL NOMBRE -->
          <div class="form-group">
            <label for="changeNombre" class="text-uppercase">contacto de emergencia</label>
            <input
              type="fiTEXTe"
              class="form-control"
              id="changeNombre"
              placeholder="Contacto de emergencia"
              v-model="perfil.nombre_persona"
              readonly
            />
          </div>
          <!-- CAMBIAR PUESTO O CARGO -->
          <div class="form-group">
            <label for="changePuesto" class="text-uppercase">Número de emergencia</label>
            <input
              type="fiTEXTe"
              class="form-control"
              id="changePuesto"
              placeholder="(503) 7854 6985"
              v-model="perfil.numero_emergencia"
              readonly
            />
          </div>
          <!-- DESCRIPCION DEL PUESTO -->
          
          <div class="floatCenter">
            <button
              @click="sendInfromation()"
              type="button"
              class="btn btn-dark mt-4"
            >
              GUARDAR CAMBIOS <i class="fa-solid fa-circle-plus"></i>
            </button>
            <button
              type="button"
              class="btn btn-primary my-3"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal"
            >
              Cambiar contraseña <i class="fa-solid fa-pen-to-square"></i>
            </button>
          </div>
        </form>
      </div>
    </article>
    <!-- ****** -->

    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Nombre de persona
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <modal-change-password></modal-change-password>
          </div>
        </div>
      </div>
    </div>
    <!-- ******* -->
  </main>
</template>

<style scoped>
.card {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
}
.cicle {
  width: 200px;
  height: 200px;
  border-radius: 50%;
}
.floatCenter {
  display: flex;
  flex-direction: column;
  justify-items: center;
  align-items: center;
  border-right: 3px solid rgba(0, 0, 0, 0.726);
}
.formContent {
  margin: 10%;
}
</style>
<script>
import axios from "axios";

import ModalChangePassword from "./ModalChangePassword.vue";
export default {
  data() {
    return {
      defaultImagen:
        "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png",
      defaultBooleand: true,
      file: File,
      perfil: {
        email: "",
        imagen: "",
        numero_emergencia: "",
        nombre_persona: "",
      },
    };
  },
  components: {
    ModalChangePassword,
  },
  methods: {
    changesDefauld(event) {
      this.defaultBooleand = false;
      this.file = event.target.files[0];
      
      const files = event.target.files[0];
      this.perfil.imagen = URL.createObjectURL(files);
    },
    sendInfromation() {
      console.log(this.file)
        const sendFiles = new FormData();
        sendFiles.append('imagen', this.file,this.file.name);

        axios.post(
        "/editarusuario",
         sendFiles 
      )
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
      
      // return new  Promise.all();
    },
  },
};
</script>