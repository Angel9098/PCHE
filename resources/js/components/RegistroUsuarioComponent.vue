<template>
    <div class="w-100 d-flex justify-content-center">
        <div class="col-9 mt-3">
            <h1 v-if="id != 0" class="text-center h1 text-uppercase" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5)">
                Modificaci&#243;n de Empleado
            </h1>
            <h1 v-else class="text-center h1 text-uppercase" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5)">
                Registro de Empleado
            </h1>
            <div class="card mt-3 mb-3 borderCircle bg-white">
                <div class="card-body">
                    <form @submit.prevent="registrar">
                        <h4 class="alert-heading">Datos generales</h4>
                        <hr />
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-5">
                                <label for="name">Nombres</label>
                                <input v-model="$v.usuario.nombres.$model" type="text" id="nombres" class="form-control" required autocomplete="off">
                                <span v-if="$v.usuario.nombres.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="last_name">Apellidos</label>
                                <input id="last_name" type="text" class="form-control" v-model="$v.usuario.apellidos.$model" required autocomplete="off">
                                <span v-if="$v.usuario.apellidos.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-5">
                                <label for="job_title">DUI</label>
                                <input id="job_title" type="text" class="form-control" v-model="$v.usuario.dui.$model" required v-mask="'########-#'" @change="verifyDui" autocomplete="off">
                                <span v-if="$v.usuario.dui.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                                <span v-if="verifyDuiEmailorBoolean" class="text-danger">
                                    {{ MSErrorDui }}
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="email">Correo Electr&#243;nico</label>
                                <input id="email" type="email" class="form-control" v-model="$v.usuario.email.$model" required @keydown="verifyDomain" autocomplete="off">

                                <span v-if="verifyEmailorDuiBoolean" class="text-danger">
                                    {{ MSErrorEmailODui }}
                                </span>
                                <span v-if="verifyEmailBoolean" class="text-danger">
                                    {{ MSErrorEmail }}
                                </span>
                                <span v-if="$v.usuario.email.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                        </div>
                        <h4 class="alert-heading pt-4">
                            En caso de emergencia contactar a
                        </h4>
                        <hr />
                        <div class="d-flex flex-row justify-content-between gap-2 mt-2">
                            <div class="form-group col-5">
                                <label for="nameEmergency">Nombre completo</label>
                                <input id="nameEmergency" type="text" class="form-control" v-model="$v.usuario.avisar_contacto.$model" required autocomplete="off">
                                <span v-if="$v.usuario.avisar_contacto.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="phoneEmergency">Tel&#233;fono</label>
                                <input id="phoneEmergency" type="text" class="form-control" v-model="$v.usuario.numero_emergencia.$model" required v-mask="'####-####'" autocomplete="off">
                                <span v-if="$v.usuario.numero_emergencia.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                        </div>

                        <h4 class="alert-heading pt-4">Empresa</h4>
                        <hr />
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-5">
                                <label for="company">Empresa</label>
                                <select id="company" class="form-select" v-model="$v.usuario.empresa.$model" required @change="getAreas">
                                    <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                                        {{ empresa.nombre }}
                                    </option>
                                </select>
                                <span v-if="$v.usuario.empresa.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="area">&#193;rea</label>
                                <select id="area" class="form-select" v-model="$v.usuario.area_id.$model" @change="getHorarios" required :disabled="usuario.empresa == ''">
                                    <option v-if="areas.length === 0" :disabled="true">
                                        Sin elementos disponibles
                                    </option>
                                    <option v-for="a in areas" :key="a.id" :value="a.id">
                                        {{ a.nombre }}
                                    </option>
                                </select>
                                <span v-if="$v.usuario.area_id.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="form-group col-5">
                                <label for="job_title">Cargo</label>
                                <input id="job_title" type="text" class="form-control" v-model="$v.usuario.cargo.$model" required autocomplete="off">
                                <span v-if="$v.usuario.cargo.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label for="salary">Salario</label>
                                <input id="salary" type="text" class="form-control" v-model="$v.usuario.salario.$model" required autocomplete="off">
                                <span v-if="$v.usuario.salario.$error" class="text-danger">
                                    Este campo es obligatorio
                                </span>
                            </div>
                        </div>

                        <h4 class="alert-heading pt-4">
                            Selecci&#243;n horario
                        </h4>
                        <hr />
                        <div class="d-flex flex-row justify-content-start">
                            <div class="form-group col-4">
                                <label for="hora">Turno</label>
                                <select id="hora" class="form-select" v-model="turno" required :placeholder="'Seleccione turno'" @change="filterHorario">
                                    <option :value="'all'">Todos</option>
                                    <option :value="'diurno'">Diurno</option>
                                    <option :value="'nocturno'">
                                        Nocturno
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-row col-12 mt-2">
                            <table class="table table-hover table-sm table-bordered">
                                <thead class="text-center table-primary">
                                    <tr>
                                        <th scope="col">Selecci&#243;n</th>
                                        <th scope="col">Horario</th>
                                        <th scope="col">Horas por semana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="horario in horariosFilter" :key="horario.id">
                                        <th scope="row">
                                            <div class="form-check d-flex flex-row justify-content-center">
                                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" :value="horario.id" @change="selectHorario(horario.id)" :checked="horario.id === usuario.horario_id">
                                            </div>
                                        </th>
                                        <td>{{ horario.descripcion }}</td>
                                        <td class="text-center">
                                            {{ horario.horas_semana }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-12 d-flex flex-row justify-content-start">
                            <button type="submit" :disabled="$v.$invalid" class="btn btn-primary">
                                Guardar
                            </button>
                            <button type="button" class="btn btn-light mx-3" @click="cancelar">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<style></style>
<script>
import axios from "axios";
import { required, email } from "vuelidate/lib/validators";

export default {
    props: ["id"],
    data() {
        return {
            MSErrorDui: "",
            MSErrorEmailODui: "",
            MSErrorEmail:
                "Por favor, ten en cuenta que solo se permiten los siguientes dominios para esta aplicación: example@latmobile.com, example@sdsalgroup.com. Asegúrate de utilizar uno de estos dominios al registrar o ingresar.",
            usuario: {
                nombres: "",
                apellidos: "",
                email: "",
                area_id: 0,
                cargo: "",
                empresa: "",
                dui: "",
                id: "",
                horario_id: 0,
                avisar_contacto: "",
                numero_emergencia: "",
                salario: 0,
            },
            empresas: [],
            areas: [],
            horarios: [],
            horariosFilter: [],
            turno: "all",
            verifyEmailBoolean: false,
            verifyEmailorDuiBoolean: false,
            verifyDuiEmailorBoolean: false
        };
    },
    validations: {
        usuario: {
            nombres: { required },
            apellidos: { required },
            email: { required, email },
            area_id: { required },
            cargo: { required },
            empresa: { required },
            dui: { required },
            horario_id: { required },
            avisar_contacto: { required },
            numero_emergencia: { required },
            salario: { required },
            // Agrega aquí las validaciones para los demás campos del objeto 'usuario' si es necesario.
        },
    },
    mounted() {
        this.getEmpresas();
        this.getHorarios();
        this.userId(this.id);
        document.title = `PCHE - Registro de usuario`;
    },
    methods: {
        registrar() {
            this.verifyDomain();
            this.verifyDui();
            if (this.$v.usuario.$invalid || this.verifyEmailBoolean || this.verifyDuiEmailorBoolean || this.usuario.area_id == 0 || this.usuario.empresa == 0 || this.usuario.horario_id == 0) {
                this.$swal.fire({
                    title: "Error",
                    icon: "info",
                    text: "Datos inválidos",
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 2000,
                });
                return;
            } else {
                this.$swal.fire({
                    title: 'Cargando...',
                    html: '<div class="my-5 d-flex flex-row justify-content-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div></div>',
                    showCancelButton: false,
                    showConfirmButton: false
                });
                if (this.usuario.id != "") {
                    axios.post("empleados/actualizar", this.usuario, {headers: { "Content-type": "application/json" },})
                        .then((response) => {
                            this.$swal.close();
                            this.$swal.fire({
                                title: "¡Hecho!",
                                icon: "success",
                                text: response.data.message,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 2000,
                            });
                            this.$router.push("/empleados");
                        });
                } else {
                    axios.post("empleados/crear", this.usuario, { headers: { "Content-type": "application/json" },})
                        .then((response) => {
                            this.$swal.close();
                            this.$swal.fire({
                                title: "¡Hecho!",
                                icon: "success",
                                text: response.data.message,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            this.$router.push("/empleados");
                        });
                }
            }
        },
        verifyDui() {
            this.verifyDuiAsync(this.usuario.dui);
        },
        verifyDomain() {
            if ((this.usuario.email === "")) {
                this.verifyEmailBoolean = false;

               this.verifyEmailorDuiBoolean = false;
            } else {
                const dominio = this.usuario.email.split("@")[1];
                const regex = /^(latmobile\.com|sdsalgroup\.com)$/;
                if (regex.test(dominio)) {
                    this.verifyEmailBoolean = false;
                    this.verifyEmail(this.usuario.email);
                } else {
                    this.verifyEmailBoolean = true;
                    this.MSErrorEmail = 'Correo electrónico inválido';
                }
            }
        },
        getEmpresas() {
            axios
                .get("empresas", {
                    headers: { "Content-type": "application/json" },
                })
                .then((resp) => {
                    this.empresas = resp.data;
                });
        },
        getAreas() {
            axios
                .get(`empresa/areas?id=${this.usuario.empresa}`, {
                    headers: { "Content-type": "application/json" },
                })
                .then((resp) => {
                    this.areas = resp.data.object;
                });
        },
        getHorarios() {
            axios
                .get(`horarios`, {
                    headers: { "Content-type": "application/json" },
                })
                .then((resp) => {
                    this.horarios = resp.data;
                    this.horariosFilter = resp.data;
                });
        },
        userId(id) {
            if (id != 0) {
                this.$swal.fire({
                    title: 'Cargando...',
                    html: '<div class="my-5 d-flex flex-row justify-content-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div></div>',
                    showCancelButton: false,
                    showConfirmButton: false
                });
                axios.get(`empleadobyid?idEmpleado=${id}`).then((result) => {
                    this.$swal.close();
                    this.usuario.nombres = result.data.object[0].nombres;
                    this.usuario.empresa = result.data.object[0].area.empresa.id;
                    this.usuario.area_id = result.data.object[0].area.id;
                    this.usuario.apellidos = result.data.object[0].apellidos;
                    this.usuario.email = result.data.object[0].email;
                    this.usuario.cargo = result.data.object[0].cargo;
                    this.usuario.dui = result.data.object[0].dui;
                    this.usuario.horario_id = result.data.object[0].horario_id;
                    this.usuario.avisar_contacto = result.data.object[0].avisar_contacto;
                    this.usuario.numero_emergencia = result.data.object[0].numero_emergencia;
                    this.usuario.salario = result.data.object[0].salario;
                    this.usuario.id = result.data.object[0].id;

                    axios.get(`empresa/areas?id=${this.usuario.empresa}`, { headers: { "Content-type": "application/json" } })
                        .then((resp) => {
                                this.areas = resp.data.object;
                            })
                    }).catch((error) => {
                        console.error("Error al obtener datos del empleado:", error);
                    });
            }
        },
        filterHorario() {
            if (this.turno == "all") {
                this.getHorarios();
            } else {
                this.horariosFilter = [];
                this.horariosFilter = this.horarios.filter(
                    (item) =>
                        this.turno.localeCompare(item.turno, "es", {
                            sensitivity: "accent",
                        }) == 0
                );
            }
        },
        selectHorario(id) {
            this.usuario.horario_id = id;
        },
        cancelar() {
            this.$confirm(
                "¿Estás seguro de que deseas salir de esta pantalla?"
            ).then(() => {
                    this.usuario.nombres = "";
                    this.usuario.apellidos = "";
                    this.usuario.email = "";
                    this.usuario.area_id = 0;
                    this.usuario.cargo = "";
                    this.usuario.empresa = "";
                    this.usuario.dui = "";
                    this.usuario.horario_id = 0;
                    this.usuario.avisar_contacto = "";
                    this.usuario.numero_emergencia = "";
                    this.usuario.salario = 0;
                    this.$router.push("/empleados");
            });
        },
        async verifyDuiAsync(dui) {
            axios.get(`empleados/existeDui?dui=${dui}`).then((response) => {
                if (response.data.status == 200) {
                    this.verifyDuiEmailorBoolean = false;
                    this.MSErrorDui = "DUI válido";
                }
            }).catch((error) => {
                if (error.response && error.response.data.status == 400) {
                    if (error.response.data.object.id == this.usuario.id) {
                        this.verifyDuiEmailorBoolean = false;
                        this.MSErrorDui = "DUI válido";
                    } else {
                        this.verifyDuiEmailorBoolean = true;
                        this.MSErrorDui = "DUI ya ha sido registrado";
                    }
                }
            });
        },
        async verifyEmail(Email) {
            axios.get(`empleados/existeEmail?email=${Email}`).then((response) => {
                if (response.data.status == 200) {
                    this.verifyEmailorDuiBoolean = false;
                    this.MSErrorDui = "Correo electrónico válido";
                }
            }).catch((error) => {
                if (error.response && error.response.data.status == 400) {
                    if (error.response.data.object.id != this.usuario.id) {
                        this.verifyEmailorDuiBoolean = false;
                        this.MSErrorDui = "Correo electrónico válido";
                    } else {
                        this.verifyEmailorDuiBoolean = true;
                        this.MSErrorDui = "Correo electrónico ya ha sido registrado";
                    }
                }
            });
        },
    },
};
</script>
<style>
.borderCircle {
    border-radius: 20px;
}
</style>
