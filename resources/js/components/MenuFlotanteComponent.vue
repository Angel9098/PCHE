<template>
  <div>
    <nav class="navbar navbar-primary bg-primary fixed-top">
      <div class="container-fluid">
       
        <button
          class="navbar-toggler bg-white"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasDarkNavbar"
          aria-controls="offcanvasDarkNavbar"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
          <img
            class="wiHImg"
            width="80"
            height="50"
            src="assets/img/logopngblanco_Mesa de trabajo 1.png"
            alt=""
            srcset=""
        /></a>
        <div
          class="offcanvas offcanvas-start text-bg-primary"
          tabindex="-1"
          id="offcanvasDarkNavbar"
          aria-labelledby="offcanvasDarkNavbarLabel"
        >
          <div class="offcanvas-header">
            <h5
              class="offcanvas-title text-white"
              id="offcanvasDarkNavbarLabel"
            >
              {{ nombre }}
            </h5>
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            ></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
              <li class="nav-item">
                <a
                  class="nav-link active text-white"
                  aria-current="page"
                  href="?#/dashboard"
                  ><i class="fa-solid fa-house mr-3"></i> Inicio</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link text-white text-uppercase"  href="?#/registro"><i class="fa-solid fa-circle-plus  "></i> registrar empleados</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white text-uppercase"  href="?#/importacionhoras"><i class="fa-solid fa-circle-plus  "></i> Agregar horas extras </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white text-uppercase"  href="?#/editarperfilusuario"><i class="fa-solid fa-user "></i> Perfil </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white text-uppercase"  href="?#/empleados"><i class="fa-solid fa-users"></i> Empleados</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white text-uppercase"  href="?#/calendarios"><i class="fa-solid fa-calendar"></i> Calendario</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-white text-uppercase"  href="?#/historialhoras"><i class="fa-brands fa-searchengin "></i> Historial horas extras</a>
              </li>
              <!-- <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle "
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Dropdown
              </a>
              <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li>
                  <a class="dropdown-item" href="#">Something else here</a>
                </li>
              </ul>
            </li> -->
            </ul>
            <button
            @click="cerrarSesion"
              class="btn bg-black btn-primary text-uppercase btnGray"
              type="submit"
            >
              Cerrar sesion
            </button>
          </div>
        </div>
      </div>
    </nav>
    <div>
        <div>
            <nav class="flexMenu bg-primary">
            <ul class="ul">
                <li class="li my-4">
                    <a  class="my-4 text-uppercase"  href="?#/dashboard"><i class="fa-solid fa-house text-white"></i> </a>
                </li>
                <li class="li my-4">
                    <a class="my-4 text-uppercase"  href="?#/registro"><i class="fa-solid fa-circle-plus  text-white"></i> </a>
                </li>
                <li class="li my-4">
                    <a  class="my-4 text-uppercase"  href="?#/importacionhoras"><i class="fa-solid fa-circle-plus  text-white"></i> </a>
                </li>
                <li class="li my-4">
                    <a class="my-4 text-uppercase"  href="?#/editarperfilusuario"><i class="fa-solid fa-user  text-white"></i> </a>
                </li>
                <li class="li my-4">
                    <a class="my-4 text-uppercase"  href="?#/empleados"><i class="fa-solid fa-users text-white"></i></a>
                </li>
                <li class="li my-4">
                    <a class="my-4 text-uppercase"  href="?#/calendarios"><i class="fa-solid fa-calendar text-white"></i> </a>
                </li>
                <li class="li my-4">
                    <a class="my-4 text-uppercase"  href="?#/historialhoras"><i class="fa-brands fa-searchengin text-white"></i> </a>
                </li>
                <li class="li my-4">
                    <a class="my-4 text-uppercase" @click="cerrarSesion" ><i class="fa-regular fa-circle-xmark text-white"></i> </a>
                </li>
            </ul>
        </nav>
        </div>
        <div>

        </div>
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
  methods: {
    //empleadobyid
    leerData() {
      const { empleado_id } = JSON.parse(localStorage.getItem("user"));
      axios
        .get(`empleadobyid?idEmpleado=${empleado_id}`)
        .then((result) => {
          this.nombre = result.data[0].nombres;
        })
        .catch((error) => {});
    },
    cerrarSesion(){
                localStorage.removeItem('user');
                this.$router.push('/');
            }
  },
};
</script>

