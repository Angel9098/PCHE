<template>
    <div id="app">
        <nav
            class="navbar navbar-primary bg-primary position-static"
            :hidden="
                currentPath == '/' ||
                currentPath == '/business' ||
                currentPath == '/access-denied'
            "
        >
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
                    />
                </a>
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
                            <router-link class="navbar-brand" to="/dashboard">
                                <img
                                    :src="imagen"
                                    alt="Logo"
                                    width="30"
                                    height="30"
                                    class="mx-1 cicle"
                                />
                                {{ changeIMG(this.$store.state.changeIMG) }}
                                <strong class="text-white">{{ nombre }}</strong>
                            </router-link>
                        </h5>
                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul
                            class="navbar-nav justify-content-end flex-grow-1 pe-3"
                        >
                            <li class="nav-item">
                                <router-link
                                    class="nav-link active text-white"
                                    aria-current="page"
                                    to="/dashboard"
                                    ><i class="fa-solid fa-house mx-2"></i>
                                    Inicio</router-link
                                >
                            </li>
                            <li
                                v-if="userRoles === 'administrador'"
                                class="nav-item"
                            >
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/registro/0"
                                    ><i
                                        class="fa-solid fa-circle-plus mx-2"
                                    ></i>
                                    Registrar empleados</router-link
                                >
                            </li>
                            <li v-if="userRoles === 'jefe'" class="nav-item">
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/importacionhoras"
                                    ><i
                                        class="fa-solid fa-file-arrow-up mx-2"
                                    ></i>
                                    Agregar horas extras
                                </router-link>
                            </li>
                            <li v-if="userRoles === 'jefe'" class="nav-item">
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/editarperfilusuario"
                                    ><i class="fa-solid fa-user mx-2"></i>
                                    Perfil usuario
                                </router-link>
                            </li>
                            <li
                                v-if="userRoles === 'administrador'"
                                class="nav-item"
                            >
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/empleados"
                                    ><i class="fa-solid fa-users mx-2"></i>
                                    Empleados</router-link
                                >
                            </li>
                            <li
                                v-if="userRoles === 'administrador'"
                                class="nav-item"
                            >
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/empresas"
                                    ><i class="fa-solid fa-building mx-2"></i>
                                    Empresas</router-link
                                >
                            </li>
                            <li
                                v-if="userRoles === 'administrador'"
                                class="nav-item"
                            >
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/areas"
                                    ><i
                                        class="fa-solid fa-network-wired mx-2"
                                    ></i>
                                    &#193;reas</router-link
                                >
                            </li>
                            <li
                                v-if="userRoles === 'administrador'"
                                class="nav-item"
                            >
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/activacionusuario"
                                    ><i class="fa-solid fa-user-check mx-2"></i>
                                    Activar Usuario</router-link
                                >
                            </li>
                            <li
                                v-if="userRoles === 'administrador'"
                                class="nav-item"
                            >
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/calendarios"
                                    ><i class="fa-solid fa-calendar mx-2"></i>
                                    Calendario</router-link
                                >
                            </li>
                            <li
                                v-if="userRoles === 'administrador'"
                                class="nav-item"
                            >
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/historialhoras"
                                    ><i
                                        class="fa-brands fa-searchengin mx-2"
                                    ></i>
                                    Historial horas extras</router-link
                                >
                            </li>
                            <li
                                v-if="userRoles === 'administrador'"
                                class="nav-item"
                            >
                                <router-link
                                    class="nav-link text-white text-uppercase"
                                    to="/seleccionararchivo"
                                    ><i class="fa-solid fa-file mx-2"></i>
                                    Selecci&#243;n archivo</router-link
                                >
                            </li>
                        </ul>
                        <button
                            @click="cerrarSesion"
                            class="btn bg-black btn-primary text-uppercase btnGray my-4"
                            type="button"
                        >
                            Cerrar sesion
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="d-flex flex-row h-full">
            <nav
                class="flexMenu bg-primary"
                :hidden="currentPath == '/' || currentPath == '/business'"
            >
                <ul class="ul">
                    <!-- v-if="userRoles === 'administrador'" -->
                    <li class="li my-4">
                        <button
                            class="btn btn-primary text-center"
                            type="button"
                        >
                            <router-link
                                class="my-4 text-uppercase"
                                to="/dashboard"
                                ><i class="fa-solid fa-house text-white"></i>
                            </router-link>
                        </button>
                    </li>
                    <li v-if="userRoles === 'jefe'" class="li my-4">
                        <button
                            class="btn btn-primary text-center"
                            type="button"
                        >
                            <router-link
                                class="my-4 text-uppercase"
                                to="/importacionhoras"
                                ><i
                                    class="fa-solid fa-file-arrow-up text-white"
                                ></i>
                            </router-link>
                        </button>
                    </li>
                    <li class="li my-4">
                        <div class="dropdown">
                            <button
                                class="btn btn-primary dropdown-toggle"
                                type="button"
                                id="dropdownMenu2"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <div
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenu2"
                            >
                                <button
                                    v-if="userRoles === 'administrador'"
                                    class="dropdown-item"
                                    type="button"
                                >
                                    <router-link
                                        class="my-4 text-uppercase link-underline-light"
                                        to="/registro/0"
                                        ><i
                                            class="fa-solid fa-circle-plus text-black mx-2"
                                        ></i>
                                        Registrar empleados
                                    </router-link>
                                </button>
                                <button
                                    v-if="
                                        userRoles === 'jefe' ||
                                        userRoles === 'administrador'
                                    "
                                    class="dropdown-item"
                                    type="button"
                                >
                                    <router-link
                                        class="my-4 text-uppercase link-underline-light"
                                        to="/editarperfilusuario"
                                        ><i
                                            class="fa-solid fa-user text-black mx-2"
                                        ></i>
                                        Perfil usuario
                                    </router-link>
                                </button>

                                <button
                                    v-if="userRoles === 'administrador'"
                                    class="dropdown-item"
                                    type="button"
                                >
                                    <router-link
                                        class="my-4 text-uppercase link-underline-light"
                                        to="/activacionusuario"
                                        ><i
                                            class="fa-solid fa-user-check text-black mx-2"
                                        ></i>
                                        Activar Usuario
                                    </router-link>
                                </button>
                                <button
                                    v-if="userRoles === 'administrador'"
                                    class="dropdown-item"
                                    type="button"
                                >
                                    <router-link
                                        class="my-4 text-uppercase link-underline-light"
                                        to="/empleados"
                                        ><i
                                            class="fa-solid fa-users text-black mx-2"
                                        ></i>
                                        Empleados</router-link
                                    >
                                </button>
                            </div>
                        </div>
                    </li>

                    <li v-if="userRoles === 'administrador'" class="li my-4">
                        <button
                            class="btn btn-primary text-center"
                            type="button"
                        >
                            <router-link
                                class="my-4 text-uppercase"
                                to="/empresas"
                                ><i class="fa-solid fa-building text-white"></i
                            ></router-link>
                        </button>
                    </li>
                    <li v-if="userRoles === 'administrador'" class="li my-4">
                        <button
                            class="btn btn-primary text-center"
                            type="button"
                        >
                            <router-link class="my-4 text-uppercase" to="/areas"
                                ><i
                                    class="fa-solid fa-network-wired text-white"
                                ></i
                            ></router-link>
                        </button>
                    </li>

                    <li v-if="userRoles === 'administrador'" class="li my-4">
                        <button
                            class="btn btn-primary text-center"
                            type="button"
                        >
                            <router-link
                                class="my-4 text-uppercase"
                                to="/calendarios"
                                ><i class="fa-solid fa-calendar text-white"></i>
                            </router-link>
                        </button>
                    </li>
                    <li v-if="userRoles === 'administrador'" class="li my-4">
                        <button
                            class="btn btn-primary text-center"
                            type="button"
                        >
                            <router-link
                                class="my-4 text-uppercase"
                                to="/historialhoras"
                                ><i
                                    class="fa-brands fa-searchengin text-white"
                                ></i>
                            </router-link>
                        </button>
                    </li>
                    <li v-if="userRoles === 'administrador'" class="li my-4">
                        <button
                            class="btn btn-primary text-center"
                            type="button"
                        >
                            <router-link
                                class="my-4 text-uppercase"
                                to="/seleccionararchivo"
                                ><i
                                    class="fa-solid fa-file-circle-check text-white"
                                ></i>
                            </router-link>
                        </button>
                    </li>

                    <li class="li my-4">
                        <button
                            class="btn btn-primary text-center"
                            type="button"
                        >
                            <a class="my-4 text-uppercase" href=""
                                ><i
                                    class="fa-solid fa-right-from-bracket text-white"
                                    @click="cerrarSesion"
                                ></i>
                            </a>
                        </button>
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
.cicle {
    border-radius: 50%;
}
.flexMenu {
    margin: 0%;
    width: 55px;
    height: 100vh;
    display: grid;
    justify-content: center;
    align-items: center;
    z-index: 1026;
}

.ul .li {
    list-style: none;
}

ol,
ul,
dl {
    margin-top: 20px;
    margin-bottom: 1rem;
}

ol,
ul {
    padding-left: 0rem;
}
.h-full {
    height: 100vh !important;
}
a:hover {
    cursor: pointer;
}
.nav-link:hover {
    background-color: #c3c6c89a;
    border-radius: 5px;
}
.navbar-brand:hover {
    background-color: #c3c6c869;
    border-radius: 5px;
}
.dropdown-item:hover {
    background-color: #c3c6c89a;
    text-decoration: none !important;
}
</style>
<script>
import axios from "axios";
export default {
    data() {
        return {
            id: "0",
            nombre: "",
            imagen: "storage/imagenes/blank-profile-picture-973460_1280.webp",
        };
    },
    mounted() {
        this.leerData();
    },
    computed: {
        currentPath() {
            return this.$route.path;
        },
        userRoles() {
            this.leerData();
            if (this.$store.state.userRol !== null) {
                return this.$store.state.userRol;
            } else {
                return localStorage.getItem("userAdmin");
            }
        },
    },
    methods: {
        //empleadobyid
        leerData() {
            if (JSON.parse(localStorage.getItem("user")) !== null) {
                const { empleado_id, imagen } = JSON.parse(
                    localStorage.getItem("user")
                );
                if (imagen !== null) {
                    this.defaultBooleand = false;
                    this.imagen = `storage/imagenes/${imagen}`;
                }
                axios
                    .get(`empleadobyid?idEmpleado=${empleado_id}`)
                    .then((result) => {
                        this.id = result.data[0].id;
                        this.nombre = `${result.data[0].nombres} ${result.data[0].apellidos}`;
                    })
                    .catch((error) => {});
            } else if (localStorage.getItem("user") === null)
                return this.cerrarSesion();
        },
        cerrarSesion() {
            localStorage.removeItem("user");
            localStorage.removeItem("empresaID");
            this.$store.dispatch("logout");
            localStorage.setItem("session", false);
            localStorage.clear();
            this.$router.push("/");
        },

        changeIMG(img) {
            if (img === true) return this.leerData();
        },
    },
};
</script>
