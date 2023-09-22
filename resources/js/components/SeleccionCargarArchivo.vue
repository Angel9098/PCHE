<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12">

        <div class="center-image d-flex flex-column justify-content-center align-items-center" style="margin-bottom: 1%">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-75" />
        </div>
        <div class="container mt-4">
            <h2 style="text-align: left;" class="mt-5">FILTROS DE BUSQUEDA</h2>
        </div>
        <div class="container mt-4">
            <div class="row">
                <div class="col-2">
                    <div class="form-group d-flex" style="width: 100%;">
                        <select v-model="filtros.selectEmpresa" @input="debounceSearchArea" class="form-select">
                            <option value="" disabled selected>Seleccionar Empresa</option>
                            <option value="">No seleccionar</option>
                            <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                                {{ empresa.id }} - {{ empresa.nombre }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group d-flex" style="width: 100%;">
                        <select v-model="filtros.selectArea" @input="debounceSearchEmpleado" class="form-select">
                            <option value="" disabled selected>Seleccionar Area</option>
                            <option value="">No seleccionar</option>
                            <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                                {{ empresa.id }} - {{ empresa.nombre }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <input v-model="duiJefe" type="text" placeholder="Dui jefe" class="form-control mb-2" disabled />
                </div>
                <div class="col-2">
                    <input v-model="nombreJefe" type="text" placeholder="Nombre jefe" class="form-control mb-2" disabled />
                </div>
            </div>
        </div>

        <div class="container">
            <h2 class="h1 text-center mt-5">REGISTROS DE HORAS EXTRAS</h2>
            <div class="col-11 d-flex flex-column">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                        <thead class="text-center bg-primary text-white align-middle">
                            <th scope="col">ID Empleado</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Sueldo</th>
                            <th scope="col">Diurnas</th>
                            <th scope="col">Nocturnas</th>
                            <th scope="col">Diurnas Descanso</th>
                            <th scope="col">Nocturnas Descanso</th>
                            <th scope="col">Diurnas Asueto</th>
                            <th scope="col">Nocturnas Asueto</th>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="registro in items" :key="registro.id">
                                <td scope="row">{{ registro.idEmpleado }}</td>
                                <td>{{ registro.nombre }}</td>
                                <td>{{ registro.fecha }}</td>
                                <td>{{ registro.sueldo | toCurrency }}</td>
                                <td>{{ registro.diurnas }}</td>
                                <td>{{ registro.nocturnas }}</td>
                                <td>{{ registro.diurnasDescanso }}</td>
                                <td>{{ registro.nocturnasDescanso }}</td>
                                <td>{{ registro.diurnasAsueto }}</td>
                                <td>{{ registro.nocturnasAsueto }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
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
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            empleados: [],
            empresas: [],
            areas: [],
            duiJefe: "",
            nombreJefe: "",
            filtros: {
                selectEmpresa: "",
                selectArea: "",
            },

            currentPage: 1,
            lastPage: 1,
        };
    },
    created() {
        this.fetchEmpresas();
        // this.fetchEmpleados();
    },
    methods: {
        debounceSearchArea: _.debounce(function () {

            //this.searchEmpleado();
            this.buscarArea();
        }, 300),
        debounceSearchEmpleado: _.debounce(function () {
            const areaId = this.filtros.selectArea;
            console.log(areaId);
            if (areaId === 1) {
                this.duiJefe = "";
                this.nombreJefe = "";
            }
            this.searchAreaById(areaId);
        }, 300),
        async buscarArea() {
            if (this.filtros.selectEmpresa === 1) {
                this.filtros.selectEmpresa = null;
                this.filtros.selectArea = null;
            }
            console.log(this.filtros.selectEmpresa);
            axios
                .get("/areasbysearch")
                .then((response) => {
                    this.areas = response.data.map((area) => ({
                        id: area.id,
                        nombre: area.nombre,
                        idJefeArea: area.jefe_area,
                    }));
                })
                .catch((error) => {
                    console.error("Error al cargar areas:", error);
                });
        },
        async searchAreaById(areaId) {
            $.ajax({
                url: '/areabyid?id=' + areaId,
                method: 'GET',
                success: (data) => {
                    $.ajax({
                        url: '/empleadobyid?idEmpleado=' + data.jefe_area,
                        method: 'GET',
                        success: (data2) => {
                            this.duiJefe = data2.nombre;
                            this.nombreJefe = data2.nombre;
                        },
                        error: (error) => {

                        }
                    });
                },
                error: (error) => {
                    console.error('Error al obtener los datos de la empresa:', error);
                }
            });
        },

        fetchEmpresas() {
            axios
                .get("/empresas")
                .then((response) => {
                    this.empresas = response.data.map((empresa) => ({
                        id: empresa.id,
                        nombre: empresa.nombre,
                    }));
                })
                .catch((error) => {
                    console.error("Error al cargar empresas:", error);
                });
        },

        changePage(page) {
            this.currentPage = page;
            this.fetchEmpleados();
        }
    },
};
</script>

<style scoped>
.content-container {
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
}

.textbox,
.vdp-datepicker {
    flex: 1;
    padding: 10px;
    margin: 0 10px;
}

.borderCircle {
    border-radius: 20px;
    border-color: white;
}
</style>
