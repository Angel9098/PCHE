<template>
  <div id="app">
    <nav class="navbar navbar-primary bg-primary position-static" :hidden="currentPath =='/' || currentPath =='/business'">
      <div class="container-fluid">
        <button class="navbar-toggler bg-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
          <img class="wiHImg" width="80" height="50" src="assets/img/logopngblanco_Mesa de trabajo 1.png" alt="">
        </a>
        <div class="offcanvas offcanvas-start text-bg-primary" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white" id="offcanvasDarkNavbarLabel">
              {{ nombre }}
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
              <li class="nav-item">
                <router-link class="nav-link active text-white" aria-current="page" to="/dashboard"><i class="fa-solid fa-house mr-3"></i> Inicio</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/registro"><i class="fa-solid fa-circle-plus  "></i> Registrar empleados</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/importacionhoras"><i class="fa-solid fa-file-arrow-up"></i> Agregar horas extras </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/editarperfilusuario"><i class="fa-solid fa-user "></i> Perfil </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/empleados"><i class="fa-solid fa-users"></i> Empleados</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/empresas"><i class="fa-solid fa-building"></i> Empresas</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/activacionusuario"><i class="fa-solid fa-user-check"></i> Activar Usuario</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/calendarios"><i class="fa-solid fa-calendar"></i> Calendario</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/historialhoras"><i class="fa-brands fa-searchengin "></i> Historial horas extras</router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link text-white text-uppercase"  to="/seleccionararchivo"><i class="fa-brands fa-file-circle-check"></i> Selecci&#243;n archivo</router-link>
              </li>
            </ul>
            <button @click="cerrarSesion" class="btn bg-black btn-primary text-uppercase btnGray" type="button">
              Cerrar sesion
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="d-flex flex-row">
        <nav class="flexMenu bg-primary" :hidden="currentPath == '/' || currentPath == '/business'">
            <ul class="ul">
                <li class="li my-4">
                    <router-link  class="my-4 text-uppercase"  to="/dashboard"><i class="fa-solid fa-house text-white"></i> </router-link>
                </li>
                <li class="li my-4">
                    <router-link  class="my-4 text-uppercase"  to="/importacionhoras"><i class="fa-solid fa-file-arrow-up text-white"></i> </router-link>
                </li>
                <li class="li my-4">
                  <router-link  class="my-4 text-uppercase"  to="/registro"><i class="fa-solid fa-circle-plus  text-white"></i> </router-link>
                </li>
                <li class="li my-4">
                    <router-link class="my-4 text-uppercase"  to="/editarperfilusuario"><i class="fa-solid fa-user  text-white"></i> </router-link>
                </li>
                <li class="li my-4">
                    <router-link class="my-4 text-uppercase"  to="/empleados"><i class="fa-solid fa-users text-white"></i></router-link>
                </li>
                <li class="li my-4">
                  <router-link class="my-4 text-uppercase"  to="/empresas"><i class="fa-solid fa-building text-white"></i></router-link>
                </li>
                <li class="li my-4">
                  <router-link class="my-4 text-uppercase"  to="/activacionusuario"><i class="fa-solid fa-user-check text-white"></i></router-link>
                </li>
                <li class="li my-4">
                    <router-link class="my-4 text-uppercase"  to="/calendarios"><i class="fa-solid fa-calendar text-white"></i> </router-link>
                </li>
                <li class="li my-4">
                    <router-link class="my-4 text-uppercase"  to="/historialhoras"><i class="fa-brands fa-searchengin text-white"></i> </router-link>
                </li>
                <li class="li my-4">
                    <router-link class="my-4 text-uppercase"  to="/seleccionararchivo"><i class="fa-solid fa-file-circle-check text-white"></i> </router-link>
                </li>
                <li class="li my-4">
                    <a class="my-4 text-uppercase" href=""><i class="fa-solid fa-right-from-bracket text-white" @click="cerrarSesion"></i> </a>
                </li>
            </ul>
        </nav>
        <main class="w-100 d-flex flex-row justify-content-center h-100">
          <router-view></router-view>
        </main>
    </div>
  </div>
</template>
<style>

  .flexMenu {
      margin: 0%;
      width: 40px;
      height: 100vh;
      display: grid;
      justify-content: center;
      align-items: center;
      z-index: 1200;
  }
  .ul .li {
      list-style: none;
  }
  ol, ul, dl {
      margin-top: 20px;
      margin-bottom: 1rem;
  }

  ol, ul {
      padding-left: 0rem;
  }

  a:hover{
    cursor: pointer;
  }

</style>
<script>
import axios from "axios";
export default {
  data() {
    return {
      nombre: "",
    };
  },
  mounted() {
    this.leerData();
  },
  computed: {
    currentPath() {
      return this.$route.path;
    }
  },
  methods: {
    //empleadobyid
    leerData() {
      const empleadoId = JSON.parse(localStorage.getItem("user")).empleado_id;
      axios .get(`empleadobyid?idEmpleado=${empleadoId}`)
      .then((result) => {
          this.nombre = result.data[0].nombres;
        }).catch((error) => {});
    },
    cerrarSesion(){
      localStorage.removeItem('user');
      localStorage.removeItem('empresaID');
      this.$store.dispatch('logout');
      this.$router.push('/');
    }
  },
};
</script>

