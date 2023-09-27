/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//const { default: VueRouter } = require('vue-router');

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';
import Toast from 'vue-toastification';
import VCalendar from 'v-calendar';
import VueMask from 'v-mask';

import "vue-toastification/dist/index.css"
// Asumiendo que 'router' es el objeto Vue Router
import store from './store'; // Asumiendo que 'store' es el objeto Vuex Store

import MenuFlotanteComponent from './components/MenuFlotanteComponent.vue';
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('login-component', require('./components/LoginComponent.vue').default);
//Vue.component('dashboard-component', require('./components/DashboardComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VueRouter);
Vue.use(VCalendar);
Vue.use(VueMask);

const options = {
    transition: "Vue-Toastification_fade"
};

Vue.use(Toast, options);
localStorage.setItem('userAdmin' , 'jefe');

if (JSON.parse(localStorage.getItem("user")) !== null) {
    const empleadoId = JSON.parse(localStorage.getItem("user"));
    console.log(empleadoId)
    localStorage.setItem('userAdmin' , `${empleadoId.rol}`)
    axios
      .get(`empleadobyid?idEmpleado=${empleadoId.empleado_id}`)
      .then((result) => {
         console.log(result.data[0])
      })
      .catch((error) => {});
  }
//
const routes = [
    {
        path: '/',
        name: 'Login',
        component: require('./components/LoginComponent.vue').default,
        meta: { requiresAuth: false }
    },
    {
        path: '/dashboard',
        component: require('./components/DashboardComponent.vue').default,
        meta: { requiresAuth: true }
    },
    {
        path: '/business',
        component: require('./components/SelectBussinesComponent.vue').default,
        meta: { requiresAuth: true }
    },
    {
        path: '/registro',
        component: require('./components/RegistroUsuarioComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },
    {
        path: '/activacionusuario',
        component: require('./components/ActivacionUsuarioComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },
    {
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
    },
    {
        path: '/editarperfilusuario',
        component: require('./components/EditarPerfilUsuarioComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'jefe') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }

    },
    {
        path: '/menu',
        component: require('./components/MenuFlotanteComponent.vue').default,
        meta: { requiresAuth: true }
    },
    {
        path: '/historialhoras',
        component: require('./components/HistorialHorasComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },
    {
        path: '/importacionhoras',
        component: require('./components/CargaHorasComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'jefe') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },
    {
        path: '/calendarios',
        component: require('./components/CalendarioComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }

    },
    {
        path: '/empleados',
        component: require('./components/EmpleadosListComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    }
    ,
    {
        path: '/empresas',
        component: require('./components/EmpresasComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },
    {
        path: '/reporte',
        component: require('./components/ReportePersonaComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },
    {
        path: '/seleccionararchivo',
        component: require('./components/SeleccionCargarArchivo.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },
    {
        path: '/areas',
        component: require('./components/AreasComponent.vue').default,
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem('userAdmin') === 'administrador') {
              next(); // Permite el acceso si el usuario es administrador
            } else {
              next('/access-denied'); // Redirige a una página de acceso denegado si no es administrador
            }
          }
    },
    {
        path: '/access-denied',
        component: require('./components/Access-deniedComponent.vue').default,
        meta: { requiresAuth: true }
    }
]

const router = new VueRouter({
    routes: routes,
    mode: "hash"
})

Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    });
    return formatter.format(value);
});

const app = new Vue({
    render: h => h(MenuFlotanteComponent),
    store,
    router
}).$mount('#app');


router.beforeEach((to, from, next) => {
    if (to.matched.some(route => route.meta.requiresAuth)) {
        if (!store.state.loggedIn) { // Corregí el nombre de la propiedad a "loggedIn"
            next('/');
        } else {
            next();
            // return this.$store.getters.currentUser;
        }
    } else {
        next();
        //  return this.$store.getters.currentUser;
    }
});

