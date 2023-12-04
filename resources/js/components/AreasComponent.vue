<template>
    <div class="container d-flex flex-column justify-content-center align-items-center mt-4">
        <h1 class="h1 text-center" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">&#193;REAS POR EMPRESA</h1>
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
                                <button type="button" class="btn btn-primary" @click="showModificarArea(item.area_id, item.id_empresa, item.id_jefe)" title="Editar">
                                    <i class="fa-solid fa-pen-to-square text-white"></i>
                                </button>
                                <button type="button" class="btn btn-danger" @click="showDeleteModal(item.area_id)"
                                    title="Eliminar">
                                    <i class="fa-solid fa-trash-alt text-white"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <span class="page-link">{{ currentPage }}</span>
                    </li>
                    <li class="page-item" v-for="page in lastPage" :key="page" :class="{ active: page === currentPage }">
                        <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === lastPage }">
                        <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>

                </ul>
            </nav>


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
                                    <input type="text" id="name" :class="errorNameArea ? 'form-control is-invalid' : 'form-control'" placeholder="Ingrese nombre de área" v-model="area.nombre_area" @keyup="verifyNameArea" @focusout="verifyNameArea">
                                    <div class="invalid-feedback" v-if="errorNameArea">Campo requerido</div>
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
    </div>
</template>
<script>
import axios from 'axios';
import 'bootstrap/dist/js/bootstrap.bundle';
export default {
    data() {
        return {
            currentPage: 1,
            perPage: 5,
            totalPages: 0,

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
            selectedEliminar: 0,
            errorNameArea: false
        }
    },
    mounted() {
        this.getEmpresas();
        this.getAreasAll();

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    },
    methods: {
        changePage(page) {
            this.currentPage = page;
            this.getAreasByEmpresa();
        },
        getEmpresas() {
            axios.get('/empresas', { headers: { 'Content-type': 'application/json' } }).then(resp => {
                this.empresas = resp.data;
            });
        },
        getAreasByEmpresa() {

            const params = {
                id_empresa: this.selected,
                page: this.currentPage,
                per_page: this.perPage,
            };

            axios.get('/areas', { params, headers: { 'Content-type': 'application/json' } })
                .then(resp => {
                    this.areas = resp.data.object.data;
                    this.totalPages = resp.data.object.last_page;
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
            if (this.area.nombre_area == '') {
                this.$swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: 'Nombre de área requerido',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 2000
                });
                this.errorNameArea = true;
            } else {
                this.$swal.fire({
                    title: 'Cargando...',
                    html: '<div class="my-5 d-flex flex-row justify-content-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div></div>',
                    showCancelButton: false,
                    showConfirmButton: false
                });
                Vue.set(this.areaCrear, 'nombre', this.area.nombre_area);
                Vue.set(this.areaCrear, 'empresa_id', this.selected);
                Vue.set(this.areaCrear, 'jefe_area', this.area.id_jefe);

                axios.post('areas/create', this.areaCrear, { headers: { 'Content-type': 'application/json' } }).then(response => {
                    this.$swal.close();
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

                }).catch(error => {
                    if (error.response) {
                        this.$swal.fire({
                            title: 'Error',
                            icon: 'error',
                            text: error.response.data.message,
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                });
            }
        },
        getAreasAll() {
            const params = {
                id_empresa: this.selected,
                page: this.currentPage,
                per_page: this.perPage,
            };

            axios.get('/areas', { params, headers: { 'Content-type': 'application/json' } })
                .then(resp => {
                    this.areas = resp.data.object.data;
                    this.totalPages = resp.data.object.last_page;
                });
        },
        getJefaturas() {
            this.jefaturas = [];
            axios.get('areas/jefes', { headers: { 'Content-type': 'application/json' } }).then(response => {
                this.jefaturas = response.data.object;
            });
        },
        getJefaturasAsignados(idEmpleado) {
            this.jefaturas = [];
            axios.get(`areas/jefesAsignados?id=${idEmpleado}`, { headers: { 'Content-type': 'application/json' } }).then(response => {
                this.jefaturas = response.data.object;
            });
        },
        rellenarCampos(idArea, idEmpresa) {
            let registroSeleccionado = this.areas.find(element => element.area_id === idArea);
            this.area.nombre_area = registroSeleccionado.nombre_area;
            this.area.id_jefe = registroSeleccionado.id_jefe;
            this.area.area_id = registroSeleccionado.area_id;
            this.area.empresa_id = idEmpresa;
        },
        showModificarArea(idArea, idEmpresa, idJefe) {
            $('#modalCrear').modal('show');
            this.getJefaturasAsignados(idJefe);
            this.accionModal = 'Modificar'; //Accion para modificar titulo y rellenar campos
            this.rellenarCampos(idArea, idEmpresa);
        },
        modificarArea() {
            this.$swal.fire({
                title: 'Cargando...',
                html: '<div class="my-5 d-flex flex-row justify-content-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div></div>',
                showCancelButton: false,
                showConfirmButton: false
            });
            Vue.set(this.areaCrear, 'nombre', this.area.nombre_area);
            Vue.set(this.areaCrear, 'empresa_id', this.area.empresa_id);
            Vue.set(this.areaCrear, 'jefe_area', this.area.id_jefe);
            axios.put(`areas/update?id=${this.area.area_id}`, this.areaCrear, { headers: { 'Content-type': 'application/json' } }).then(response => {
                this.$swal.close();
                if (response.data.status == 200) {
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
                if (error.response) {
                    this.$swal.fire({
                        title: 'Error',
                        icon: 'error',
                        text: error.response.data.message,
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            });
        },
        showDeleteModal(idArea) {
            this.selectedEliminar = idArea;
            this.$swal.fire({
                title: '¿Estás seguro que deseas eliminar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteArea();
                }
            });
        },
        deleteArea() {
            this.$swal.fire({
                title: 'Cargando...',
                html: '<div class="my-5 d-flex flex-row justify-content-center"><div class="sk-chase"><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div><div class="sk-chase-dot"></div></div></div>',
                showCancelButton: false,
                showConfirmButton: false
            });
            axios.delete(`areas/delete?id=${this.selectedEliminar}`, { headers: { 'Content-type': 'application/json' } }).then(response => {
                this.$swal.close();
                if (response.status == 200) {
                    this.$swal.fire({
                        title: '¡Hecho!',
                        icon: 'success',
                        text: 'Área eliminada con éxito',
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    })
                    this.getAreasByEmpresa();
                    $('#modalEliminar').modal('hide');
                }
            }).catch(error => {
                if (error.response) {
                    this.$swal.fire({
                        title: 'Error',
                        icon: 'error',
                        text: error.response.data.message,
                        showCancelButton: false,
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            });
        },
        verifyNameArea($event) {
            if ($event.target.value.length > 0) this.errorNameArea = false;
            else this.errorNameArea = true;
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
