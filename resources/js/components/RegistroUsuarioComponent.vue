<template>
    <div class="w-100 d-flex justify-content-center">
        <div class="col-9 mt-3">
            <h1 class="text-center">Registro de Empleado</h1>
            <div class="card mt-3 mb-3 borderCircle bg-white">
                <div class="card-body">
                    <form class="needs-validation" novalidate @submit.prevent="registrar">
                        <h4 class="alert-heading">Datos generales</h4>
                        <hr>
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-5">
                                <label for="name">Nombre</label>
                                <input id="name" type="text" class="form-control" v-model="usuario.nombres" required>
                                <div class="invalid-feedback">
                                    Ingrese nombre
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="last_name">Apellido</label>
                                <input id="last_name" type="text" class="form-control" v-model="usuario.apellidos" required>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-5">
                                <label for="job_title">DUI</label>
                                <input id="job_title" type="text" class="form-control" v-model="usuario.dui" required v-mask="'########-#'">
                            </div>
                            <div class="form-group col-6">
                                <label for="email">Correo Electr&#243;nico</label>
                                <input id="email" type="email" class="form-control" v-model="usuario.email" required>
                            </div>
                        </div>
                        <h4 class="alert-heading pt-4">En caso de emergencia contactar a</h4>
                        <hr>
                        <div class="d-flex flex-row justify-content-between gap-2 mt-2">
                            <div class="form-group col-5">
                                <label for="nameEmergency">Nombre completo</label>
                                <input id="nameEmergency" type="text" class="form-control" v-model="usuario.nombre_persona"
                                    required>
                            </div>
                            <div class="form-group col-6">
                                <label for="phoneEmergency">Tel&#233;fono</label>
                                <input id="phoneEmergency" type="text" class="form-control" v-model="usuario.numero_emergencia" required v-mask="'####-####'">
                            </div>
                        </div>

                        <h4 class="alert-heading pt-4">Empresa</h4>
                        <hr>
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-5">
                                <label for="company">Empresa</label>
                                <select id="company" class="form-select" v-model="usuario.empresa" required
                                    @change="getAreas">
                                    <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                                        {{ empresa.nombre }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="area">&#193;rea</label>
                                <select id="area" class="form-select" v-model="usuario.area_id" @change="getHorarios"
                                    required :disabled="usuario.empresa == ''">
                                    <option v-if="areas.length === 0" :disabled="true">
                                        Sin elementos disponibles
                                    </option>
                                    <option v-for="a in areas" :key="a.id" :value="a.id">
                                        {{ a.nombre }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="form-group col-5">
                                <label for="job_title">Cargo</label>
                                <input id="job_title" type="text" class="form-control" v-model="usuario.cargo" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="salary">Salario</label>
                                <input id="salary" type="text" class="form-control" v-model="usuario.salario" required>
                            </div>
                        </div>

                        <h4 class="alert-heading pt-4">Selecci&#243;n horario</h4>
                        <hr>
                        <div class="d-flex flex-row justify-content-start">
                            <div class="form-group col-4">
                                <label for="hora">Turno</label>
                                <select id="hora" class="form-select" v-model="turno" required :placeholder="'Seleccione turno'" @change="filterHorario">
                                    <option :value="'all'">
                                        Todos
                                    </option>
                                    <option :value="'diurno'">
                                        Diurno
                                    </option>
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
                                                <input class="form-check-input position-static" type="checkbox"
                                                    id="blankCheckbox" :value="horario.id" aria-label="..."
                                                    @change="selectHorario(horario.id)"
                                                    :checked="horario.id === usuario.horario_id">
                                            </div>
                                        </th>
                                        <td>{{ horario.descripcion }}</td>
                                        <td class="text-center">{{ horario.horas_semana }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-12 d-flex flex-row justify-content-start">
                            <button type="submit" class="btn btn-primary">
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
<script>

import axios from 'axios';

export default {
    data() {
        return {
            usuario: {
                nombres: '',
                apellidos: '',
                email: '',
                area_id: 0,
                cargo: '',
                empresa: '',
                dui: '',
                horario_id: 0,
                nombre_persona: '',
                numero_emergencia: '',
                salario: 0
            },
            empresas: [],
            areas: [],
            horarios: [],
            horariosFilter: [],
            turno: 'all'
        }
    },
    mounted() {
        this.getEmpresas();
        this.getHorarios();
    },
    methods: {
        registrar() {
            axios.post('empleados/crear', this.usuario, {
                headers: { 'Content-type': 'application/json' }
            }).then(response => {
                this.cancelar();
                this.$toast.success(response.data.message, {
                    timeout: 3000,
                    position: 'top-right',
                    icon: true
                });
            })
        },
        getEmpresas() {
            axios.get('empresas', {
                headers: { 'Content-type': 'application/json' }
            }).then(resp => {
                this.empresas = resp.data;
            });
        },
        getAreas() {
            axios.get(`areas?idEmpresa=${this.usuario.empresa}`, {
                headers: { 'Content-type': 'application/json' }
            }).then(resp => {
                this.areas = resp.data.areas;
            });
        },
        getHorarios() {
            axios.get(`horarios`, {
                headers: { 'Content-type': 'application/json' }
            }).then(resp => {
                this.horarios = resp.data;
                this.horariosFilter = resp.data;
            });
        },
        filterHorario() {
            if (this.turno == 'all') {
                this.getHorarios();
            }
            else {
                this.horariosFilter = [];
                this.horariosFilter = this.horarios.filter(item => this.turno.localeCompare(item.turno, 'es', { sensitivity: 'accent' }) == 0);
            }
        },
        selectHorario(id) {
            this.usuario.horario_id = id;
        },
        cancelar() {
            this.usuario.nombres = '';
            this.usuario.apellidos = '';
            this.usuario.email = '';
            this.usuario.area_id = 0;
            this.usuario.cargo = '';
            this.usuario.empresa = '';
            this.usuario.dui = '';
            this.usuario.horario_id = 0;
            this.usuario.nombre_persona = '';
            this.usuario.numero_emergencia = '';
            this.usuario.salario = 0;
        }

    }
}
</script>
<style>
.borderCircle {
    border-radius: 20px;
}
</style>
