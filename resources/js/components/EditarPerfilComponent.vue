<template>
    <main class="container m-auto">
        <h1 class="text-center my-4">Preferencia de cuenta</h1>
        <article class="card">
            <div class="floatCenter">
                <h2 class="text-center my-3">Vista previa</h2>
                <picture class="mb-3">
                    <source
                        :srcset="
                            defaultBooleand ? defaultImagen : perfilIMG.imagen
                        "
                        type="image"
                    />
                    <img
                        :src="
                            defaultBooleand ? defaultImagen : perfilIMG.imagen
                        "
                        class="img-fluid img-thumbnail cicle"
                        alt="..."
                    />
                </picture>

                <p class="font-weight-bold">NOMBRE DE PÉRFIL :</p>
                <p>{{ nombres }}</p>
                <p class="font-weight-bold">CARGO O PUESTO :</p>
                <p>{{ perfil.cargo }}</p>
                <p class="font-weight-bold">DUI :</p>
                <p>{{ perfil.dui }}</p>
            </div>
            <div>
                <form class="formContent">
                    <div class="form-group">
                                <label for="changeIMG">CAMBIAR LA IMAGEN</label>
                                <input
                                    type="file"
                                    class="form-control"
                                    id="changeIMG"
                                    placeholder="CAMBIAR LA IMAGEN"
                                    @change="changesDefauld"
                                />
                            </div>
                    <div class="row my-3">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="changeNombre">NOMBRE</label>
                                <input
                                    type="fiTEXTe"
                                    class="form-control"
                                    id="changeNombre"
                                    placeholder="CAMBIAR EL NOMBRE"
                                    v-model="nombres"
                                />
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="changeNombre">EMAIL</label>
                                <input
                                    type="fiTEXTe"
                                    class="form-control"
                                    id="changeNombre"
                                    placeholder="CAMBIAR EL NOMBRE"
                                    v-model="perfil.email"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- CAMBIAR LA IMAGEN -->

                    <!-- CAMBIAR EL NOMBRE -->
                    <div class="row my-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="changePuesto">PUESTO O CARGO</label>
                                <input
                                    type="fiTEXTe"
                                    class="form-control"
                                    id="changePuesto"
                                    placeholder="CAMBIAR PUESTO O CARGO"
                                    v-model="perfil.cargo"
                                />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="changeNombre" class="text-uppercase"
                                    >contacto de emergencia</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="changeNombre"
                                    placeholder="Contacto de emergencia"
                                    v-model="perfil.avisar_contacto"
                                    readonly
                                />
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col">
                                <div class="form-group">
                                    <label
                                        for="changePuesto"
                                        class="text-uppercase"
                                        >Número de emergencia</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="changePuesto"
                                        placeholder="(503) 7854 6985"
                                        v-model="perfil.numero_emergencia"
                                        readonly
                                    />
                                </div>
                            </div>
                            <div class="col">
                                <label for="changePuesto" class="text-uppercase"
                                    >Dui</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Last name"
                                    v-model="perfil.dui"
                                    readonly
                                />
                            </div>
                        </div>
                    </div>
                    <!-- CAMBIAR PUESTO O CARGO -->

                    <!-- DESCRIPCION DEL PUESTO -->

                    <div class="row">
                        <div class="col">
                            <button
                                @click="sendInfromation()"
                                type="button"
                                class="btn btn-dark"
                            >
                                GUARDAR CAMBIOS
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </div>
                        <div class="col">
                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                            >
                                Cambiar contraseña
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
            nombres: "",
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
            perfilIMG: {
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
                const empleadoId = JSON.parse(localStorage.getItem("user"));
                this.perfilIMG.imagen = empleadoId.imagen;
                axios
                    .get(`empleadobyid?idEmpleado=${empleadoId.empleado_id}`)
                    .then((result) => {
                        this.nombres = ` ${result.data[0].nombres} ${result.data[0].apellidos}`;
                        this.perfil = result.data[0];
                    })
                    .catch((error) => {});
            }
        },
        changesDefauld(event) {
            this.defaultBooleand = false;
            this.file = event.target.files[0];

            const files = event.target.files[0];
            this.perfilIMG.imagen = URL.createObjectURL(files);
        },
        sendInfromation() {
            const sendFiles = new FormData();
            sendFiles.append("imagen", this.file, this.file.name);
            axios
                .post("/subir_archivo", sendFiles)
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
