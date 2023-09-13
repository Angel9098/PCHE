<template>
    <div>
        <menu-flotante-component-vue></menu-flotante-component-vue>
     <main class="container">
         <h1 class="text-center my-4">Bienvenido</h1>
     </main>
    </div>

</template>
<script>
import MenuFlotanteComponentVue from './MenuFlotanteComponent.vue';
    export default {
        data() {
            return {
                usuario: {}
            }
        },
        mounted() {
            if(localStorage.getItem('user') !== null){
                this.usuario = JSON.parse(localStorage.getItem('user'));
            }
        },
        methods: {
            cerrarSesion(){
                localStorage.removeItem('user');
                this.$router.push('/');
            }
        },
        components:{
            MenuFlotanteComponentVue
        }
    }
</script>