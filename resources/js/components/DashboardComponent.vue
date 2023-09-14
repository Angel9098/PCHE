<template>
    <div>
        <menu-flotante-component-vue></menu-flotante-component-vue>
     <main class="container">
                
         <h1 class="text-center my-4">Bienvenido  {{ nombre }}</h1>
     </main>
    </div>

</template>
<script>
import MenuFlotanteComponentVue from './MenuFlotanteComponent.vue';
    export default {
        data() {
            return {
                usuario: {},
                nombre: ''
            }
        },
        mounted() {
    this.leerData();

            if(localStorage.getItem('user') !== null){
                this.usuario = JSON.parse(localStorage.getItem('user'));
            }
        },
        methods: {
            cerrarSesion(){
                localStorage.removeItem('user');
                this.$router.push('/');
            },
            leerData(){
      const {id} = JSON.parse(localStorage.getItem("user"));
      axios.get(`empleadobyid?idEmpleado=${id}`).then((result) => {
       this.nombre =result.data[0].nombres
            }).catch(error => {})
    }
        },
        components:{
            MenuFlotanteComponentVue
        }
    }
</script>