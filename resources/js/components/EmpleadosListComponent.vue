<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12">

        <div class="center-image d-flex flex-column justify-content-center align-items-center" style="margin-bottom: 2%">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-75" />
        </div>

        <!-- Acordeón de filtros -->
        <div class="container mt-4">
            <div class="accordion" id="accordionFilters">
                <div class="accordion-item">
                    <!-- Cabecera del acordeón -->
                    <h2 class="accordion-header" id="filters-headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filters-collapseOne" aria-expanded="true" aria-controls="filters-collapseOne">
                            FILTROS DE BUSQUEDA
                        </button>
                    </h2>
                    <!-- Cuerpo del acordeón (filtros) -->
                    <div id="filters-collapseOne" class="accordion-collapse collapse" aria-labelledby="filters-headingOne">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-2">
                                    <input v-model="filtros.nombre" @input="debounceSearchEmpleado" type="text"
                                        placeholder="Nombre" class="form-control mb-2" />

                                </div>
                                <div class="col-2">
                                    <input v-model="filtros.apellido" @input="debounceSearchEmpleado" type="text"
                                        placeholder="Apellido" class="form-control mb-2" />

                                </div>
                                <div class="col-2">
                                    <input v-model="filtros.dui" @input="debounceSearchEmpleado" type="text"
                                        placeholder="DUI" class="form-control mb-2" />
                                </div>
                                <div class="col-2">
                                    <input v-model="filtros.cargo" @input="debounceSearchEmpleado" type="text"
                                        placeholder="Cargo" class="form-control mb-2" />
                                </div>
                                <div class="col-2">
                                    <input v-model="filtros.email" @input="debounceSearchEmpleado" type="text"
                                        placeholder="Email" class="form-control mb-2" />
                                </div>
                                <div class="col-2">
                                    <div class="form-group d-flex" style="width: 100%;">
                                        <select v-model="filtros.selectedOption" @input="debounceSearchEmpleado"
                                            class="form-select">
                                            <option value="" disabled selected>Empresa</option>
                                            <option value="">No seleccionar</option>
                                            <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                                                {{ empresa.id }} - {{ empresa.nombre }}
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
            <h2 class="h1 text-center mt-5">LISTADO DE EMPLEADOS</h2>
            <table class="table table-hover table-bordered mt-4">
                <thead class="table-primary bg-primary">
                    <tr class="text-center">
                        <th scope="col">DUI</th>
                        <th scope="col">Nombres</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Email</th>
                        <th scope="col">Area</th>
                        <th scope="col">Empresa</th>
                        <th scope="col" class="actions-header">Acciones</th>
                    </tr>
                </thead>
                <tbody v-if="empleados.length > 0">
                    <tr class="text-center" v-for="empleado in empleados" :key="empleado.id">
                        <td>{{ empleado.dui }}</td>
                        <td>{{ empleado.nombres }}</td>
                        <td>{{ empleado.apellidos }}</td>
                        <td>{{ empleado.cargo }}</td>
                        <td>{{ empleado.email }}</td>
                        <td>{{ empleado.area.nombre }}</td>
                        <td>{{ empleado.area.empresa.nombre }}</td>
                        <td class="actions-cell">
                            <button @click="seleccionar" class="btn btn-primary" type="button">
                                Editar perfil
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
                if (this.filtros.selectedOption === 1) {
                    this.filtros.selectedOption = null;
                }
                debugger;
                if ((filtros.nombre === "") && (filtros.apellido === "") && (filtros.dui === "") && (filtros.cargo === "") && (filtros.email === "") && (filtros.selectedOption === "")) {
                    this.fetchEmpleados();
                } else {
                    const response = await axios.post("/empleados/filtro/busqueda", this.filtros);
                    this.empleados = response.data;
                }
            } catch (error) {
                console.error("Error al buscar empleados:", error.response ? error.response.data : error.message);
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
            axios
                .get("/empleados_area?page=" + this.currentPage)
                .then((response) => {
                    this.empleados = response.data.data;

                    this.lastPage = response.data.last_page;
                })
                .catch((error) => {
                    console.error("Error al cargar empresas:", error);
                });
        },
        seleccionar() {
            this.$router.push('/editarperfil');
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
