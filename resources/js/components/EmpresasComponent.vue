<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12">

        <div class="center-image d-flex flex-column justify-content-center align-items-center" style="margin-bottom: 2%">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-75" />
        </div>

        <div class="container">
            <h2 class="h1 text-center mt-5">LISTADO DE EMPRESAS</h2>
            <button @click="abrirModalAgregarEmpresa" class="btn btn-primary mt-3">Agregar Empresa</button>
            <table class="table table-hover table-bordered mt-4">
                <thead class="table-primary bg-primary">
                    <tr class="text-center">
                        <th scope="col">Nombre</th>
                        <th scope="col">Direccion</th>
                        <th scope="col">Rubro</th>
                        <th scope="col">Fecha Registro</th>
                        <th scope="col" class="actions-header">Acciones</th>
                    </tr>
                </thead>
                <tbody v-if="empresas.length > 0">
                    <tr class="text-center" v-for="empresa in empresas" :key="empresa.id">
                        <td>{{ empresa.nombre }}</td>
                        <td>{{ empresa.direccion }}</td>
                        <td>{{ empresa.rubro }}</td>
                        <td>{{ formatFecha(empresa.fecha) }}</td>
                        <td class="actions-cell">
                            <div class="btn-group" role="group">
                                <button style="margin-right: 2%;" @click="editarEmpresa(empresa)"
                                    class="btn btn-primary custom-btn" type="button">
                                    Actualizar
                                </button>
                                <button @click="eliminarEmpresa(empresa.id)" class="btn btn-danger custom-btn"
                                    type="button">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="8" class="text-center">
                            No hay empresas para mostrar
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
            <div class="modal" id="modalAgregarEmpresa">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar Empresa</h5>
                            <button type="button" class="close" data-dismiss="modal" @click="cerrarModalAgregarEmpresa">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row" style="margin-bottom: 2%;">
                                    <div class="col-5">
                                        <label for="nombreEmpresa">Nombre de la Empresa</label>
                                    </div>
                                    <div class="col-7">
                                        <input type="text" class="form-control" v-model="data.nombre">
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 2%;">
                                    <div class="col-5">
                                        <label for="nombreEmpresa">Direccion</label>
                                    </div>
                                    <div class="col-7">
                                        <input type="text" class="form-control" v-model="data.direccion">
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 2%;">
                                    <div class="col-5">
                                        <label for="nombreEmpresa">Rubro</label>
                                    </div>
                                    <div class="col-7">
                                        <input type="text" class="form-control" v-model="data.rubro">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                @click="cerrarModalAgregarEmpresa">Cancelar</button>
                            <button type="button" class="btn btn-primary" @click="guardarEmpresa">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

import moment from "moment";
export default {
    data() {
        return {
            empresas: [],
            data: {
                nombre: "",
                direccion: "",
                rubro: "",
                fecha: "",
            },
            nombreEmpresa: "",
            currentPage: 1,
            lastPage: 1,
        };
    },
    created() {
        this.fetchEmpresas();
    },
    methods: {

        async dataGuardarEmpresa() {
            try {
                console.log(this.data);
                const response = await axios.post("/empleados/busqueda", this.data);
                this.empleados = response.data;
            } catch (error) {
                console.error("Error al agregar empresa:", error.response ? error.response.data : error.message);
            }
        },

        editarEmpresa(empresa) {
            this.data.nombre = empresa.nombre;
            this.data.direccion = empresa.direccion;
            this.data.rubro = empresa.rubro;
            $("#modalAgregarEmpresa").modal("show");
        },
        eliminarEmpresa(id) {
            if (confirm("¿Estás seguro de que deseas eliminar esta empresa?")) {
                axios
                    .delete(`/empresas/${id}`)
                    .then(() => {
                        this.fetchEmpresas();
                    })
                    .catch((error) => {
                        console.error("Error al eliminar empresa:", error);
                    });
            }
        },
        abrirModalAgregarEmpresa() {
            $("#modalAgregarEmpresa").modal("show");
        },
        cerrarModalAgregarEmpresa() {
            $("#modalAgregarEmpresa").modal("hide");
        },
        guardarEmpresa() {
            this.dataGuardarEmpresa();

        },
        formatFecha(date) {
            return moment(date).format("DD/MM/YYYY");
        },
        fetchEmpresas() {
            axios
                .get("/empresas")
                .then((response) => {
                    this.empresas = response.data.map((empresa) => ({
                        id: empresa.id,
                        nombre: empresa.nombre,
                        direccion: empresa.direccion,
                        rubro: empresa.rubro,
                        fecha: empresa.created_at
                    }));
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
            this.fetchEmpresas();
        }
    },
};
</script>

<style scoped>
.spacer-cell {
    width: 20px;
}

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
