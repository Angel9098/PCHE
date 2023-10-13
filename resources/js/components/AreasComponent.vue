<template>
    <div class="container d-flex flex-column justify-content-center align-items-center mt-4">
        <h1>&#193;REAS POR EMPRESA</h1>
        <div class="w-100 d-flex flex-row justify-content-between align-items-center mt-5">
            <button type="button" class="btn btn-primary" @click="showCrearArea">Agregar &#193;rea</button>
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
                <thead class="table-primary bg-primary">
                    <tr class="text-center align-middle">
                        <th>&#193;rea</th>
                        <th>Empresa</th>
                        <th>Jefatura</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="areas.length == 0">
                        <td colspan="4" class="text-center">No se encuentran registros asociados</td>
                    </tr>
                    <tr v-else v-for="item in areas" :key="item.area_id" class="align-middle">
                        <td scope="row">{{ item.nombre_area }}</td>
                        <td>{{ item.nombre_empresa }}</td>
                        <td>{{ item.nombre_jefe_area }}</td>
                        <td>
                            <div class="d-flex flex-row justify-content-around">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalCrear" @click="showModificarArea(item.area_id, item.id_empresa)"
                                    title="Editar"><i class="fa-solid fa-pen-to-square text-white"></i></button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminar" @click="showDeleteModal(item.area_id)" title="Eliminar"><i
                                        class="fa-solid fa-trash-alt text-white"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="modalCrear" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="modalCrearLabel" aria-hidden="true">
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
                                    <input type="text" id="name" class="form-control" placeholder="Ingrese nombre de área"
                                        v-model="area.nombre_area">
                                </div>
                                <div class="col-5">
                                    <label for="boss">Seleccione Jefatura</label>
                                    <select class="form-select" id="boss" v-model="area.id_jefe">
                                        <option v-for="jefe in jefaturas" :key="jefe.id" :value="jefe.id">
                                            {{ jefe.nombre_jefe }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"
                            @click="accionModal == 'Agregar' ? crearArea() : modificarArea()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEliminar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="modalEliminarLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger text-white" @click="deleteArea()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import axios from 'axios';
import 'bootstrap/dist/js/bootstrap.bundle';
export default {
    data() {
        return {
            empresas: [],
            areas: [],
            selected: 0,
            accionModal: '',
            area: {
                area_id: 0,
                nombre_area: '', // parametro para traer nombre de area
                nombre: '', // parametro solo para crear y modificar area
                empresa_id: 0,
                id_jefe: 0
            },
            areaCrear: { // parametro solo para crear area
                nombre: '',
                empresa_id: 0,
                jefe_area: 0
            },
            jefaturas: [],
            selectedEliminar: 0
        }
    },
    mounted() {
        this.getEmpresas();
        this.getAreasAll();

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    },
    methods: {
        getEmpresas() {
            axios.get('/empresas', { headers: { 'Content-type': 'application/json' } }).then(resp => {
                this.empresas = resp.data;
            });
        },
        getAreasByEmpresa() {
            axios.get(`/areas?id_empresa=${this.selected}`, { headers: { 'Content-type': 'application/json' } }).then(resp => {
                this.areas = resp.data;
            });
        },
        showCrearArea() {
            if (this.selected == 0) {
                this.$swal.fire({
                    title: 'Error',
                    icon: 'info',
                    text: 'Seleccione empresa',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#modalCrear').modal('show');
                this.getJefaturas();
                this.accionModal = 'Agregar';
                Vue.set(this.area, 'nombre_area', '');
                Vue.set(this.area, 'jefe_area', 0);
            }
        },
        crearArea() {
            Vue.set(this.areaCrear, 'nombre', this.area.nombre_area);
            Vue.set(this.areaCrear, 'empresa_id', this.selected);
            Vue.set(this.areaCrear, 'jefe_area', this.area.jefe_area);

            axios.post('areas/create', this.areaCrear, { headers: { 'Content-type': 'application/json' } }).then(resp => {
                if (resp.data.status == 200) {
                    this.$swal.fire({
                        title: '¡Hecho!',
                        icon: 'success',
                        text: response.data.message,
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    })
                    this.getAreasByEmpresa();
                    $('#modalCrear').modal('hide');
                }
            }).catch(error => {
                if (error.resp.data.status) {
                    this.$swal.fire({
                        title: 'Error',
                        icon: 'error',
                        text: response.data.message,
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            });
        },
        getAreasAll() {
            axios.get('/areas', { headers: { 'Content-type': 'application/json' } }).then(resp => {
                this.areas = resp.data.object;
            });
        },
        getJefaturas() {
            axios.get('areas/jefes', { headers: { 'Content-type': 'application/json' } }).then(response => {
                this.jefaturas = response.data.object;
            });
        },
        rellenarCampos(idArea, idEmpresa) {
            let registroSeleccionado = this.areas.find(element => element.id === idArea);
            this.area.nombre_area = registroSeleccionado.nombre_area;
            this.area.jefe_area = registroSeleccionado.id_jefe;
            this.area.id = registroSeleccionado.id;
            this.area.empresa_id = idEmpresa;
        },
        showModificarArea(idArea, idEmpresa) {
            const modalBackground = document.querySelector('.show');
            modalBackground.classList.add('modal-backdrop');
            this.getJefaturas();
            this.accionModal = 'Modificar'; //Accion para modificar titulo y rellenar campos
            this.rellenarCampos(idArea, idEmpresa);
        },
        modificarArea() {
            Vue.set(this.area, 'nombre', this.area.nombre_area);
            axios.put(`areas/update?id=${this.area.id}`, this.area, { headers: { 'Content-type': 'application/json' } }).then(resp => {
                if (resp.status == 200) {
                    this.$swal.fire({
                        title: '¡Hecho!',
                        icon: 'success',
                        text: resp.data.message,
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    })
                    this.getAreasByEmpresa();
                    const myModal = document.getElementById('modalCrear');
                    myModal.classList.remove('show');
                    myModal.style.display = 'none';
                    const modalBackground = document.querySelector('.modal-backdrop');
                    modalBackground.className = '';
                }
            });
        },
        showDeleteModal(idArea) {
            this.selectedEliminar = idArea;
        },
        deleteArea() {
            axios.delete(`areas/delete?id=${this.selectedEliminar}`, { headers: { 'Content-type': 'application/json' } }).then(resp => {
                if (resp.status == 200) {
                    this.$toast.success('Área eliminada', {
                        position: "top-right",
                        timeout: 3000,
                        closeOnClick: true,
                        pauseOnHover: true,
                        closeButton: "button",
                        icon: true
                    });
                    this.getAreasByEmpresa();
                    $('#modalEliminar').modal('hide');
                }
            })
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
thead th:nth-child(4),
tr td:nth-child(4) {
    width: 10%;
}

.custom-tooltip {
    --bs-tooltip-bg: var(--bd-violet-bg);
    --bs-tooltip-color: var(--bs-white);
}
</style>
