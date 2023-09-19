<template>
    <div class="w-100 d-flex flex-column justify-content-center align-items-center">
        <div class="w-75 d-flex flex-column justify-content-center align-items-center">
            <h1>Registro de Horas Extras</h1>
            <div id="drop-zone" class="col-11 d-flex flex-column justify-content-center align-items-center bg-light rounded-3 dropdrag-zone mt-5" @dragover="dragOver" @dragleave="dragLeave" @drop="drop">
                <input id="archivo" type="file" ref="file" class="input-file" :accept="'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel.sheet.macroEnabled.12'" @change="importarExcel">
                <transition name="slide-fade">
                    <div v-if="loaded == false" class="d-flex flex-column justify-content-center align-items-center">
                        <i class="fa-solid fa-file-excel text-primary" :style="'font-size: 60px'"></i>
                        <h5 class="mb-0 mt-2">Adjunte archivo de horas extras</h5>
                        <small>Solo se permite archivo Excel</small>
                    </div>
                    <div v-else class="col-12 d-flex flex-row justify-content-center align-items-center">
                        <div class="col-5 d-flex flex-row justify-content-end">
                            <i class="fa-solid fa-file-excel text-primary" :style="'font-size: 60px'"></i>
                        </div>
                        <div class="mx-3 col-5 d-flex flex-column justify-content-center align-items-start">
                            <div class="d-flex flex-row justify-content-start align-items-center">
                                <h5 class="mb-0">{{ fileExcel.name }}</h5>
                                <i class="fa-solid fa-circle-xmark mx-2 z-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remover archivo" @click="remove"></i>
                            </div>
                            <small>{{ fileExcel.size / 1000 }} kb</small>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
        <transition name="fade">
            <div class="col-11 d-flex flex-column" v-if="loaded">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                        <thead class="text-center bg-primary text-white align-middle">
                            <th scope="col">ID Empleado</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Sueldo</th>
                            <th scope="col">Diurnas</th>
                            <th scope="col">Nocturnas</th>
                            <th scope="col">Diurnas Descanso</th>
                            <th scope="col">Nocturnas Descanso</th>
                            <th scope="col">Diurnas Asueto</th>
                            <th scope="col">Nocturnas Asueto</th>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="registro in items" :key="registro.idEmpleado">
                                <td scope="row">{{ registro.idEmpleado }}</td>
                                <td>{{ registro.nombre }}</td>
                                <td>{{ registro.fecha }}</td>
                                <td>{{ registro.sueldo | toCurrency }}</td>
                                <td>{{ registro.diurnas }}</td>
                                <td>{{ registro.nocturnas }}</td>
                                <td>{{ registro.diurnasDescanso }}</td>
                                <td>{{ registro.nocturnasDescanso }}</td>
                                <td>{{ registro.diurnasAsueto }}</td>
                                <td>{{ registro.nocturnasAsueto }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import readXlsxFile from 'read-excel-file';
import moment from 'moment';
export default {
    data() {
        return {
            loaded: false,
            items: [],
            fileExcel: File,
            isDragging: false   
        }
    },
    mounted() {

    },
    methods: {
        importarExcel() {
            this.fileExcel = this.$refs.file.files[0];
            this.items = [];
            if (this.$refs.file.files.length > 1) {
                this.$toast.error("Seleccione únicamente un archivo", {
                    position: "top-right",
                    timeout: 3000,
                    closeOnClick: true,
                    pauseOnHover: true,
                    closeButton: "button",
                    icon: true
                });
            } else {
                if (this.fileExcel.type !== '') {
                    this.loaded = false;
                    this.$toast.error("Archivo Excel requerido", {
                        position: "top-right",
                        timeout: 3000,
                        closeOnClick: true,
                        pauseOnHover: true,
                        closeButton: "button",
                        icon: true
                    });
                }
                else {
                    this.loaded = true;
                    readXlsxFile(this.fileExcel, { sheet: 2 }).then((rows) => {
                        rows.forEach((element, index) => {
                            if (index > 4) {
                                let registroHora = {
                                    idEmpleado: element[0],
                                    nombre: element[1],
                                    fecha: element[2] == null ? element[2] : moment(element[2]).format('L'),
                                    sueldo: element[3],
                                    diurnas: element[4] == null ? 0 : element[4],
                                    nocturnas: element[5] == null ? 0 : element[5],
                                    diurnasDescanso: element[6] == null ? 0 : element[6],
                                    nocturnasDescanso: element[7] == null ? 0 : element[7],
                                    diurnasAsueto: element[8] == null ? 0 : element[8],
                                    nocturnasAsueto: element[9] == null ? 0 : element[9]
                                }
                                this.items.push(registroHora);
                            }
                        });
                    });
                }
            }
        },
        dragOver(e) {
            e.preventDefault();
            this.isDragging = true;
        },
        dragLeave(e) {
            this.isDragging = true;
        },
        drop(e) {
            e.preventDefault();
            this.$refs.file.files = e.dataTransfer.files;
            this.importarExcel();
            this.isDragging = false;
        },
        remove() {
            this.loaded = false;
            this.isDragging = false;
            this.$refs.file.files = null;
            this.fileExcel = new File();
            this.items = [];
        }
    }
}
</script>
<style>
    .dropdrag-zone{
        min-height: 150px;
        outline: 2px dashed gray;
        cursor: pointer;
        color:dimgray;
    }

    .input-file{
        opacity: 0;
        width: 70%;
        height: 150px;
        cursor: pointer;
        position: absolute;
    }
    
    thead th:nth-child(2), tr td:nth-child(2) {
        width: 25%;
    }
    thead th:nth-child(4), tr td:nth-child(4) {
        width: 6%;
    }
    thead th:nth-child(5), tr td:nth-child(5) {
        width: 6%;
    }
    thead th:nth-child(6), tr td:nth-child(6) {
        width: 8%;
    }

    .fade-enter-active .fade-leave-active{
        transition: opacity .5s;
    }

    .fade-enter .fade-leave-to{
        opacity: 0;
    }

    .slide-fade-enter-active{
        transition: all .3s ease;
    }

    .slide-fade-leave-active{
        transition: all .8s cubic-bezier(1.0,0.5,0.8,1.0);
    }

    .slide-fade-enter .slide-fade-leave-to{
        transform: translateX(10px);
        opacity: 0;
    }


</style>