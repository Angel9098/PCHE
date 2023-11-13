<template>
    <main class="container m-auto">
        <h1 class="text-center my-4 text-uppercase h1" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Preferencia de cuenta</h1>
        <article class="card">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-6">
                    <div class="floatCenter my-4">
                        <picture class="mb-3">
                            <source :srcset="defaultBooleand
                                    ? defaultImagen
                                    : perfil.imagen
                                " type="image" />
                            <img :src="defaultBooleand
                                    ? defaultImagen
                                    : perfil.imagen
                                " class="img-fluid img-thumbnail cicle" alt="SRC" />
                        </picture>

                        <div class="d-flex flex-row">
                            <p class="fw-bold">Nombre: {{ perfil.nombres }}</p>
                        </div>
                        <div class="d-flex flex-row">
                            <p class="fw-bold">
                                Cargo o puesto: {{ perfil.cargo }}
                            </p>
                        </div>
                        <div class="d-flex flex-row">
                            <p class="fw-bold">DUI: {{ perfil.dui }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-6">
                    <div class="container">
                        <form class="formContent">
                            <div class="mb-3">
                                <label for="changeIMG" class="form-label">Cambiar Imagen</label>
                                <input type="file" class="form-control" id="changeIMG" @change="changesDefauld" />
                            </div>
                            <div class="mb-3">
                                <label for="changeNombre" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="changeNombre" v-model="perfil.email"
                                    readonly />
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-sm-12 col-lg-6">
                                    <label for="contactoEmergencia" class="form-label">Contácto de Emergencia</label>
                                    <input type="text" class="form-control" id="contactoEmergencia"
                                        placeholder="Contacto de emergencia" v-model="perfil.avisar_contacto" readonly />
                                </div>
                                <div class="col-12 col-sm-12 col-lg-6">
                                    <label for="changePuesto" class="form-label">Número de Emergencia
                                    </label>
                                    <input type="tel" class="form-control" id="changePuesto" placeholder="(503) 7854 6985"
                                        v-model="perfil.numero_emergencia" readonly />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col my-2">
                                    <button @click="sendInfromation()" type="button" class="btn btn-dark"
                                        style="width: 100%">
                                        Guardar
                                        <i class="fa-solid fa-circle-plus"></i>
                                    </button>
                                </div>
                                <div class="col my-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" style="width: 100%">
                                        Contraseña
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
        <!-- ****** -->

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">
                            Actualizaci&#243;n de Contrase&#241;a
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <modal-change-password></modal-change-password>
                    </div>
                </div>
            </div>
        </div>
        <!-- ******* -->
    </main>
</template>

<style scoped>
.cicle {
    width: 200px;
    height: 200px;
    border-radius: 50%;
}

.floatCenter {
    display: flex;
    flex-direction: column;
    justify-items: center;
    align-items: center;
    border-right: 3px solid rgba(0, 0, 0, 0.726);
}

.formContent {
    margin: 10%;
}
</style>
<script>
import { createToastInterface } from "vue-toastification";

const pluginOptions = {
    timeout: 4000,
};

// Create the interface
const toast = createToastInterface(pluginOptions);

// Use it

import axios from "axios";

import ModalChangePassword from "./ModalChangePassword.vue";
export default {
    data() {
        return {
            defaultImagen:
                "storage/imagenes/blank-profile-picture-973460_1280.webp",
            defaultBooleand: true,
            MSErrorImgen: `
                Por favor, ten en cuenta que solo se permiten documentos en formato JPEG, PNG, GIF, WEBP, SVG. Asegúrate de cargar un archivo con la extensión correcta para continuar.
                `,
            file: File,
            perfil: {
                nombres: "",
                apellidos: "",
                cargo: "",
                dui: "",
                email: "",
                numero_emergencia: "",
                avisar_contacto: "",
                salario: "",
                imagen: "",
            },
        };
    },
    mounted() {
        this.leerData();
    },
    components: {
        ModalChangePassword,
    },
    methods: {
        leerData() {
            if (JSON.parse(localStorage.getItem("user")) !== null) {
                const { empleado_id, imagen } = JSON.parse(
                    localStorage.getItem("user")
                );
                axios
                    .get(`empleadobyid?idEmpleado=${empleado_id}`)
                    .then((result) => {

                        this.perfil = result.data.object[0];
                        this.perfil.nombres = `${result.data.object[0].nombres} ${result.data.object[0].apellidos}`;
                        document.title = `PCHE - Perfil usuario - ${this.perfil.nombres}`;
                    })
                    .catch((error) => { });
            }
        },
        changesDefauld(event) {
            if (
                event.target.files[0].type === "image/jpeg" ||
                event.target.files[0].type === "image/png" ||
                event.target.files[0].type === "image/gif" ||
                event.target.files[0].type === "image/bmp" ||
                event.target.files[0].type === "image/tiff" ||
                event.target.files[0].type === "image/webp" ||
                event.target.files[0].type === "image/svg+xml" ||
                event.target.files[0].type === "image/x-icon"
            ) {
                this.defaultBooleand = false;
                this.file = event.target.files[0];
                const files = event.target.files[0];
                this.perfil.imagen = URL.createObjectURL(files);
                if (this.perfil.imagen.length > 1) {
                    this.defaultBooleand = true;
                    this.defaultImagen = URL.createObjectURL(files);
                }
            } else {
                this.leerData();
                this.$toast.error(this.MSErrorImgen, {
                    timeout: 3000,
                    position: "bottom-center",
                    icon: true,
                });
            }
        },
        sendInfromation() {
            const { empleado_id } = JSON.parse(localStorage.getItem("user"));

            const sendFiles = new FormData();
            sendFiles.append("imagen", this.file, this.file.name);
            sendFiles.append("id", empleado_id);
            axios
                .post("/editarusuario", sendFiles)
                .then((response) => {
                    this.$store.dispatch("changeIMG");
                    localStorage.setItem(
                        "user",
                        String(JSON.stringify(response.data.message))
                    );

                    this.showToast("Actualizado");
                })
                .catch((error) => {
                    this.$toast.error("Algo salio mal", {
                        timeout: 3000,
                        position: "bottom-center",
                        icon: true,
                    });
                });

            // return new  Promise.all();
        },
        showToast(message) {
            this.$toast.success(message, {
                timeout: 3000,
                position: "bottom-center",
                icon: true,
            });
        },
    },
};
</script>
