<template>
  <main class="container m-auto">
    <h1 class="text-center my-4">Actualización de perfil</h1>
    <article class="card">
      <div class="floatCenter">
        <h2 class="text-center my-3">Vista previa</h2>
        <picture class="mb-3">
          <source
            srcset="https://cdn.jdpower.com/Average%20Weight%20Of%20A%20Car.jpg"
            type="image"
          />
          <img
            src="https://cdn.jdpower.com/Average%20Weight%20Of%20A%20Car.jpg"
            class="img-fluid img-thumbnail cicle"
            alt="..."
          />
        </picture>

        <p class="font-weight-bold">NOMBRE DE PERFIL :</p>
        <p></p>
        <p class="font-weight-bold">CARGO O PUESTO :</p>
        <p></p>
        <p class="font-weight-bold">DESCRIPCION DEL PUESTO :</p>
        <p></p>
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
            ></textarea>
            <div class="invalid-feedback">
              Please enter a message in the textarea.
            </div>
          </div>
          <div class="floatCenter">
            <button type="button" class="btn btn-dark">GUARDAR CAMBIOS <i class="fa-solid fa-circle-plus"></i></button>
            <button type="button" class="btn btn-dark my-3 text-uppercase">Cambiar contraseña <i class="fa-solid fa-pen-to-square"></i></button>
          </div>
        </form>
      </div>
    </article>
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