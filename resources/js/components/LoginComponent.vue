<template>
    <div class="w-100 d-flex justify-content-center align-items-center" style="margin-top: 15%;">
<!--         <div class="d-flex flex-column col-5 paddingOff" style="background-color: #1b4d76;">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100 effect" src="assets/img/banner4.jpg" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 effect" src="assets/img/banner5.jpg" alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div> -->
        <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-sm-12 col-md-7 col-lg-7">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-25">
            <form class="w-50 d-flex flex-column gap-3">
                <div class="form-group">
                    <label for="user">Usuario</label>
                    <input type="text" class="form-control" id="user" placeholder="Escriba usuario" aria-describedby="userHelp" v-model="objLogin.email" @keyup.enter="Ingresar">
                </div>
                <div class="form-group">
                    <label for="pass">Contraseña</label>
                    <input type="password" class="form-control" id="pass" placeholder="Escriba contraseña" aria-describedby="passHelp" v-model="objLogin.password" @keyup.enter="Ingresar">
                </div>
                <div class="form-group d-flex flex-row justify-content-center">
                    <button type="button" class="btn btn-primary" @click="Ingresar">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>

import axios from 'axios'
    export default {
        data() {
            return {
                objLogin: { email: '', password: '' },
            
            }
        },
        mounted() {

        },
        computed: {
            anchoPantalla() {
                return window.screen.width;
            }
        },
        methods: {
            Ingresar(){
                if(this.objLogin.email == ''){
                    this.$toast.error('Ingrese Usuario',{
                        timeout: 3000,
                        position: 'bottom-center',
                        icon: true
                    });
                } else if(this.objLogin.password == ''){
                    this.$toast.error('Ingrese Contraseña',{
                        timeout: 3000,
                        position: 'bottom-center',
                        icon: true
                    });
                } else{
                    axios.post('login', this.objLogin, { headers: {'Content-type': 'application/json' }}).then(response=>{
                        if(response.status === 200){
                            localStorage.setItem('user',JSON.stringify(response.data.Usuario));
                            this.$router.push('/business')
                        }
                    }).catch(error=>{
                        if(error.response.status === 401){
                            this.$toast.error(error.response.data.message,{
                                timeout: 3000,
                                position: 'bottom-center',
                                icon: true
                            })
                        }
                    })
                }
            }
        }
    }
</script>
<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to{
        opacity: 0;
    }

    .effect{
        filter: opacity(.5);
    }
    
    .paddingOff{
        padding-left: 0px;
        padding-right: 0px;
    }
</style>