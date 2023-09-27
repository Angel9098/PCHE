<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12">

        <div class="center-image d-flex flex-column justify-content-center align-items-center" style="margin-bottom: 1%">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-75" />
        </div>

        <div class="container mt-4">
            <div class="accordion" id="accordionFilters">
                <div class="accordion-item">
                    <!-- Cabecera del acordeón con estilos personalizados -->
                    <h2 class="accordion-header" id="filters-headingOne">
                        <button class="accordion-button bg-gradient border-0 rounded-3" type="button"
                            data-bs-toggle="collapse" data-bs-target="#filters-collapseOne" aria-expanded="true"
                            aria-controls="filters-collapseOne">
                            FILTROS DE BÚSQUEDA
                        </button>
                    </h2>
                    <!-- Cuerpo del acordeón (filtros) con estilos personalizados -->
                    <div id="filters-collapseOne" class="accordion-collapse collapse" aria-labelledby="filters-headingOne">
                        <div class="accordion-body p-4 border-3d">
                            <!-- Contenido de los filtros aquí -->
                            <div class="row" style="margin-bottom: 2%;">
                                <div class="col-3">
                                    <div class="form-group d-flex" style="width: 100%;">
                                        <select v-model="filtros.selectEmpresa" @input="debounceSearchArea"
                                            class="form-select">
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
                                        <select v-model="filtros.selectArea" @input="debounceSearchEmpleado"
                                            class="form-select">
                                            <option value="" disabled selected>Seleccionar Área</option>
                                            <option value="no">No seleccionar</option>
                                            <option v-for="area in areas" :key="area.id" :value="area.id">
                                                {{ area.id }} - {{ area.nombre }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <input v-model="filtros.dui" @input="debounceSearchEmpleado" type="text" placeholder="DUI" class="form-control mb-2" />
                                </div>
                                <div class="col-2">
                                    <input v-model="filtros.nombre" @input="debounceSearchEmpleado" type="text" placeholder="Nombre" class="form-control mb-2" />
                                </div>
                                <div class="col-2">
                                    <input v-model="filtros.email"  @input="debounceSearchEmpleado" type="text" placeholder="Email" class="form-control mb-2" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="fechaDesde">Desde:</label>
                                        </div>
                                        <div class="col-9">
                                            <input v-model="filtros.fechaDesde" @change="buscarRegistrosByEmpresa"
                                                type="date" id="fechaDesde" class="form-control mb-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="fechaHasta">Hasta:</label>
                                        </div>
                                        <div class="col-9">
                                            <input v-model="filtros.fechaHasta" @change="buscarRegistrosByEmpresa"
                                                type="date" id="fechaHasta" class="form-control mb-2">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="container mt-4">
            <h2 class="h1 text-center mt-5" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">CALCULOS PROCESADOS</h2>
            <div class="col-12 d-flex flex-column">
                <div class="table-responsive mt-5">
                    <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                        <thead class="text-center bg-primary text-white">
                            <th scope="col" class="col-2">ID Empleado</th>
                            <th scope="col" class="col-3">Nombre</th>
                            <th scope="col" class="col-1">Fecha</th>
                            <th scope="col" class="col-2">Sueldo</th>
                            <th scope="col" class="col-1">Empresa</th>
                            <th scope="col" class="col-1">Area</th>
                            <th scope="col" class="col-1">total_horas</th>
                            <th scope="col" class="col-1">salario_ganado</th>
                            <th scope="col" class="col-1">salario_total</th>
                        </thead>
                        <tbody class="text-center" v-if="calculosHoras.length > 0">
                            <tr v-for="registro in calculosHoras" :key="registro.id">
                                <td scope="row">{{ registro.empleado.dui }}</td>
                                <td>{{ registro.empleado.nombres }}</td>
                                <td>{{ registro.fecha_calculo }}</td>
                                <td>{{ registro.empleado.salario | toCurrency }}</td>
                                <td>{{ registro.empleado.area.nombre }}</td>
                                <td>{{ registro.empleado.area.empresa.nombre }}</td>
                                <td>{{ registro.total_horas }}</td>
                                <td>{{ registro.salario_neto }}</td>
                                <td>{{ registro.salario_total }}</td>
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
                <div class="col-9">
                    <!-- Contenido principal, si es necesario -->
                </div>
                <div class="col-1">
                    <div>
                        <button @click="imprimir()" class="btn btn-custom btn-3d">Imprimir</button>
                    </div>
                </div>
                <div class="col-1">
                </div>
                <div class="col-1">
                    <div>
                        <button @click="exportar()" class="btn btn-custom btn-3d">Exportar</button>
                    </div>
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
            calculosHoras: [],
            areas: [],
            duiJefe: "",
            nombreJefe: "",
            email: "",
            filtros: {
                selectEmpresa: "",
                selectArea: "",
                dui: "",
                nombre: "",
                email: "",
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
        debounceSearchFechas: _.debounce(function () {
            if (this.filtros.selectEmpresa === "no") {
                this.filtros.selectEmpresa = "NA";
                this.areas = [];
                this.filtros.selectArea = "NA";
            }
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
        imprimir() {
            // Agrega aquí la lógica para imprimir
        },
        exportar() {
            // Agrega aquí la lógica para exportar
        },
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

        async buscarRegistrosByEmpresa() {
            const response = await axios.post("calculos", this.filtros);
            this.calculosHoras = response.data;
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

.btn-custom {
    background-image: linear-gradient(to bottom, #06495a, #05333f);
    color: #fff;
    border-color: #2a7484;
}

.btn-custom:hover {
    background-image: linear-gradient(to bottom, #06495a, #05333f);
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

.accordion-button {
    background-color: #032a55;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    font-weight: bold;
    transition: background-color 0.3s, box-shadow 0.3s;
}

.accordion-button:hover {
    background-color: #071d35;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.accordion-button:focus {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.accordion-collapse {
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 0.25rem 0.25rem;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

.accordion-button::after {
    content: "\f078";
    /* Código Unicode para una flecha hacia abajo */
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    float: right;
    margin-left: 10px;
}

.accordion-button[aria-expanded="true"]::after {
    content: "\f077";
}

.accordion-body {
    background-color: #f9f9f9;
    padding: 15px;
}
</style>
