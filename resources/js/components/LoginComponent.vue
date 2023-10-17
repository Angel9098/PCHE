<template>
    <div class="w-100 d-flex justify-content-center align-items-center" style="height: 90vh;">
        <div
            class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-sm-12 col-md-7 col-lg-7">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-25">
            <form class="w-50 d-flex flex-column gap-3">
                <div class="form-group">
                    <label for="user">Usuario</label>
                    <input type="text" class="form-control" id="user" placeholder="Escriba usuario"
                        aria-describedby="userHelp" v-model="objLogin.email" @keyup.enter="login">
                </div>
                <div class="form-group">
                    <label for="pass">Contraseña</label>
                    <input type="password" class="form-control" id="pass" placeholder="Escriba contraseña"
                        aria-describedby="passHelp" v-model="objLogin.password" @keyup.enter="login">
                </div>
                <div class="form-group d-flex flex-row justify-content-center">
                    <button type="button" class="btn btn-primary d-flex flex-row justify-content-center gap-1" @click="login">
                        <div v-if="isSubmit" class="spinner-border text-white spinner-load" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div><span>Iniciar sesión</span></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            objLogin: { email: '', password: '' },
            isSubmit: false,
            nombre: ''
        };
    },
    methods: {
        async login() {
            if (!this.objLogin.email) {
                this.showToast('Ingrese usuario');
                return;
            }
            if (!this.objLogin.password) {
                this.showToast('Ingrese contraseña');
                return;
            }
            this.isSubmit = true;
            axios.post(`/login`, this.objLogin).then((response) => {
                this.isSubmit = false;
                const user = response.data.object;
                localStorage.setItem('user', JSON.stringify(user));
                localStorage.setItem("userAdmin", user.rol);
                this.$store.dispatch('logins', user);
                this.$router.push('/business');

                axios.get(`empleadobyid?idEmpleado=${user.empleado_id}`).then((result) => {
                    this.nombre = result.data.object[0].nombres + ' ' + result.data.object[0].apellidos;
                    localStorage.setItem('nombreUser', JSON.stringify(this.nombre));
                }).catch(error => { });
                
            }).catch((error) => {
                this.isSubmit = false;
                if (error.response.data.status === 401) {
                    this.showToast('Credenciales incorrectas. Intente de nuevo');
                } else {
                    console.log(error)
                    this.showToast('Error en el servidor. Por favor, inténtelo de nuevo más tarde');
                }
            });
        },
        showToast(message) {
            this.$toast.error(message, {
                timeout: 3000,
                position: 'bottom-center',
                icon: true,
            });
        },
    },
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity .5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.effect {
    filter: opacity(.5);
}

.paddingOff {
    padding-left: 0px;
    padding-right: 0px;
}

.spinner-load{
    font-size: 10px;
    width: 20px;
    height: 20px;
}
</style>
