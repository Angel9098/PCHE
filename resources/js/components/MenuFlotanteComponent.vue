<template>
    <div id="app">
        <div class="w-100 d-flex flex-row justify-content-end positionNavBar">
            <nav class="w-100 navbar navbar-primary bg-primary" style="padding-top: 0px; padding-bottom: 0px;"
                :hidden="currentPath == '/' || currentPath == '/business' || currentPath == '/access-denied'">
                <div class="container-fluid" style="padding-left: 0px; padding-right: 0px;">
                    <a :class="expandSideBar ? 'toggle-btn-extend' : 'toggle-btn'" role="button" @click="toggleSideBar">
                        <i class="fa-solid fa-bars text-white" style="font-size: 25px; margin-top: 15px;"></i>
                    </a>
                    <a class="navbar-brand" href="#">
                        <img class="wiHImg" width="80" height="50" src="assets/img/logopngblanco_Mesa de trabajo 1.png"
                            alt="" />
                    </a>
                    <div class="offcanvas offcanvas-start text-bg-primary" tabindex="-1" id="offcanvasDarkNavbar"
                        aria-labelledby="offcanvasDarkNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title text-white" id="offcanvasDarkNavbarLabel">
                                <router-link class="navbar-brand" to="/dashboard">
                                    <img :src="imagen" alt="Logo" width="30" height="30" class="mx-1 cicle" />
                                    {{ changeIMG(this.$store.state.changeIMG) }}
                                    <strong class="text-white">{{ nombre }}</strong>
                                </router-link>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                                aria-label="Close" @click="toggleSideBar"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item">
                                    <router-link class="nav-link active text-white" aria-current="page" to="/dashboard">
                                        <i class="fa-solid fa-house mx-2"></i>
                                        Inicio
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'administrador'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/registro/0">
                                        <i class="fa-solid fa-circle-plus mx-2"></i>
                                        Registrar empleados
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'jefe'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/importacionhoras">
                                        <i class="fa-solid fa-file-arrow-up mx-2"></i>
                                        Agregar horas extras
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'jefe'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/editarperfilusuario">
                                        <i class="fa-solid fa-user mx-2"></i>
                                        Perfil usuario
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'administrador'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/empleados">
                                        <i class="fa-solid fa-users mx-2"></i>
                                        Empleados
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'administrador'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/empresas">
                                        <i class="fa-solid fa-building mx-2"></i>
                                        Empresas
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'administrador'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/areas">
                                        <i class="fa-solid fa-network-wired mx-2"></i>
                                        &#193;reas
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'administrador'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/activacionusuario">
                                        <i class="fa-solid fa-user-check mx-2"></i>
                                        Activar Usuario
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'administrador'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/calendarios">
                                        <i class="fa-solid fa-calendar mx-2"></i>
                                        Calendario
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'administrador'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/historialhoras">
                                        <i class="fa-brands fa-searchengin mx-2"></i>
                                        Historial horas extras
                                    </router-link>
                                </li>
                                <li v-if="userRoles === 'administrador'" class="nav-item">
                                    <router-link class="nav-link text-white text-uppercase" to="/seleccionararchivo">
                                        <i class="fa-solid fa-file mx-2"></i>
                                        Selecci&#243;n archivo
                                    </router-link>
                                </li>
                            </ul>
                            <button @click="cerrarSesion" class="btn bg-black btn-primary text-uppercase btnGray my-4"
                                type="button">
                                Cerrar sesi&#243;n
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <nav :class="expandSideBar ? 'flexMenu-extend bg-primary' :'flexMenu bg-primary'" :hidden="currentPath == '/' || currentPath == '/business'">
            <ul class="ul">
                <router-link :class="expandSideBar ? 'my-1 text-uppercase item-extend' :'my-1 text-uppercase item'" to="/dashboard" v-tooltip="{ theme: 'info-tooltip', content: 'Dashboard', disabled: expandSideBar }">
                    <i :class="expandSideBar ? 'fa-solid fa-house text-white iconItem-extend' : 'fa-solid fa-house text-white iconItem'"></i>
                    <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar ==true">Dashboard</span>
                </router-link>
                <router-link v-if="userRoles === 'jefe'" :class="expandSideBar ? 'my-1 text-uppercase item-extend' : 'my-1 text-uppercase item'" to="/importacionhoras" v-tooltip="{ theme: 'info-tooltip', content: 'Importar Horas', disabled: expandSideBar }">
                    <i :class="expandSideBar ? 'fa-solid fa-file-arrow-up text-white iconItem-extend' : 'fa-solid fa-file-arrow-up text-white iconItem'"></i>
                    <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Importar Horas</span>
                </router-link>
                <a :class="expandSideBar ?'item-extend' : 'item'" role="button" id="dropdownMenu2" data-bs-toggle="collapse" href="#subMenu" aria-expanded="false" @click="toggleSubMenu" aria-controls="subMenu" v-tooltip="{ theme: 'info-tooltip', content: 'Empleados', disabled: expandSideBar }">
                    <i :class="expandSideBar ? 'fa-solid fa-users-gear text-white iconItem-extend' : 'fa-solid fa-users-gear text-white iconItem'"></i>
                    <div class="expandible" v-if="expandSideBar" style="width:145px;text-align: center;">
                        <span class="text-white text-uppercase">Empleados</span>
                        <i class="fa-solid fa-caret-down text-white animate__animated animate__bounce"></i>
                    </div>
                </a>
                <transition name="expand">
                    <div class="submenu" id="subMenu" v-if="isSubMenuOpen">
                        <router-link v-if="userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/registro/0" v-tooltip="{ theme: 'info-tooltip-sub', content: 'Registrar Empleados', disabled: expandSideBar }">
                            <i :class="expandSideBar ? 'fa-solid fa-circle-plus text-white' : 'fa-solid fa-circle-plus text-white iconItem'"></i>
                            <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Registrar Empleados</span>
                            <!-- Registrar empleados -->
                        </router-link>
                        <router-link v-if="userRoles === 'jefe' || userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/editarperfilusuario" v-tooltip="{ theme: 'info-tooltip-sub', content: 'Perfil', disabled: expandSideBar }">
                            <i :class="expandSideBar ? 'fa-solid fa-user text-white' : 'fa-solid fa-user text-white iconItem'"></i>
                            <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Perfil</span>
                            <!-- Perfil usuario -->
                        </router-link>
                        <router-link v-if="userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/activacionusuario" v-tooltip="{ theme: 'info-tooltip-sub', content: 'Activar Usuario', disabled: expandSideBar }">
                            <i :class="expandSideBar ? 'fa-solid fa-user-check text-white' : 'fa-solid fa-user-check text-white iconItem'"></i>
                            <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Activar Usuario</span>
                            <!-- Activar Usuario -->
                        </router-link>
                        <router-link v-if="userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/empleados" v-tooltip="{ theme: 'info-tooltip-sub', content: 'Listado Empleados', disabled: expandSideBar }">
                            <i :class="expandSideBar ? 'fa-solid fa-users text-white' : 'fa-solid fa-users text-white iconItem'"></i>
                            <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Listado Empleados</span>
                            <!-- Empleados -->
                        </router-link>
                    </div>
                </transition>
                <router-link v-if="userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/empresas" v-tooltip="{ theme: 'info-tooltip', content: 'Empresas', disabled: expandSideBar }">
                    <i :class="expandSideBar ? 'fa-solid fa-building text-white' : 'fa-solid fa-building text-white iconItem'"></i>
                    <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Empresas</span>
                </router-link>
                <router-link v-if="userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/areas" v-tooltip="{ theme: 'info-tooltip', content: 'Áreas', disabled: expandSideBar }">
                    <i :class="expandSideBar ? 'fa-solid fa-network-wired text-white' : 'fa-solid fa-network-wired text-white iconItem'"></i>
                    <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Áreas</span>
                </router-link>
                <router-link v-if="userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/calendarios" v-tooltip="{ theme: 'info-tooltip', content: 'Calendario', disabled: expandSideBar }">
                    <i :class="expandSideBar ? 'fa-solid fa-calendar text-white' : 'fa-solid fa-calendar text-white iconItem'"></i>
                    <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Calendario</span>
                </router-link>
                <router-link v-if="userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/historialhoras" v-tooltip="{ theme: 'info-tooltip', content: 'Procesar Horas', disabled: expandSideBar }">
                    <i :class="expandSideBar ? 'fa-brands fa-searchengin text-white' : 'fa-brands fa-searchengin text-white iconItem'"></i>
                    <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Procesar Horas</span>
                </router-link>
                <router-link v-if="userRoles === 'administrador'" :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" to="/seleccionararchivo" v-tooltip="{ theme: 'info-tooltip', content: 'Cálculo Horas', disabled: expandSideBar }">
                    <i :class="expandSideBar ? 'fa-solid fa-file-circle-check text-white' : 'fa-solid fa-file-circle-check text-white iconItem'"></i>
                    <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Cálculo Horas</span>
                </router-link>
                <a :class="expandSideBar ? 'text-uppercase item-extend' : 'text-uppercase item'" href="" v-tooltip="{ theme: 'info-tooltip', content: 'Cerrar Sesión', disabled: expandSideBar }" @click="cerrarSesion">
                    <i :class="expandSideBar ? 'fa-solid fa-right-from-bracket text-white' : 'fa-solid fa-right-from-bracket text-white iconItem'"></i>
                    <span class="text-white" style="width:145px;text-align: center;" v-if="expandSideBar">Cerrar Sesión</span>
                </a>
            </ul>
        </nav>
        <div :class="currentPath == '/' || currentPath == '/business' || currentPath == '/access-denied' ? 'd-flex flex-row justify-content-center' : 'd-flex flex-row justify-content-end'">
            <main class="d-flex flex-row justify-content-center h-100" :style="currentPath == '/' || currentPath == '/business' || currentPath == '/access-denied' ? 'width:100%' : currentMenu">
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
    position: fixed;
}

.flexMenu-extend {
    margin: 0%;
    width: 200px;
    height: 100vh;
    display: grid;
    justify-content: center;
    align-items: center;
    z-index: 1026;
    position: fixed;
}

.item {
    display: list-item;
    height: 40px;
    text-align: center;
    width: 55px;
    list-style: none;
}

.item-extend {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    height: 40px;
    text-align: center;
    width: 200px;
    list-style: none;
}

.iconItem {
    margin-top: 13px;
}

.iconItem-extend {
    margin-top: 0px;
}

.toggle-btn:hover,
.item:hover, .toggle-btn-extend:hover, .item-extend:hover {
    background-color: #7DCADC;
}

.submenu {
    background-color: #102E47;
}

.expandible{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 5px;
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

.positionNavBar {
    position: sticky;
    overflow: hidden;
    top: 0px;
    z-index: 1000;
}

.toggle-btn {
    display: list-item;
    height: 60px;
    text-align: center;
    width: 55px;
    list-style: none;
}

.toggle-btn-extend {
    display: list-item;
    height: 60px;
    text-align: right;
    width: 200px;
    list-style: none;
    padding-right: 5px;
}

.v-popper--theme-info-tooltip .v-popper__inner {
    background-color: #1b4d76;
    color: #ffffff;
    padding: 10px;
    border-radius: 9px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.41);
}

.v-popper--theme-info-tooltip .v-popper__arrow-outer {
    border-color: #1b4d76;
}

.v-popper--theme-info-tooltip-sub .v-popper__inner {
    background-color: #102E47;
    color: #ffffff;
    padding: 10px;
    border-radius: 9px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.41);
}

.v-popper--theme-info-tooltip-sub .v-popper__arrow-outer {
    border-color: #102E47;
}

.expand-enter-active,
.expand-leave-active {
    /*transition: max-height 0.1s cubic-bezier(0.25, 0.1, 0.2, 0.25);*/
    transition: transform 0.9s ease-out;
}

.expand-enter,
.expand-leave-to {
    /*max-height: 0;
    overflow: hidden;*/
    transform: translateY(-20%);
    opacity: 0;
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
            isSubMenuOpen: false,
            expandSideBar: false
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
        currentMenu() {
            if (this.expandSideBar == true) return 'width:84%';
            else return 'width:97%';
        }
    },
    methods: {
        //empleadobyid
        leerData() {
            if (localStorage.getItem("user") !== null) {
                const { empleado_id, imagen } = JSON.parse(JSON.stringify(localStorage.getItem("user")));
                if (imagen !== null) {
                    this.defaultBooleand = false;
                    this.imagen = `storage/imagenes/${imagen}`;
                }
                if (localStorage.getItem('nombreUser') != null) {
                    this.nombre = JSON.parse(localStorage.getItem('nombreUser'));
                }
               /*  axios.get(`empleadobyid?idEmpleado=${empleado_id}`)
                    .then((result) => {
                        this.id = result.data.object[0].id;
                        this.nombre = `${result.data.object[0].nombres} ${result.data.object[0].apellidos}`;
                        

                    })
                    .catch((error) => { }); */
            } else if (localStorage.getItem("user") === null)
                return this.cerrarSesion();
        },
        cerrarSesion() {
            localStorage.removeItem("user");
            localStorage.removeItem("empresaID");
            localStorage.setItem("session", false);
            localStorage.removeItem('nombreUser');
            this.$store.dispatch("logouts");
        },

        changeIMG(img) {
            if (img === true) return this.leerData();
        },
        toggleSubMenu() {
            this.isSubMenuOpen = !this.isSubMenuOpen;
        },
        toggleSideBar() {
            this.expandSideBar = !this.expandSideBar;
        }
    },
};
</script>
