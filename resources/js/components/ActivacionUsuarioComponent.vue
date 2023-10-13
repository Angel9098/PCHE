<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12"
        style="margin-bottom: 5%;">
        <!-- Imagen centrada -->
        <div class="center-image d-flex flex-column justify-content-center align-items-center" style="margin-bottom: 3%">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-75" />
        </div>
        <form class="col-8 col-sm-12 col-md-12 d-flex flex-column justify-content-center">
            <div class="col-12 d-flex flex-column justify-content-center">
                <h2 class="text-center" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); margin-bottom: 3%;">ACTIVACIÓN DE USUARIO</h2>

                <!-- Campo para ingresar el DUI del empleado -->
                <div class="d-flex flex-row justify-content-center align-items-center" style="margin-bottom: 5%;">
                    <div class="col-2 d-flex flex-row justify-content-start">
                        <label for="duiEmpleado" style="font-weight: bold">DUI DE EMPLEADO</label>
                    </div>
                    <div class="col-6 mx-1">
                        <input v-model="duiEmpleado" type="text" id="duiEmpleado" class="form-control"
                            placeholder="Ingrese DUI de empleado" v-mask="'########-#'">
                        <input v-model="idEmpleado" type="text" id="idEmpleado" class="form-control" hidden>
                    </div>
                    <div class="col-2 d-flex flex-row justify-content-start">
                        <button @click="buscarEmpleado" class="btn btn-primary" type="button">Buscar</button>
                    </div>
                </div>

                <div class="d-flex flex-row justify-content-center gap-2" style="margin-bottom: 2%;">
                    <div class="col-4">
                        <div class="d-flex flex-row align-items-center">
                            <div class="col-3">
                                <label for="nombres">Nombres</label>
                            </div>
                            <div class="col-9">
                                <input v-model="nombres" type="text" id="nombres" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-row align-items-center">
                            <div class="col-3">
                                <label for="apellidosEmpleado">Apellidos</label>
                            </div>
                            <div class="col-9">
                                <input v-model="apellidosEmpleado" type="text" id="apellidosEmpleado" class="form-control"
                                    disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-row justify-content-center gap-2" style="margin-bottom: 2%;">
                    <div class="col-4">
                        <div class="d-flex flex-row align-items-center">
                            <div class="col-3">
                                <label for="empresa">Empresa</label>
                            </div>
                            <div class="col-9">
                                <input v-model="empresa" type="text" id="empresa" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-row align-items-center">
                            <div class="col-3">
                                <label for="cargo">Cargo</label>
                            </div>
                            <div class="col-9">
                                <input v-model="cargo" type="text" id="cargo" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-row justify-content-center gap-2" style="margin-bottom: 2%;">
                    <div class="col-4">
                        <div class="d-flex flex-row align-items-center">
                            <div class="col-3">
                                <label for="correo">Correo</label>
                            </div>
                            <div class="col-9">
                                <input v-model="correo" type="text" id="correo" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-row align-items-center">
                            <div class="col-3">
                                <label for="correo">Rol</label>
                            </div>
                            <div class="col-9">
                                <select v-model="rol" class="form-select">
                                    <option value="administrador">Administrador</option>
                                    <option value="jefe">Jefe</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex flex-row justify-content-center" style="margin-bottom: 2%;">
                    <div class="form-check form-check-inline align-items-center">
                        <input v-model="activarUsuario" type="checkbox" class="form-check-input" id="activarUsuario" />
                        <label class="form-check-label" for="activarUsuario">Activar Usuario</label>
                    </div>
                </div>

                <!-- Campo para ingresar la contraseña temporal -->
                <div class="d-flex flex-row justify-content-center align-items-center" style="margin-bottom: 2%;">
                    <div class="col-4">
                        <label for="contrasenia" style="font-weight: bold">Contraseña temporal</label>
                    </div>
                    <div class="col-4">
                        <input v-model="contrasenia" type="text" id="contrasenia" class="form-control"
                            placeholder="Ingrese contraseña temporal" :disabled="!activarUsuario">
                    </div>
                </div>

                <!-- Botones de Guardar y Cancelar -->
                <div class="col-12 d-flex justify-content-center">
                    <button @click="guardar" style="margin-right: 2%;" class="btn btn-primary" type="button"
                        :disabled="!activarUsuario">Guardar</button>
                    <button @click="cancelar" class="btn btn-secondary" type="button">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios'; // Importa Axios
export default {
    data() {
        return {
            empleado: null,
            duiEmpleado: "",
            contrasenia: "",
            nombres: "", // Agrega estas propiedades
            apellidosEmpleado: "",
            empresa: "",
            cargo: "",
            correo: "",
            idEmpleado: "",
            activarUsuario: false,
            rol: ""
        };
    },

    methods: {

        buscarEmpleado() {
            if (this.duiEmpleado) {
                axios
                    .get(`/empleado_dui?dui=${this.duiEmpleado}`)
                    .then((response) => {
                        this.empleado = response.data.object;
                        if (response.data.status == 200) {
                            this.nombres = this.empleado.nombres;
                            this.apellidosEmpleado = this.empleado.apellidos;
                            this.empresa = this.empleado.area.empresa.nombre;
                            this.cargo = this.empleado.cargo;
                            this.correo = this.empleado.email;
                            this.idEmpleado = this.empleado.id;
                            this.rol = this.empleado.rol;
                        }
                    })
                    .catch((error) => {
                        if (error.response && error.response.data.status == 400) {
                            this.nombres = null;
                            this.apellidosEmpleado = null;
                            this.empresa = null;
                            this.cargo = null;
                            this.correo = null;
                            this.idEmpleado = null;
                            this.rol = null;
                            this.$swal.fire({
                                title: 'Error',
                                icon: 'error',
                                text: error.response.data.message,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 2000,

                            })

                        }

                    });
            }
        },
        guardar() {
            if (this.contrasenia == '') {
                this.$swal.fire({
                    title: 'Error',
                    icon: 'warning',
                    text: 'Debe ingresar una contraseña para el empleado',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                if (!this.duiEmpleado == '' && !this.nombres == '' && !this.apellidosEmpleado == '' && !this.correo == '') {
                    const datosRegistro = {
                        idEmpleado: this.idEmpleado,
                        dui: this.duiEmpleado,
                        nombres: this.nombres,
                        apellidos: this.apellidosEmpleado,
                        empresa: this.empresa,
                        cargo: this.cargo,
                        correo: this.correo,
                        contrasenia: this.contrasenia,
                        rol: this.rol,
                    };

                    axios.post('/registrarse', datosRegistro)
                        .then((response) => {
                            this.$swal.fire({
                                title: 'Exito',
                                icon: 'success',
                                text: response.data.message,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 2000
                            })
                            this.nombres = null;
                            this.duiEmpleado = null;
                            this.contrasenia = null;
                            this.idEmpleado = null;
                            this.apellidosEmpleado = null;
                            this.empresa = null;
                            this.cargo = null;
                            this.correo = null;
                            this.idEmpleado = null
                            this.activarUsuario = false;
                            this.rol = null;

                        })
                        .catch((error) => {
                            this.$swal.fire({
                                title: 'Error',
                                icon: 'error',
                                text: error.data.message,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 2000
                            })
                        });
                } else {
                    this.$swal.fire({
                        title: 'Error',
                        icon: 'warning',
                        text: 'Debe agregar un empleado',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    })
                }

            }
        },

        cancelar() {
            this.nombres = null;
            this.duiEmpleado = null;
            this.contrasenia = null;
            this.idEmpleado = null;
            this.apellidosEmpleado = null;
            this.empresa = null;
            this.cargo = null;
            this.correo = null;
            this.idEmpleado = null
            this.activarUsuario = false;
            this.rol = null;
        },
    },
};
</script>
<style>
.swal2-popup {
    background-color: #143c5ef4;
    color: white;
}

.swal2-title {
    color: white;
}

.swal2-content {
    color: white;
}
</style>
