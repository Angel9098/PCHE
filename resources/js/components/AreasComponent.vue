<template>
    <div class="container d-flex flex-column justify-content-center align-items-center mt-4">
        <h1>AREAS POR EMPRESA</h1>
        <div class="w-100 d-flex flex-row justify-content-between align-items-center mt-5">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear" @click="accionModal='Agregar'">Agregar &#193;rea</button>
            <div class="col-4">
                <select class="form-select" v-model="selected" @change="getAreasByEmpresa">
                    <option :value="0">
                        Todos
                    </option>
                    <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                        {{ empresa.nombre }}
                    </option>
                </select>
            </div>
        </div>
        <div class="w-100 d-flex flex-column table-responsive mt-5">
            <table class="table table-bordered table-hover table-sm">
                <thead class="text-center table-primary align-middle">
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Jefatura</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    <tr v-if="areas.length == 0">
                        <td colspan="4" class="text-center">No se encuentran registros asociados</td>
                    </tr>
                    <tr v-else v-for="item in areas" :key="item.id" class="align-middle">
                        <td scope="row">{{ item.nombre_area }}</td>
                        <td>{{ item.nombre_empresa }}</td>
                        <td>{{ item.nombre_jefe_area }}</td>
                        <td>
                            <div class="d-flex flex-row justify-content-around">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalCrear" @click="showModificarArea(item.id)"><i class="fa-solid fa-pen-to-square text-white"></i></button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar"><i class="fa-solid fa-trash text-white"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    <div class="modal fade" id="modalCrear" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalCrearLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">{{ accionModal }} &#193;rea</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="col-12">
                <div class="w-100 d-flex flex-row justify-content-between align-items-center">
                    <div class="col-5">
                        <label for="name">Nombre &#193;rea</label>
                        <input type="text" id="name" class="form-control" placeholder="Ingrese nombre de área" v-model="area.nombre_area">
                    </div>
                    <div class="col-5">
                        <label for="boss">Seleccione Jefatura</label>
                        <select class="form-select" id="boss" v-model="area.jefe_area">
                            <option>

                            </option>
                        </select>
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary">Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalEliminar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Eliminar &#193;rea</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de eliminar el &#225;rea seleccionada?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger text-white">Eliminar</button>
                </div>
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
            empresas: [],
            areas: [],
            selected: 0,
            accionModal: '',
            area: {
                nombre_area: '', // parametro para traer nombre de area
                nombre: '', // parametro solo para crear y modificar area 
                empresa_id: 0,
                jefe_area: 0
            }
        }
    },
    mounted() {
        this.getEmpresas();
        this.getAreasAll();
    },
    methods: {
        getEmpresas() {
            axios.get('/empresas', { headers: { 'Content-type': 'application/json' } }).then(resp => {
                this.empresas = resp.data;
            });
        },
        getAreasByEmpresa() {
            axios.get(`/empresa/areas?id=${this.selected}`, { headers: { 'Content-type': 'application/json' } }).then(resp => {
                this.areas = resp.data;
            });
        },
        showModificarArea(idArea) {
            this.accionModal = 'Modificar'; //Accion para modificar titulo y rellenar campos
            this.rellenarCampos(idArea);
        },
        getAreasAll() {
            axios.get('/areas', { headers: { 'Content-type': 'application/json' } }).then(resp => {
                this.areas = resp.data;
            });
        },
        rellenarCampos(idArea) {
            let registroSeleccionado = this.areas.find(element => element.id === idArea);
            this.area.nombre_area = registroSeleccionado.nombre_area;
        },
        deleteArea(idArea) {
            axios.delete('')
        }
    },
    filters: {
        empresaName: function (value) {
            if (!value) return '';
            else {
                let nombre = this.empresas.find(item => item.id === value).nombre;
                return nombre;
            }
        }
    }
}
</script>
<style lang="scss">

thead th:nth-child(4), tr td:nth-child(4) {
    width: 10%;
}

.custom-tooltip{
    --bs-tooltip-bg: var(--bd-violet-bg);
    --bs-tooltip-color: var(--bs-white);
}

</style>