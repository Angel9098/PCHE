

<template>
  <main class="container m-auto">
    <h1 class="text-center my-4">Actualización de perfil</h1>
    <article class="card">
      <div class="floatCenter">
        <h2 class="text-center my-3">Vista previa</h2>
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

        <p class="font-weight-bold">NOMBRE DE PERFIL :</p>
        <p>{{ perfil.nombres }}</p>
        <p class="font-weight-bold">CARGO O PUESTO :</p>
        <p>{{ perfil.cargo }}</p>
        <p class="font-weight-bold">DESCRIPCION DEL PUESTO :</p>
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
          <!-- CAMBIAR EL NOMBRE -->
          <div class="form-group">
            <label for="changeNombre">CAMBIAR EL NOMBRE</label>
            <input
              type="fiTEXTe"
              class="form-control"
              id="changeNombre"
              placeholder="CAMBIAR EL NOMBRE"
              v-model="perfil.nombres"
            />
          </div>
          <!-- CAMBIAR PUESTO O CARGO -->
          <div class="form-group">
            <label for="changePuesto">CAMBIAR PUESTO O CARGO</label>
            <input
              type="fiTEXTe"
              class="form-control"
              id="changePuesto"
              placeholder="CAMBIAR PUESTO O CARGO"
              v-model="perfil.cargo"
            />
          </div>
          <!-- DESCRIPCION DEL PUESTO -->
          <div class="mb-3">
            <label for="validationTextarea">DESCRIPCION DEL PUESTO</label>
            <textarea
              class="form-control is-invalid"
              id="validationTextarea"
              placeholder="Required example textarea"
              required
              v-model="perfil.description"
            ></textarea>
            <div class="invalid-feedback">
              Please enter a message in the textarea.
            </div>
          </div>
          <div class="floatCenter">
            <button
              @click="sendInfromation()"
              type="button"
              class="btn btn-dark"
            >
              GUARDAR CAMBIOS <i class="fa-solid fa-circle-plus"></i>
            </button>
            <button
            type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#exampleModal"
            >
              Cambiar contraseña <i class="fa-solid fa-pen-to-square"></i>
            </button>
          </div>
        </form>
      </div>
    </article>
    <!-- ****** -->

   <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nombre de persona</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
import ModalChangePassword from "./ModalChangePassword.vue";
export default {
  data() {
    return {
      defaultImagen:
        "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png",
      defaultBooleand: true,
      perfil: {
        nombres: "",
        imagen: "",
        cargo: "",
        description: "",
      },
    };
  },
  components: {
    ModalChangePassword,
  },
  methods: {
    changesDefauld(event) {
      this.defaultBooleand = false;
      const files  = event.target.files[0];
      this.perfil.imagen =URL.createObjectURL(files)
    },
    sendInfromation() {
      console.log(this.perfil);
    },
  },
};
</script>