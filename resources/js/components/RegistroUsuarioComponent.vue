<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9 mt-3">
            <h1 class="text-center">Registro de Empleado</h1>
            <div class="card mt-3 mb-3 borderCircle bg-white">
                <div class="card-body">
                    <form>
                        <h4 class="alert-heading">Datos generales</h4>
                        <hr>
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-5">
                                <label for="name">Nombre</label>
                                <input id="name" type="text" class="form-control" v-model="usuario.name" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="last_name">Apellido</label>
                                <input id="last_name" type="text" class="form-control" v-model="usuario.last_name" required>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-4">
                                <label for="job_title">Cargo</label>
                                <input id="job_title" type="text" class="form-control" v-model="usuario.job_title" required>
                            </div>
                            <div class="form-group col-3">
                                <label for="job_title">DUI</label>
                                <input id="job_title" type="text" class="form-control" v-model="usuario.dui" required>
                            </div>
                            <div class="form-group col-4">
                                <label for="email">Correo Electrónico</label>
                                <input id="email" type="email" class="form-control" v-model="usuario.email" required>
                            </div>
                        </div>
                        
                        <h4 class="alert-heading pt-4">Empresa y horario</h4>
                        <hr>
                        <div class="d-flex flex-row justify-content-between gap-2">
                            <div class="form-group col-5">
                                <label for="company">Empresa</label>
                                <select id="company" class="form-select" v-model="usuario.company" required @change="getAreas">
                                    <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                                        {{ empresa.nombre }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="area">Área</label>
                                <select id="area" class="form-select" v-model="usuario.area" @change="getHorarios" required>
                                    <option v-if="areas.length === 0" :disabled="true">
                                        Sin elementos disponibles
                                    </option>
                                    <option v-for="a in areas" :key="a.id" :value="a.id">
                                        {{ a.nombre }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-row justify-content-start">
                            <div class="form-group col-4">
                                <label for="hora">Turno</label>
                                <select id="hora" class="form-select" v-model="turno" required :placeholder="'Seleccione turno'" @change="filterHorario">
                                    <option value="diurno">
                                        Diurno
                                    </option>
                                    <option value="nocturno">
                                        Nocturno
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-row col-12 mt-2">
                            <table class="table table-hover table-sm table-bordered">
                                <thead class="text-center table-dark">
                                    <tr>
                                    <th scope="col" class="text-white">Selección</th>
                                    <th scope="col" class="text-white">Horario</th>
                                    <th scope="col" class="text-white">Horas por semana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check d-flex flex-row justify-content-center">
                                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" :value="usuario.calendarId" aria-label="...">
                                            </div>                                            
                                        </th>
                                        <td>LUNES A JUEVES DE 8:00 AM A 5:00 PM / VIERNES DE 8:00 AM A 8:00 PM</td>
                                        <td class="text-center">44</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check d-flex flex-row justify-content-center">
                                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" :value="usuario.calendarId" aria-label="...">
                                            </div> 
                                        </th>
                                        <td>LUNES A JUEVES DE 12:00 AM A 8:00 PM / DOMINGO DE 8:00 AM A 8:00 PM</td>
                                        <td class="text-center">40</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-12 d-flex flex-row justify-content-start">
                            <button type="button" class="btn btn-primary" @click="registrar">
                                Guardar
                            </button>
                            <button type="button" class="btn btn-light mx-3">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</template>
<script>

import axios from 'axios';

    export default{
        data() {
            return {
                usuario:{
                    name:'',
                    last_name:'',
                    email:'',
                    area: 0,
                    job_title:'',
                    company:'',
                    dui: '',
                    calendarId: 0
                },
                empresas: [],
                areas: [],
                horarios: [],
                turno: ''
            }
        },
        mounted() {
            this.getEmpresas();
        },
        methods: {
            registrar() {
                axios.post('empleados/crear', this.usuario, {
                    headers:{'Content-type':'application/json'}
                }).then(response=>{
                    console.log(response.data);
                })
            },
            getEmpresas(){
                axios.get('empresas',{
                    headers: {'Content-type': 'application/json'}
                }).then(resp=>{
                    this.empresas = resp.data;
                });
            },
            getAreas() {
                axios.get(`areas?idEmpresa=${this.usuario.company}`, {
                    headers: { 'Content-type': 'application/json' }
                }).then(resp=>{
                    this.areas = resp.data.areas;
                });
            },
            getHorarios(){
                axios.get(`horarios/area?idArea=${this.usuario.area}`,{
                    headers: {'Content-type': 'application/json'}
                }).then(resp=>{
                    this.horarios = resp.data;
                });
            },
            filterHorario(){
            this.horarios =  this.horarios.filter(item => { item.turno == this.turno });
            }

        }
    }
</script>
<style>
    .borderCircle{
        border-radius: 20px;
    }
</style>