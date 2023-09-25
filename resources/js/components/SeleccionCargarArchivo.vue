<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12">

        <div class="center-image d-flex flex-column justify-content-center align-items-center" style="margin-bottom: 1%">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-75" />
        </div>
        <div class="container mt-4">
            <h2 style="text-align: left;" class="mt-5">FILTROS DE BUSQUEDA</h2>
        </div>
        <div class="container mt-4">
            <div class="row" style="margin-bottom: 2%;">
                <div class="col-2">
                    <div class="form-group d-flex" style="width: 100%;">
                        <select v-model="filtros.selectEmpresa" @input="debounceSearchArea" class="form-select">
                            <option value="" disabled selected>Seleccionar Empresa</option>
                            <option value="no">No seleccionar</option>
                            <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                                {{ empresa.id }} - {{ empresa.nombre }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group d-flex" style="width: 100%;">
                        <select v-model="filtros.selectArea" @input="debounceSearchEmpleado" class="form-select">
                            <option value="" disabled selected>Seleccionar Area</option>
                            <option value="no">No seleccionar</option>
                            <option v-for="area in areas" :key="area.id" :value="area.id">
                                {{ area.id }} - {{ area.nombre }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <input v-model="duiJefe" type="text" placeholder="Dui jefe" class="form-control mb-2" disabled />
                </div>
                <div class="col-3">
                    <input v-model="nombreJefe" type="text" placeholder="Nombre jefe" class="form-control mb-2" disabled />
                </div>
                <div class="col-2">
                    <input v-model="email" type="text" placeholder="Email" class="form-control mb-2" disabled />
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="row">
                        <div class="col-3">
                            <label for="fechaHasta">Desde:</label>
                        </div>
                        <div class="col-9">
                            <input v-model="filtros.fechaDesde" type="date" id="fechaHasta" class="form-control mb-2">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="row">
                        <div class="col-3">
                            <label for="fechaHasta">Hasta:</label>
                        </div>
                        <div class="col-9">
                            <input v-model="filtros.fechaHasta" type="date" id="fechaHasta" class="form-control mb-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <h2 class="h1 text-center mt-5">HISTORIAL DE HORAS EXTRAS</h2>
            </div>
            <div class="col-11 d-flex flex-column">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                        <thead class="text-center bg-primary text-white">
                            <th scope="col" class="col-2">ID Empleado</th>
                            <th scope="col" class="col-3">Nombre</th>
                            <th scope="col" class="col-1">Fecha</th>
                            <th scope="col" class="col-2">Sueldo</th>
                            <th scope="col" class="col-1">Diurnas</th>
                            <th scope="col" class="col-1">Nocturnas</th>
                            <th scope="col" class="col-1">Diurnas Descanso</th>
                            <th scope="col" class="col-1">Nocturnas Descanso</th>
                            <th scope="col" class="col-1">Diurnas Asueto</th>
                            <th scope="col" class="col-1">Nocturnas Asueto</th>
                        </thead>


                        <tbody class="text-center" v-if="horasExtras.length > 0">
                            <tr v-for="registro in horasExtras" :key="registro.id">
                                <td scope="row">{{ registro.empleado.dui }}</td>
                                <td>{{ registro.empleado.nombres }}</td>
                                <td>{{ registro.fecha_registro }}</td>
                                <td>{{ registro.empleado.salario | toCurrency }}</td>
                                <td>{{ registro.diurnas }}</td>
                                <td>{{ registro.nocturnas }}</td>
                                <td>{{ registro.diurnas_descanso }}</td>
                                <td>{{ registro.nocturnas_descanso }}</td>
                                <td>{{ registro.diurnas_asueto }}</td>
                                <td>{{ registro.nocturnas_asueto }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="8" class="text-center">
                                    No hay registros para mostrar
                                </td>
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

            <div class="row">
                <div class="col-10">
                </div>
                <div class="col-1">
                    <button @click="procesar()" class="btn btn-custom btn-3d">Procesar</button>
                </div>
                <div class="col-1">

                </div>
            </div>
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
            horasExtras: [],
            areas: [],
            duiJefe: "",
            nombreJefe: "",
            email: "",
            filtros: {
                selectEmpresa: "",
                selectArea: "",
                fechaDesde: "",
                fechaHasta: "",
            },

            currentPage: 1,
            lastPage: 1,
        };
    },
    created() {
        this.fetchEmpresas();
        this.buscarRegistrosByEmpresa()
    },
    methods: {
        debounceSearchArea: _.debounce(function () {
            if (this.filtros.selectEmpresa === "no") {
                this.filtros.selectEmpresa = "NA";
                this.areas = [];
                this.filtros.selectArea = "NA";
            }
            this.buscarArea();
            this.buscarRegistrosByEmpresa(this.filtros);

        }, 300),
        debounceSearchEmpleado: _.debounce(function () {
            if (this.filtros.selectArea === "no") {
                this.filtros.selectArea = "NA";
                this.duiJefe = "";
                this.email = "";
                this.nombreJefe = "";

            }
            const areaId = this.filtros.selectArea;
            this.searchAreaById(areaId);
            this.buscarRegistrosByEmpresa(this.filtros);

        }, 300),
        async buscarArea() {

            const areaId = this.filtros.selectEmpresa;
            axios
                .get("/empresa/areas?id=" + areaId)
                .then((response) => {
                    this.areas = response.data.map((area) => ({
                        id: area.id,
                        nombre: area.nombre,
                    }));
                })
                .catch((error) => {
                    console.error("Error al cargar areas:", error);
                });
        },
        procesar() {

        },
        async searchAreaById(areaId) {
            $.ajax({
                url: '/areabyid?id=' + areaId,
                method: 'GET',
                success: (data) => {
                    $.ajax({
                        url: '/findempleadobyid?id=' + data.jefe_area,
                        method: 'GET',
                        success: (data2) => {
                            this.duiJefe = data2.dui;
                            this.nombreJefe = data2.nombres;
                            this.email = data2.email;
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

        async fetchHorasExtras() {
            const response = await axios.post("horas_extra");
            this.horasExtras = response.data;
        },
        async buscarRegistrosByEmpresa() {
            const response = await axios.post("horas_extra", this.filtros);
            this.horasExtras = response.data;
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

.btn-custom {
    background-image: linear-gradient(to bottom, #57b5cc, #3a92a7);
    color: #fff;
    border-color: #2a7484;
}

.btn-custom:hover {
    background-image: linear-gradient(to bottom, #3a92a7, #57b5cc);
}

/* Ajustar el color del texto para que resalte más */
.btn-custom.btn-3d {
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
    font-weight: bold;
}

.btn-custom.btn-3d:hover {
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);
    transform: translateY(-1px);
}

.borderCircle {
    border-radius: 20px;
    border-color: white;
}
</style>
