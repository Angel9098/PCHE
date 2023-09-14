<template>
  <div>
    <nav class="navbar navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand">
         <img class="wiHImg" width="40" height="40" src="assets/img/iconblancologo_Mesa de trabajo 1.png" alt="" srcset=""> 
          {{ nombres }}
        </a>
        <form class="d-flex">
          <button class="btn btn-primary text-uppercase btnGray" type="submit">
            Cerrar sesion
          </button>
        </form>
      </div>
    </nav>
    <div class="container-fluid   fixed-top rounded">
      <div class="row">
        <!-- Menú lateral -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-primary sidebar">
          <div class="position-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link text-uppercase" href="?#/dashboard"><i class="fa-solid fa-house  mr-3"></i> Inicio </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-uppercase" href="?#/"> <i class="fa-solid fa-circle-plus  mr-3"></i> registrar empleados</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-uppercase" href="?#/importacionhoras"> <i class="fa-solid fa-circle-plus  mr-3"></i> Agregar horas extras </a>
              </li>
              
              <li class="nav-item">
                <a class="nav-link text-uppercase" href="?#/editarperfilusuario"><i class="fa-solid fa-users  mr-3"></i> Perfil </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-uppercase" href="?#/calendarios"><i class="fa-solid fa-calendar"></i> Calendario</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-uppercase" href="?#/historialhoras"> <i class="fa-brands fa-searchengin mr-3"></i> Historial horas extras</a>
              </li>
              <!-- Agrega más opciones según sea necesario -->
            </ul>
          </div>
        </nav>
        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <!-- Aquí va el contenido de tu página -->
        </main>
      </div>
    </div>
  </div>
</template>

<style>
.row > * {
    flex-shrink: 0;
    width: auto;
    max-width: 100%;
    padding-right: calc(var(--bs-gutter-x) * 0.5);
    padding-left: calc(var(--bs-gutter-x) * 0.5);
    margin-top: var(--bs-gutter-y);
    color: white;
}
.nav-link {
    display: block;
    padding: var(--bs-nav-link-padding-y) var(--bs-nav-link-padding-x);
    font-size: var(--bs-nav-link-font-size);
    font-weight: var(--bs-nav-link-font-weight);
    color: white;
    text-decoration: none;
    background: none;
    border: 0;
    font-weight: bolder;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}
.active{
  background-color: rgba(255, 255, 255, 0.774) !important;
}
.nav-link:hover{
  background-color: rgba(255, 255, 255, 0.774);
  color: black;

}
.flex {
  display: flex;
  width: 100%;
  height: 50px;
  justify-items: center;
  align-items: center;
}

.flex:hover {
  background-color: rgb(255, 255, 255);
  width: 100%;
  color: black;
  font-weight: bold;
  border-radius: 10px;
  padding: 10px;
}

.btnGray {
  background-color: rgba(128, 128, 128, 0.774);
}

.fixed-top {
    position: fixed;
    top: 20%;
    right: 0;
    left: 0;
    z-index: 1030;
}
</style>

<script>
import axios from 'axios';
export default {
  data() {
    return { 
      nombre: ''
    };
  },
  mounted() {
    this.leerData();
  },
  methods: {
    //empleadobyid
   leerData(){
      const {id} = JSON.parse(localStorage.getItem("user"));
      axios.get(`empleadobyid?idEmpleado=${id}`).then((result) => {
       this.nombre =result.data[0].nombres
            }).catch(error => {})
    }
  },
};
</script>

