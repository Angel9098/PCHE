<template>
    <div
        class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12"
    >
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

        <!-- Acordeón de filtros -->
        <div class="container mt-4">
            <div class="accordion" id="accordionFilters">
                <div class="accordion-item">
                    <!-- Cabecera del acordeón con estilos personalizados -->
                    <h2 class="accordion-header" id="filters-headingOne">
                        <button
                            class="accordion-button bg-gradient border-0 rounded-3"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#filters-collapseOne"
                            aria-expanded="true"
                            aria-controls="filters-collapseOne"
                        >
                            FILTROS DE BÚSQUEDA
                        </button>
                    </h2>
                    <!-- Cuerpo del acordeón (filtros) con estilos personalizados -->
                    <div
                        id="filters-collapseOne"
                        class="accordion-collapse collapse"
                        aria-labelledby="filters-headingOne"
                    >
                        <div class="accordion-body p-4 border-3d">
                            <div class="row">
                                <div class="col-2">
                                    <input
                                        v-model="filtros.nombre"
                                        @input="debounceSearchEmpleado"
                                        type="text"
                                        placeholder="Nombres"
                                        class="form-control mb-2"
                                    />
                                </div>
                                <div class="col-2">
                                    <input
                                        v-model="filtros.apellido"
                                        @input="debounceSearchEmpleado"
                                        type="text"
                                        placeholder="Apellidos"
                                        class="form-control mb-2"
                                    />
                                </div>
                                <div class="col-2">
                                    <input
                                        v-model="filtros.dui"
                                        @input="debounceSearchEmpleado"
                                        type="text"
                                        placeholder="DUI"
                                        class="form-control mb-2"
                                    />
                                </div>
                                <div class="col-2">
                                    <input
                                        v-model="filtros.cargo"
                                        @input="debounceSearchEmpleado"
                                        type="text"
                                        placeholder="Cargo"
                                        class="form-control mb-2"
                                    />
                                </div>
                                <div class="col-2">
                                    <input
                                        v-model="filtros.email"
                                        @input="debounceSearchEmpleado"
                                        type="text"
                                        placeholder="Email"
                                        class="form-control mb-2"
                                    />
                                </div>
                                <div class="col-2">
                                    <div
                                        class="form-group d-flex"
                                        style="width: 100%"
                                    >
                                        <select
                                            v-model="filtros.selectedOption"
                                            @input="debounceSearchEmpleado"
                                            class="form-select"
                                        >
                                            <option value="" disabled selected>
                                                Empresa
                                            </option>
                                            <option value="NA">
                                                No seleccionar
                                            </option>
                                            <option
                                                v-for="empresa in empresas"
                                                :key="empresa.id"
                                                :value="empresa.id"
                                            >
                                                {{ empresa.id }} -
                                                {{ empresa.nombre }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <h2
                class="h1 text-center mt-5"
                style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5)"
            >
                LISTADO DE EMPLEADOS
            </h2>
            <table class="table table-hover table-bordered mt-4">
                <thead class="table-primary bg-primary">
                    <tr class="text-center">
                        <th scope="col">DUI</th>
                        <th scope="col">Nombres</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Email</th>
                        <th scope="col">&#193;rea</th>
                        <th scope="col">Empresa</th>
                        <th scope="col" class="actions-header">Acciones</th>
                    </tr>
                </thead>
                <tbody v-if="empleados.length > 0">
                    <tr
                        class="text-center"
                        v-for="empleado in empleados"
                        :key="empleado.id"
                    >
                        <td>{{ empleado.dui }}</td>
                        <td>{{ empleado.nombres }}</td>
                        <td>{{ empleado.apellidos }}</td>
                        <td>{{ empleado.cargo }}</td>
                        <td>{{ empleado.email }}</td>
                        <td>{{ empleado.area.nombre }}</td>
                        <td>{{ empleado.area.empresa.nombre }}</td>
                        <td class="actions-cell">
                            <button
                                @click="seleccionar(empleado.id)"
                                class="btn btn-primary my-2"
                                type="button"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button
                                @click="eliminarEmpresa(empleado.id)"
                                class="btn btn-danger custom-btn"
                                type="button"
                            >
                                <i class="fas fa-trash-alt text-white"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="8" class="text-center">
                            No hay empleados para mostrar
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li
                        class="page-item"
                        :class="{ disabled: currentPage === 1 }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click.prevent="changePage(currentPage - 1)"
                            aria-label="Previous"
                        >
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li
                        class="page-item"
                        v-for="page in lastPage"
                        :key="page"
                        :class="{ active: page === currentPage }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click.prevent="changePage(page)"
                            >{{ page }}</a
                        >
                    </li>
                    <li
                        class="page-item"
                        :class="{ disabled: currentPage === lastPage }"
                    >
                        <a
                            class="page-link"
                            href="#"
                            @click.prevent="changePage(currentPage + 1)"
                            aria-label="Next"
                        >
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="row">
                <div class="col-9">
                </div>
                <div class="col-2">
                </div>
                <div class="col-1">
                    <div>
                        <button @click="exportar()" class="btn btn-custom btn-3d">Planilla</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

import ExcelJS from "exceljs";
export default {
    data() {
        return {
            empleados: [],
            empresas: [],
            calculosHoras: [],
            filtros: {
                nombre: "",
                apellido: "",
                dui: "",
                cargo: "",
                email: "",
                selectedOption: "",
            },

            currentPage: 1,
            lastPage: 1,
        };
    },
    created() {
        this.fetchEmpresas();
        this.fetchEmpleados();
    },
    methods: {
        debounceSearchEmpleado: _.debounce(function () {
            this.searchEmpleado();
        }, 300),
        async searchEmpleado() {
            try {
                if (this.filtros.selectedOption === "NA") {
                    this.filtros.selectedOption = "";
                }
                const response = await axios.post(
                    "/empleados/filtro/busqueda",
                    this.filtros
                );
                this.empleados = response.data.object;

            } catch (error) {
                console.error(
                    "Error al buscar empleados:",
                    error.response ? error.response.data : error.message
                );
            }
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
        fetchEmpleados() {
            axios.get("/empleados_area?page=" + this.currentPage)
                .then((response) => {
                    this.empleados = response.data.object.data;
                    this.lastPage = response.data.last_page;
                })
                .catch((error) => {
                    console.error("Error al cargar empresas:", error);
                });
        },
        seleccionar(id) {
            this.$router.push(`/registro/${id}`);
        },

        changePage(page) {
            this.currentPage = page;
            this.fetchEmpleados();
        },

        async exportar() {
            const response = await axios.post("planilla/quincenal");
            this.calculosHoras = response.data.object;

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet("Reporte de Horas Extras");

            const centeredStyle = {
                alignment: { horizontal: "center" },
            };
            const currencyStyle = {
                numFmt: "$ #,##0.00",
            };

            // Aplica los estilos a las columnas
            worksheet.columns = [
                { header: "ID Empleado", key: "id_empleado", width: 15, style: centeredStyle },
                { header: "Sueldo Mensual", key: "sueldo", width: 15, style: centeredStyle, numFmt: "$ #,##0.00" },
                { header: "Total Ganado", key: "TotalGanado", width: 15, style: centeredStyle, numFmt: "$ #,##0.00" },
                { header: "AFP", key: "afp", width: 15, style: centeredStyle, numFmt: "$ #,##0.00" },
                { header: "ISSS", key: "isss", width: 15, style: centeredStyle, numFmt: "$ #,##0.00" },
                { header: "Total a Pagar", key: "TotalPagar", width: 15, style: centeredStyle, numFmt: "$ #,##0.00" },
            ];

            this.calculosHoras.forEach((registro) => {
                // Agrega cada fila y aplica los estilos
                worksheet.addRow({
                    id_empleado: registro.dui,
                    sueldo: registro.sueldoMesual,
                    TotalGanado: registro.horasExtra,
                    afp: registro.afp,
                    isss: registro.isss,
                    TotalPagar: registro.TotalPagar,
                }).eachCell({ includeEmpty: true }, (cell, colNumber) => {
                    cell.style = centeredStyle;

                    // Aplica formato de moneda solo a las columnas que deseas
                    if ([2, 4, 5, 6, 7].includes(colNumber)) {
                        cell.numFmt = "$ #,##0.00";
                    }
                });
            });

            // Escribe el archivo y genera la descarga
            workbook.xlsx.writeBuffer().then((data) => {
                const blob = new Blob([data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                });
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "Reporte de Horas Extras.xlsx");
                document.body.appendChild(link);
                link.click();
            });
        },

        eliminarEmpresa(id) {
            this.$swal({
                title: "Estas seguro de eliminar el registro?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "¡Sí, bórralo!",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    let url = `empleados/eleminar?id=${id}`;
                    const response = await fetch(url, { method: "DELETE" });
                    if (!response.ok) {
                        this.$swal(
                            "Deleted!",
                            "Su registro ha sido eliminado.",
                            "success"
                        );
                        this.fetchEmpresas();
                        this.fetchEmpleados();
                    } else {
                        this.fetchEmpresas();
                        this.fetchEmpleados();
                    }
                }
            });
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
    width: 150px;
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

thead th:nth-child(3),
tr td:nth-child(3) {
    width: 10%;
}
</style>
