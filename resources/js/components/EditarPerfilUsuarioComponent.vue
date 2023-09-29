<template>
    <main class="container m-auto">
        <h1 class="text-center my-4 text-uppercase">Preferencia de cuenta</h1>
        <article class="card">
            <div class="floatCenter">
                <picture class="mb-3">
                    <source
                        :srcset="
                            defaultBooleand ? defaultImagen : perfil.imagen
                        "
                        type="image"
                    />
                    <img
                        :src="defaultBooleand ? defaultImagen : perfil.imagen"
                        class="img-fluid img-thumbnail cicle"
                        alt="..."
                    />
                </picture>

                <div class="d-flex flex-row">
                    <p class="fw-bold">Nombre: {{ perfil.nombres }}</p>
                </div>
                <div class="d-flex flex-row">
                    <p class="fw-bold">Cargo o puesto: {{ perfil.cargo }}</p>
                </div>
                <div class="d-flex flex-row">
                    <p class="fw-bold">DUI: {{ perfil.dui }}</p>
                </div>
            </div>
            <div class="container">
                <form class="formContent">
                    <div class="mb-3">
                        <label for="changeIMG" class="form-label"
                            >Cambiar Imagen</label
                        >
                        <input
                            type="file"
                            class="form-control"
                            id="changeIMG"
                            @change="changesDefauld"
                        />
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-sm-12 col-lg-6">
                            <label for="changeNombre" class="form-label"
                                >Email</label
                            >
                            <input
                                type="email"
                                class="form-control"
                                id="changeNombre"
                                v-model="perfil.email"
                                readonly
                            />
                        </div>
                        <div class="col-12 col-sm-12 col-lg-6">
                            <label for="changePuesto" class="form-label"
                                >Número de Emergencia</label
                            >
                            <input
                                type="tel"
                                class="form-control"
                                id="changePuesto"
                                placeholder="(503) 7854 6985"
                                v-model="perfil.numero_emergencia"
                                readonly
                            />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="contactoEmergencia" class="form-label"
                            >Contacto de Emergencia</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            id="contactoEmergencia"
                            placeholder="Contacto de emergencia"
                            v-model="perfil.avisar_contacto"
                            readonly
                        />
                    </div>

                    <div class="row">
                        <div class="col my-2">
                            <button
                                @click="sendInformation()"
                                type="button"
                                class="btn btn-dark"
                            >
                                Guardar Cambios
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </div>
                        <div class="col my-2">
                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                            >
                                Cambiar Contraseña
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </article>
        <!-- ****** -->

        <div
            class="modal fade"
            id="exampleModal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Nombre de persona
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
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
.card {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
}
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
import axios from "axios";

import ModalChangePassword from "./ModalChangePassword.vue";
export default {
    data() {
        return {
            defaultImagen:
                "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png",
            defaultBooleand: true,
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
                const empleadoId = JSON.parse(localStorage.getItem("user"));
                axios
                    .get(`empleadobyid?idEmpleado=${empleadoId.empleado_id}`)
                    .then((result) => {
                        this.perfil.nombres = `${result.data[0].nombres} ${result.data[0].apellidos}`;
                        this.perfil = result[0].data;
                    })
                    .catch((error) => {});
            }
        },
        changesDefauld(event) {
            this.defaultBooleand = false;
            this.file = event.target.files[0];
            const files = event.target.files[0];
            this.perfil.imagen = URL.createObjectURL(files);
        },
        sendInfromation() {
            const sendFiles = new FormData();
            sendFiles.append("imagen", this.file, this.file.name);

            axios
                .post("/editarusuario", sendFiles)
                .then((response) => {
                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });

            // return new  Promise.all();
        },
    },
};
</script>
