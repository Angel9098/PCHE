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
//Vue.component('login-component', require('./components/LoginComponent.vue').default);
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

const routes = [
    {
        path: '/',
        component: require('./components/LoginComponent.vue').default
    },
    {
        path: '/dashboard',
        component: require('./components/DashboardComponent.vue').default
    },
    {
        path: '/business',
        component: require('./components/SelectBussinesComponent.vue').default
    },
    {
        path: '/registro',
        component: require('./components/RegistroUsuarioComponent.vue').default
    },
    {
        path: '/activacionusuario',
        component: require('./components/ActivacionUsuarioComponent.vue').default
    },
    {
        path: '/editarperfil',
        component: require('./components/EditarPerfilComponent.vue').default
    },
    {
        path: '/editarperfilusuario',
        component: require('./components/EditarPerfilUsuarioComponent.vue').default
    },
    {
        path: '/menu',
        component: require('./components/MenuFlotanteComponent.vue').default
    },
    {
        path: '/historialhoras',
        component: require('./components/HistorialHorasComponent.vue').default
    },
    {
        path: '/importacionhoras',
        component: require('./components/CargaHorasComponent.vue').default
    },
    {
        path: '/calendarios',
        component: require('./components/CalendarioComponent.vue').default
    },
    {
        path: '/empleados',
        component: require('./components/EmpleadosListComponent.vue').default
    }
    ,
    {
        path: '/empresas',
        component: require('./components/EmpresasComponent.vue').default
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
    router
}).$mount('#app');

// router.beforeEach((to,from,next) => {

// })
