<template>
    <div class="bg-white d-flex flex-column justify-content-center align-items-center col-12 col-xs-12">
        <div class="center-image d-flex flex-column justify-content-center align-items-center" style="margin-bottom: 2%">
            <img src="assets/img/latinMobile.png" alt="logo" class="w-75" />
        </div>

        <div class="container">
            <h2 class="h1 text-center mt-5" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">LISTADO DE EMPRESAS</h2>
            <button @click="abrirModalAgregarEmpresa" class="btn btn-primary mt-3">
                Agregar Empresa
            </button>
            <table class="table table-hover table-bordered mt-4">
                <thead class="table-primary bg-primary">
                    <tr class="text-center">
                        <th scope="col">Nombre</th>
                        <th scope="col">Direcci&#243;n</th>
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
                                <button style="margin-right: 2%" @click="editarEmpresa(empresa.id)"
                                    class="btn btn-primary custom-btn" type="button">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="eliminarEmpresa(empresa.id)" class="btn btn-danger custom-btn"
                                    type="button">
                                    <i class="fas fa-trash-alt text-white"></i>
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
                        <a class="page-link" href="#" @click.prevent="changePage(page)">{{
                            page
                        }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === lastPage }">
                        <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="modal" id="modalAgregarEmpresa">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar Empresa</h5>
                            <button type="button" class="close" data-dismiss="modal" @click="cerrarModalAgregarEmpresa">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body gridClass">
                            <div class="p-4">
                                <picture class="mb-3">
                                    <source :srcset="defaultBooleand ? defaultImagen : perfil.imagen" type="image" />
                                    <img :src="defaultBooleand ? defaultImagen : perfil.imagen"
                                        class="img-fluid img-thumbnail cicle" alt="..." />
                                </picture>
                            </div>
                            <div class="p-4">
                                <div class="form-group">
                                    <div class="form-group  my-3">
                                        <label for="changeIMG">CAMBIAR LA IMAGEN</label>
                                        <input type="file" class="form-control" id="changeIMG"
                                            placeholder="CAMBIAR LA IMAGEN" @change="changesDefauld" />
                                    </div>
                                    <div class="row" style="margin-bottom: 2%">
                                        <div class="col-5">
                                            <label for="nombreEmpresa">Nombre de la Empresa</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" v-model="data.nombre" />
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 2%">
                                        <div class="col-5">
                                            <label for="nombreEmpresa">Direccion</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" v-model="data.direccion" />
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 2%">
                                        <div class="col-5">
                                            <label for="nombreEmpresa">Rubro</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" v-model="data.rubro" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                @click="cerrarModalAgregarEmpresa">
                                Cancelar
                            </button>
                            <button type="button" class="btn btn-primary" @click="guardarEmpresa">
                                Guardar
                            </button>
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
            defaultImagen:
                "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png",
            defaultBooleand: true,
            file: File,
            empresas: [],
            data: {
                imagen: File,
                nombre: "",
                direccion: "",
                id: "",
                rubro: "",
                fecha: "",
            },
            nombreEmpresa: "",
            currentPage: 1,
            lastPage: 1,
            perfil: {
                nombres: "",
                imagen: "",
            }
        };
    },
    created() {
        this.fetchEmpresas();
    },
    methods: {
        changesDefauld(event) {
            this.defaultBooleand = false;
            this.file = event.target.files[0];
            const files = event.target.files[0];
            this.data.imagen = this.file;
            this.perfil.imagen = URL.createObjectURL(files);
        },
        async dataGuardarEmpresa() {


            const formData = new FormData();
            formData.append('imagen', this.file, this.file.name);
            formData.append('nombre', this.data.nombre);
            formData.append('direccion', this.data.direccion);
            formData.append('rubro', this.data.rubro);

            const response = await axios.post("/empresa", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.status === 200) {
                this.$toast.success('Empresa creada con exito');
                this.cerrarModalAgregarEmpresa();

                this.fetchEmpresas();
            }
            this.data.nombre = "";
            this.data.direccion = "";
            this.data.rubro = "";
            this.file = null;
            this.file.name = null;


        },

        async editarEmpresa(empresaId) {
            const response = await axios.get(`/empresabyid?id=${empresaId}`);

            this.data.nombre = response.data[0].nombre;
            this.data.direccion = response.data[0].direccion;
            this.data.rubro = response.data[0].rubro;
            this.data.id = response.data[0].id
            $("#modalAgregarEmpresa").modal("show");

        },

        eliminarEmpresa(id) {

            axios
                .delete(`/empresas?id=${id}`)
                .then((response) => {
                    this.$toast.success('Empresa elimnada con exito');
                    this.fetchEmpresas();
                })
                .catch((error) => {
                    this.$toast.error('Empresa no ha podido ser eliminada');

                });

        },
        abrirModalAgregarEmpresa() {
            $("#modalAgregarEmpresa").modal("show");
        },
        cerrarModalAgregarEmpresa() {
            $("#modalAgregarEmpresa").modal("hide");
        },
        guardarEmpresa() {

            if (this.data.id != "") {
                this.actualizarEmpresa();
            } else {
                this.dataGuardarEmpresa();
            }
        },
        async actualizarEmpresa() {


            const formData = new FormData();

            if (this.file) {
                formData.append('imagen', this.file, this.file.name);
            }

            formData.append('nombre', this.data.nombre);
            formData.append('direccion', this.data.direccion);
            formData.append('rubro', this.data.rubro);
            formData.append('id', this.data.id);

            const response = await axios.post("/actualizar/empresa", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.status === 200) {
                this.$toast.success('Empresa Actualizada con exito');
                this.cerrarModalAgregarEmpresa();

                this.fetchEmpresas();
            }
            this.data.nombre = "";
            this.data.direccion = "";
            this.data.rubro = "";
            this.file = null;
            this.file.name = null;


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
                        fecha: empresa.created_at,
                    }));
                })
                .catch((error) => {
                    console.error("Error al cargar empresas:", error);
                });
        },
        seleccionar() {
            this.$router.push("/editarperfil");
        },

        changePage(page) {
            this.currentPage = page;
            this.fetchEmpresas();
        },
    },
};
</script>

<style scoped>
.gridClass {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
}

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
