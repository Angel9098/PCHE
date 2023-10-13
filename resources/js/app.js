require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router';
import Toast from 'vue-toastification';
import VCalendar from 'v-calendar';
import VueMask from 'v-mask';
import "vue-toastification/dist/index.css"
import moment from 'moment-timezone';
import VueApexCharts from 'vue-apexcharts';
import Vuelidate from 'vuelidate';
import FloatingVue from 'floating-vue';
import 'floating-vue/dist/style.css';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'animate.css';

import store from './store'; // Agregando estados 
import VueSimpleAlert from "vue-simple-alert";
import MenuFlotanteComponent from './components/MenuFlotanteComponent.vue';
Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.use(VueRouter);
Vue.use(VCalendar);
Vue.use(VueMask);
Vue.use(VueApexCharts);
Vue.use(VueSweetalert2);
Vue.use(Vuelidate);
Vue.component('apexchart', VueApexCharts);
Vue.use(FloatingVue, {
    themes: {
        'info-tooltip': {
            '$extend': 'tooltip',
            '$resetCss': true,
            triggers: ['hover'],
            autoHide: true,
            placement: 'right',
            distance: 10
        },
        'info-tooltip-sub': {
            '$extend': 'tooltip',
            '$resetCss': true,
            triggers: ['hover'],
            autoHide: true,
            placement: 'right',
            distance: 10
        }
    }
});


Vue.use(VueSimpleAlert);


moment.tz.setDefault("America/El_Salvador");

const options = {
    transition: "Vue-Toastification_fade",
};

Vue.use(Toast, options);
localStorage.setItem("userAdmin", "jefe");

if (JSON.parse(localStorage.getItem("user")) !== null) {
    const empleadoId = JSON.parse(localStorage.getItem("user"));
    localStorage.setItem("userAdmin", `${empleadoId.rol}`);
}
//
const routes = [
    {
        path: "/",
        name: "Login",
        component: require("./components/LoginComponent.vue").default,
        meta: { requiresAuth: false },
    },
    {
        path: "/dashboard",
        component: require("./components/DashboardComponent.vue").default,
        meta: { requiresAuth: true },
    },
    {
        path: "/business",
        component: require("./components/SelectBussinesComponent.vue").default,
        meta: { requiresAuth: true },
    },
    {
        path: "/registro/:id",
        component: require("./components/RegistroUsuarioComponent.vue").default,
        meta: { requiresAuth: true },
        props: true,
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/activacionusuario",
        component: require("./components/ActivacionUsuarioComponent.vue")
            .default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    /* {
        path: '/editarperfil',
        component: require('./components/EditarPerfilComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },*/
    {
        path: "/editarperfilusuario",
        component: require("./components/EditarPerfilUsuarioComponent.vue")
            .default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (
                localStorage.getItem("userAdmin") === "jefe" ||
                localStorage.getItem("userAdmin") === "administrador"
            ) {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/historialhoras",
        component: require("./components/HistorialHorasComponent.vue").default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/importacionhoras",
        component: require("./components/CargaHorasComponent.vue").default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "jefe") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/calendarios",
        component: require("./components/CalendarioComponent.vue").default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/empleados",
        component: require("./components/EmpleadosListComponent.vue").default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/empresas",
        component: require("./components/EmpresasComponent.vue").default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/reporte",
        component: require("./components/ReportePersonaComponent.vue").default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/seleccionararchivo",
        component: require("./components/SeleccionCargarArchivo.vue").default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/areas",
        component: require("./components/AreasComponent.vue").default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("userAdmin") === "administrador") {
                next(); // Permite el acceso si el usuario es administrador
            } else {
                next("/access-denied"); // Redirige a una página de acceso denegado si no es administrador
            }
        },
    },
    {
        path: "/access-denied",
        component: require("./components/Access-deniedComponent.vue").default,
        meta: { requiresAuth: true },
    },
];

const router = new VueRouter({
    routes: routes,
    mode: "hash",
});

Vue.filter("toCurrency", function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    });
    return formatter.format(value);
});

const app = new Vue({
    render: (h) => h(MenuFlotanteComponent),
    store,
    router,
}).$mount("#app");

router.beforeEach((to, from, next) => {
    if (to.matched.some((route) => route.meta.requiresAuth)) {
        if (!localStorage.getItem("session")) {
            // Corregí el nombre de la propiedad a "loggedIn"
            next("/");
        } else {
            next();
            // return this.$store.getters.currentUser;
        }
    } else {
        next();
        //  return this.$store.getters.currentUser;
    }
});
