<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12">

        <div class="center-image d-flex flex-column justify-content-center align-items-center" style="margin-bottom: 1%">
            <h2 class="h1 text-center mt-5" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">HISTORIAL DE HORAS EXTRAS
            </h2>
        </div>
        <div class="container mt-4">
            <h2 style="text-align: left;" class="mt-5">FILTROS DE BUSQUEDA</h2>
        </div>
        <div class="container mt-4">
            <div class="row" style="margin-bottom: 2%;">
                <div class="col-3">
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
                <div class="col-3">
                    <div class="row">
                        <div class="col-3">
                            <label for="fechaHasta">Desde:</label>
                        </div>
                        <div class="col-9">
                            <input v-model="filtros.fechaDesde" @input="debouncefiltros" type="date" id="fechaHasta"
                                class="form-control mb-2">
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="row">
                        <div class="col-3">
                            <label for="fechaHasta">Hasta:</label>
                        </div>
                        <div class="col-9">
                            <input v-model="filtros.fechaHasta" @input="debouncefiltros" type="date" id="fechaHasta"
                                class="form-control mb-2">
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-2">
                    <input v-model="duiJefe" type="text" @input="debouncefiltros" placeholder="Dui jefe"
                        class="form-control mb-2" disabled />
                </div>
                <div class="col-4">
                    <input v-model="nombreJefe" type="text" @input="debouncefiltros" placeholder="Nombre jefe"
                        class="form-control mb-2" disabled />
                </div>
                <div class="col-3">
                    <input v-model="email" type="text" @input="debouncefiltros" placeholder="Email"
                        class="form-control mb-2" disabled />
                </div>
            </div>
        </div>

        <div class="col-12 d-flex flex-column justify-content-center align-items-center">
            <div class="row">

            </div>
            <div class="col-11 d-flex flex-column">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                        <thead class="text-center bg-primary text-white">
                            <th scope="col" class="col-1">ID Empleado</th>
                            <th scope="col" class="col-4">Nombre</th>
                            <th scope="col">Fecha</th>
                            <th scope="col" class="col-2">Sueldo</th>
                            <th scope="col" class="fixed-width">Diurnas</th>
                            <th scope="col" class="fixed-width">Nocturnas</th>
                            <th scope="col" class="fixed-width">Diurnas Descanso</th>
                            <th scope="col" class="fixed-width">Nocturnas Descanso</th>
                            <th scope="col" class="fixed-width">Diurnas Asueto</th>
                            <th scope="col" class="fixed-width">Nocturnas Asueto</th>
                        </thead>


                        <tbody class="text-center" v-if="horasExtras.length > 0">
                            <tr v-for="registro in horasExtras" :key="registro.id">
                                <td scope="row">{{ registro.empleado.dui }}</td>
                                <td>{{ registro.empleado.nombres + " " + registro.empleado.apellidos }}</td>
                                <td>{{ formatFecha(registro.fecha_registro) }}</td>
                                <td>{{ registro.empleado.salario | toCurrency }}</td>
                                <td class="centered">{{ registro.diurnas !== 0 ? registro.diurnas : '-' }}</td>
                                <td class="centered">{{ registro.nocturnas !== 0 ? registro.nocturnas : '-' }}</td>
                                <td class="centered">{{ registro.diurnas_descanso !== 0 ? registro.diurnas_descanso : '-' }}
                                </td>
                                <td class="centered">{{ registro.nocturnas_descanso !== 0 ? registro.nocturnas_descanso :
                                    '-' }}</td>
                                <td class="centered">{{ registro.diurnas_asueto !== 0 ? registro.diurnas_asueto : '-' }}
                                </td>
                                <td class="centered">{{ registro.nocturnas_asueto !== 0 ? registro.nocturnas_asueto : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td>{{ totalsueldo | toCurrency }}</td>
                                <td class="centered">{{ totalDiurnas !== 0 ? totalDiurnas : '-' }}</td>
                                <td class="centered">{{ totalNocturnas !== 0 ? totalNocturnas : '-' }}</td>
                                <td class="centered">{{ totalDiurnasDescanso !== 0 ? totalDiurnasDescanso : '-' }}</td>
                                <td class="centered">{{ totalNocturnasDescanso !== 0 ? totalNocturnasDescanso : '-' }}</td>
                                <td class="centered">{{ totalDiurnasAsueto !== 0 ? totalDiurnasAsueto : '-' }}</td>
                                <td class="centered">{{ totalNocturnasAsueto !== 0 ? totalNocturnasAsueto : '-' }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="10" class="text-center">
                                    No hay registros para mostrar
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-11 d-flex flex-row">
                <!-- Mueve el navegador de paginación aquí, debajo de la tabla -->
                <nav aria-label="Page navigation example" class="mt-4">
                    <ul class="pagination">
                        <li class="page-item" :class="{ disabled: currentPage === 1 }">
                            <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item" v-for="page in lastPage" :key="page"
                            :class="{ active: page === currentPage }">
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

        <div class="row container">
            <div class="col-12 d-flex flex-row justify-content-end gap-4">
                <button @click="procesarCalculos()" class="btn btn-custom btn-3d">Procesar</button>
                <button @click="generatePDF()" class="btn btn-custom btn-3d">Generar PDF</button>
            </div>
            <div class="col-1">
            </div>
        </div>
        <div class="pdf-content">
            <!-- Contenido para el PDF -->
            <div v-if="showPdfTemplate" ref="pdfContent" class="pdf-wrapper">
                <!-- Encabezado -->
                <div class="header">
                    <div class="logo-container">
                        <img src="/assets/img/latinMobile.png" alt="logo" class="logo">
                    </div>
                    <div class="title-container">
                        <h2 class="title">PCHE</h2>
                        <h4>Reporte de Horas de Empleados</h4>
                        <p v-if="EmpresaSelect != undefined">Empresa: {{ EmpresaSelect.nombre }}</p>
                        <p v-else>Empresas: Todas</p>
                    </div>
                    <div class="area-date-container">
                        <p v-if="AreaSelect != undefined"> Área: {{ AreaSelect.nombre }}</p>
                        <p v-else>Áreas: Todas</p>
                        <p class="date">Fecha de Emisión: {{ currentDate }}</p>
                    </div>
                </div>

                <!-- Tabla con las 7 columnas -->
                <div class="col-12">
                    <div class="col-12 d-flex flex-column">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                                <thead class="text-center bg-primary text-white">
                                    <th scope="col" class="col-1">ID Empleado</th>
                                    <th scope="col" class="col-3">Nombre</th>
                                    <th scope="col">Fecha</th>
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
                                        <td>{{ registro.empleado.nombres + " " + registro.empleado.apellidos }}</td>
                                        <td>{{ formatFecha(registro.fecha_registro) }}</td>
                                        <td>{{ registro.empleado.salario | toCurrency }}</td>
                                        <td class="centered">{{ registro.diurnas !== 0 ? registro.diurnas : '-' }}</td>
                                        <td class="centered">{{ registro.nocturnas !== 0 ? registro.nocturnas : '-' }}</td>
                                        <td class="centered">{{ registro.diurnas_descanso !== 0 ? registro.diurnas_descanso
                                            : '-' }}</td>
                                        <td class="centered">{{ registro.nocturnas_descanso !== 0 ?
                                            registro.nocturnas_descanso : '-' }}</td>
                                        <td class="centered">{{ registro.diurnas_asueto !== 0 ? registro.diurnas_asueto :
                                            '-' }}</td>
                                        <td class="centered">{{ registro.nocturnas_asueto !== 0 ? registro.nocturnas_asueto
                                            : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>Total</td>
                                        <td>{{ totalsueldo | toCurrency }}</td>
                                        <td class="centered">{{ totalDiurnas !== 0 ? totalDiurnas : '-' }}</td>
                                        <td class="centered">{{ totalNocturnas !== 0 ? totalNocturnas : '-' }}</td>
                                        <td class="centered">{{ totalDiurnasDescanso !== 0 ? totalDiurnasDescanso : '-' }}
                                        </td>
                                        <td class="centered">{{ totalNocturnasDescanso !== 0 ? totalNocturnasDescanso : '-'
                                        }}</td>
                                        <td class="centered">{{ totalDiurnasAsueto !== 0 ? totalDiurnasAsueto : '-' }}</td>
                                        <td class="centered">{{ totalNocturnasAsueto !== 0 ? totalNocturnasAsueto : '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            No hay registros para mostrar
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Espaciador para separar la tabla del pie de página -->
                    <div class="spacer"></div>
                    <div class="row">
                        <div class="col-4">
                            <p>Emitido por: {{ nombre }} </p>
                        </div>
                        <div class="col-4" style="text-align: center;">
                            <p>Revisado por:_____________________ </p>
                        </div>
                        <div class="col-4" style="text-align: right;">
                            <p>Aprobado por:_____________________</p>
                        </div>
                    </div>


                    <!-- Paginación como pie de página -->
                    <div class="col-12 ">
                        <p>Página {{ currentPage }} de {{ totalPages }}</p>
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

export default {
    data() {
        return {
            empleados: [],
            empresas: [],
            horasExtras: [],
            areas: [],
            duiJefe: "",
            nombreJefe: "",
            nombres: "",
            email: "",
            filtros: {
                selectEmpresa: "",
                selectArea: "",
                fechaDesde: "",
                fechaHasta: "",
            },
            user: {},
            currentPage: 1,
            lastPage: 1,
            showPdfTemplate: false,
            currentDate: new Date().toLocaleDateString(),
            totalPages: 1,
            EmpresaSelect: {},
            AreaSelect: {},
            nombre: "",
        };
    },
    created() {
        this.fetchEmpresas();
        this.buscarRegistrosByEmpresa()
        if (localStorage.getItem("userInfo") != null) {
            this.user = JSON.parse(localStorage.getItem("userInfo"));
            this.nombre = this.user.nombres + " " + this.user.apellidos;

        }
    },
    methods: {
        debounceSearchArea: _.debounce(function () {
            if (this.filtros.selectEmpresa === "no") {
                this.filtros.selectEmpresa = "";
                this.areas = [];
                this.filtros.selectArea = "";
                this.EmpresaSelect = {};
                this.AreaSelect = {};
            }
            this.EmpresaSelect = this.empresas.find(empresa => empresa.id == this.filtros.selectEmpresa);
            this.buscarArea();
            this.buscarRegistrosByEmpresa(this.filtros);

        }, 300),
        debouncefiltros: _.debounce(function () {

            this.buscarRegistrosByEmpresa(this.filtros);

        }, 300),
        debounceSearchEmpleado: _.debounce(function () {
            if (this.filtros.selectArea === "no") {
                this.filtros.selectArea = "";
                this.duiJefe = "";
                this.email = "";
                this.nombreJefe = "";
                this.AreaSelect = {};

            }
            const areaId = this.filtros.selectArea;
            this.searchAreaById(areaId);
            this.buscarRegistrosByEmpresa(this.filtros);
            this.AreaSelect = this.areas.find(area => area.id == this.filtros.selectArea);
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
            const response = await axios.get("areabyid?id=" + areaId);
            if (response.data != null) {
                const response2 = await axios.get("findempleadobyid?id=" + response.data.jefe_area);
                this.duiJefe = response2.data.dui;
                this.nombreJefe = response2.data.nombres + " " + response2.data.apellidos;
                this.email = response2.data.email;
            }
        },

        async fetchEmpresas() {
            try {
                const response = await axios.get("/empresas");
                this.empresas = response.data.map((empresa) => ({
                    id: empresa.id,
                    nombre: empresa.nombre,
                }));
            } catch (error) {
                console.error("Error al cargar empresas:", error);
            }
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


        async fetchHorasExtras() {
            const response = await axios.post("horas_extra");
            this.horasExtras = response.data;
        },
        async buscarRegistrosByEmpresa() {
            const response = await axios.post("horas_extra", this.filtros);
            this.horasExtras = response.data;
            // Calcular los totales
            this.totalsueldo = 0;
            this.totalDiurnas = 0;
            this.totalNocturnas = 0;
            this.totalDiurnasDescanso = 0;
            this.totalNocturnasDescanso = 0;
            this.totalDiurnasAsueto = 0;
            this.totalNocturnasAsueto = 0;

            this.horasExtras.forEach(element => {
                element.empleado.salario = Number(element.empleado.salario);
                this.totalsueldo += element.empleado.salario;
                this.totalDiurnas += element.diurnas;
                this.totalNocturnas += element.nocturnas;
                this.totalDiurnasDescanso += element.diurnas_descanso;
                this.totalNocturnasDescanso += element.nocturnas_descanso;
                this.totalDiurnasAsueto += element.diurnas_asueto;
                this.totalNocturnasAsueto += element.nocturnas_asueto;
            });
        },
        async procesarCalculos() {

            const response = await axios.post("calculo_horas", this.horasExtras);
            var mensaje = response.data;
            this.$toast.success('Calculos realizados');
            this.buscarRegistrosByEmpresa(this.filtros);

        },
        changePage(page) {
            this.currentPage = page;
            this.fetchEmpleados();
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

.textbox,
.vdp-datepicker {
    flex: 1;
    padding: 10px;
    margin: 0 10px;
}

.btn-custom {
    background-image: linear-gradient(to bottom, #06495a, #05333f);
    color: #fff;
    border-color: #033b47;
}

.btn-custom:hover {
    background-image: linear-gradient(to bottom, #083946, #052831);
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

.pdf-wrapper {
    position: relative;
    min-height: 100%;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
}

.logo-container {
    flex: 0 0 auto;
}

.logo {
    width: 180px;
    height: 100px;
}

.title-container {
    flex: 1;
    text-align: center;
}

.title,
h4 {
    margin: 0;
}

h4 {
    margin-top: 20px;
}

.company-name {
    font-size: 1.2em;
    margin: 0;
    text-align: center;
}

.area-date-container {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.area,
.date {
    margin: 0;
}

.table-margin {
    margin: 20px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table,
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

thead.text-center>th:nth-child(3) {
    width: 10%;
}

th.col-1:nth-child(1) {
    width: 10%;
}

thead th:nth-child(3),
tr td:nth-child(3) {
    width: 10%;
}

.pagination {
    bottom: 0;
    right: 0;
    margin-top: 20px;
    font-size: 0.9em;
}

.centered {
    text-align: center;
}

.fixed-width {
    width: 5%;
}</style>
