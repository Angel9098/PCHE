<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12">

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
                                    <input v-model="filtros.dui" @input="debounceSearchEmpleadoByinput" type="text" placeholder="DUI" class="form-control mb-2" />
                                </div>
                                <div class="col-4">
                                    <input v-model="filtros.nombre" @input="debounceSearchEmpleadoByinput" type="text" placeholder="Nombre" class="form-control mb-2" />
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
                                <div class="col-3">
                                    <input v-model="filtros.email"  @input="debounceSearchEmpleadoByinput" type="text" placeholder="Email" class="form-control mb-2" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="container mt-4">
            <div class="col-12 d-flex flex-column">
                <div class="table-responsive mt-5">
                    <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                        <thead class="text-center bg-primary text-white">
                            <th scope="col" class="col-1">ID Empleado</th>
                            <th scope="col" >Nombre</th>
                            <th scope="col" class="col-1">Fecha</th>
                            <th scope="col" class="col-1">Sueldo</th>
                            <th scope="col" class="col-1">Empresa</th>
                            <th scope="col" class="col-1">&#193;rea</th>
                            <th scope="col" class="col-1">Total Horas</th>
                            <th scope="col" class="col-1">Salario Ganado</th>
                            <th scope="col" class="col-1">Salario Total</th>
                        </thead>
                        <tbody class="text-center" v-if="calculosHoras.length > 0">
                            <tr v-for="registro in calculosHoras" :key="registro.id">
                                <td scope="row">{{ registro.empleado.dui }}</td>
                                <td>{{ registro.empleado.nombres + " " + registro.empleado.apellidos }}</td>
                                <td>{{ formatFecha(registro.fecha_calculo)}}</td>
                                <td>{{ registro.empleado.salario | toCurrency}}</td>
                                <td>{{ registro.empleado.area.empresa.nombre }}</td>
                                <td>{{ registro.empleado.area.nombre }}</td>
                                <td class="centered">{{ registro.total_horas }}</td>
                                <td>{{ registro.salario_neto | toCurrency }}</td>
                                <td>{{ registro.salario_total | toCurrency}}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="9" class="text-center">
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
                        <button @click="generatePDF()" class="btn btn-custom btn-3d">Imprimir</button>
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


        <!--**************************************** segmento del PDF ********************************************************-->
        <div v-if="showPdfTemplate" class="pdf-content">
            <!-- Contenido para el PDF -->
            <div ref="pdfContent" class="pdf-wrapper">
                <!-- Encabezado -->
                <div class="header">
                    <div class="logo-container">
                        <img src="/assets/img/latinMobile.png" alt="logo" class="logo">
                    </div>
                    <div class="title-container">
                        <h2 class="title">PCHE</h2>
                        <h4>Reporte de Horas Extras</h4>
                        <p v-if="EmpresaSelect != undefined">Empresa: {{ EmpresaSelect.nombre }}</p>
                        <p v-else>Empresas: Todas</p>
                    </div>
                    <div class="area-date-container">
                        <p v-if="AreaSelect != undefined"> Área: {{ AreaSelect.nombre }}</p>
                        <p v-else>Areas: Todas</p>
                        <p class="date">Fecha de Emisión: {{ currentDate }}</p>
                    </div>
                </div>

                <div class="col-12">
                    <div class="col-12 d-flex flex-column">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                        <thead class="text-center bg-primary text-white">
                            <th scope="col" class="col-1">ID Empleado</th>
                            <th scope="col" >Nombre</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Sueldo</th>
                            <th scope="col" class="col-1">Empresa</th>
                            <th scope="col" class="col-1">Area</th>
                            <th scope="col" class="col-1">Total horas</th>
                            <th scope="col" class="col-1">Salario ganado</th>
                            <th scope="col" class="col-1">Salario total</th>
                        </thead>
                        <tbody class="text-center" v-if="calculosHoras.length > 0">
                            <tr v-for="registro in calculosHoras" :key="registro.id">
                                <td scope="row">{{ registro.empleado.dui }}</td>
                                <td>{{ registro.empleado.nombres + " " + registro.empleado.apellidos }}</td>
                                <td>{{formatFecha(registro.fecha_calculo) }}</td>
                                <td>{{ registro.empleado.salario | toCurrency }}</td>
                                <td>{{ registro.empleado.area.empresa.nombre }}</td>
                                <td>{{ registro.empleado.area.nombre }}</td>
                                <td>{{ registro.total_horas }}</td>
                                <td>{{ registro.salario_neto }}</td>
                                <td>{{ registro.salario_total }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="9" class="text-center">
                                    No hay registros para mostrar
                                </td>
                            </tr>
                        </tbody>
                    </table>
                        </div>
                    </div>
                    <!-- Espaciador para separar la tabla del pie de página -->
                    <div class="row">
                        <div class="col-4">
                        <p>Emitido por: {{ nombreJefe }} </p>
                    </div>
                    <div class="col-4" style="text-align: center;">
                        <p>Revisado por:_____________________ </p>
                    </div>
                    <div class="col-4" style="text-align: right;">
                        <p>Aprobado por:_____________________</p>
                    </div>
                    </div>
                    <!-- Paginación como pie de página -->
                    <div class="pagination">
                        pg {{ currentPage }} de {{ totalPages }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from "axios";
import html2pdf from "html2pdf.js";
import moment from "moment";
import ExcelJS from "exceljs";


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
            currentDate: new Date().toLocaleDateString(),
            showPdfTemplate: false,
            currentPage: 1,
            lastPage: 1,
            totalPages: 1,
            EmpresaSelect: {},
            AreaSelect: {},
            
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
                this.EmpresaSelect = {};
            }
            this.buscarArea();
            this.buscarRegistrosByEmpresa(this.filtros);
            this.EmpresaSelect = this.empresas.find(
                (empresa) => empresa.id === this.filtros.selectEmpresa
            );

        }, 300),
        debounceSearchFechas: _.debounce(function () {
            if (this.filtros.selectEmpresa === "no") {
                this.filtros.selectEmpresa = "";
                this.areas = [];
                this.filtros.selectArea = "";
            }
            this.buscarRegistrosByEmpresa(this.filtros);
        }, 300),
        debounceSearchEmpleado: _.debounce(function () {
            if (this.filtros.selectArea === "no") {
                this.filtros.selectArea = "";
                this.duiJefe = "";
                this.email = "";
                this.nombreJefe = "";
                this.selectArea = {};

            }
            const areaId = this.filtros.selectArea;
            this.searchAreaById(areaId);
            this.buscarRegistrosByEmpresa(this.filtros);
            this.AreaSelect = this.areas.find(
                (area) => area.id === this.filtros.selectArea
            );
        }, 300),
        debounceSearchEmpleadoByinput: _.debounce(function () {

            this.buscarRegistrosByEmpresa(this.filtros);
        }, 300),
       
        exportar() {
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet("Reporte de Horas Extras");
            worksheet.columns = [
                { header: "ID Empleado", key: "id_empleado", width: 10 },
                { header: "Nombre", key: "nombre", width: 32 },
                { header: "Fecha", key: "fecha", width: 15 },
                { header: "Sueldo", key: "sueldo", width: 15 },
                { header: "Empresa", key: "empresa", width: 15 },
                { header: "Area", key: "area", width: 15 },
                { header: "Total Horas", key: "total_horas", width: 15 },
                { header: "Salario Ganado", key: "salario_ganado", width: 15 },
                { header: "Salario Total", key: "salario_total", width: 15 },
            ];
            this.calculosHoras.forEach((registro) => {
                worksheet.addRow({
                    id_empleado: registro.empleado.dui,
                    nombre: registro.empleado.nombres + " " + registro.empleado.apellidos,
                    fecha: this.formatFecha(registro.fecha_calculo),
                    sueldo: registro.empleado.salario,
                    empresa: registro.empleado.area.empresa.nombre,
                    area: registro.empleado.area.nombre,
                    total_horas: registro.total_horas,
                    salario_ganado: registro.salario_neto,
                    salario_total: registro.salario_total,
                });
            });
            workbook.xlsx.writeBuffer().then((data) => {
                const blob = new Blob([data], {
                    type:
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "Reporte de Horas Extras.xlsx");
                document.body.appendChild(link);
                link.click();
            });
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
            const {nombres, apellidos} = JSON.parse(localStorage.getItem('userInfo'));
            this.nombreJefe = `${nombres} ${apellidos}`;
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
        },
        generatePDF() {
            // Mostrar la sección de contenido para generar el PDF
            this.showPdfTemplate = true;

            // Utilizar this.$nextTick para esperar a que el DOM se actualice
            this.$nextTick(() => {
                const content = this.$refs.pdfContent;
                const pdfOptions = {
                    margin: 10,
                    filename: "documento.pdf",
                    image: { type: "jpeg", quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
                };

                // Utilizar html2pdf dentro de this.$nextTick
                html2pdf().from(content).set(pdfOptions).save();

                // Ocultar la sección de contenido después de generar el PDF
                this.showPdfTemplate = false;
            });
        },
        formatFecha(date) {
            return moment(date).format("DD/MM/YYYY");
        },
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
.pdf-wrapper {
    position: relative;
    min-height: 100%;
}

.logo-container {
    flex: 0 0 auto;
}

.logo {
    width: 180px;
    height: 100px;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
}
.title-container {
    flex: 1;
    text-align: center;
}


th,
td {
    border: 1px solid black;
}

th,
td {
    padding: 8px;
    text-align: left;
}

.spacer {
    height: 50px;
}
thead.text-center > th:nth-child(3) {
width: 10%;
}
th.col-1:nth-child(1) {
width: 10%;
}
thead th:nth-child(3), tr td:nth-child(3) {
width: 10%;
}

th.col-3 {
width: 16%;
}

th.col-1:nth-child(6) {
width: 10%;
}

th.col-1:nth-child(5) {
width: 13%;
}

.centered {
  text-align: center;
}

</style>