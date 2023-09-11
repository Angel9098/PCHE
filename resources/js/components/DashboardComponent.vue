<template>
    <div>
        <nav class="navbar navbar-dark bg-primary justify-content-between">
            <a class="navbar-brand">PCHE</a>
            <div class="form-inline">
                <button class="btn btn-outline-light" type="button" @click="cerrarSesion">Cerrar sesión</button>
            </div>
        </nav>
        <h2 class="text-center">Bienvenido {{ usuario.nombres }} {{ usuario.apellidos }}</h2>
    </div>
</template>
<script>
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
        }
    }
</script>