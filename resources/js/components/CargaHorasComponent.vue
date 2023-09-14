<template>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="w-75 d-flex flex-column justify-content-center align-items-center">
            <h1>Registro de Horas Extras</h1>
            <div class="col-11 d-flex flex-column justify-content-center align-items-center bg-light rounded-3 dropdrag-zone mt-5">
                <input id="archivo" type="file" class="input-file" :accept="'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'" @change="importarExcel">
                <i class="fa-solid fa-file-excel text-primary" :style="'font-size: 60px'"></i>
                <h5 class="mb-0 mt-2">Adjunte archivo de horas extras</h5>
                <small>Solo se permite archivo Excel</small>
            </div>
        </div>
        <div class="col-11 d-flex flex-column">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm mt-4 align-middle">
                    <thead class="text-center bg-primary text-white">
                        <th scope="col">ID Empleado</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Diurnas</th>
                        <th scope="col">Nocturnas</th>
                        <th scope="col">Diurnas Descanso</th>
                        <th scope="col">Nocturnas Descanso</th>
                        <th scope="col">Diurnas Asueto</th>
                        <th scope="col">Nocturnas Asueto</th>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr v-for="registro in items" :key="registro.idEmpleado">
                            <td scope="row">{{ registro.idEmpleado }}</td>
                            <td>{{ registro.nombre }}</td>
                            <td>{{ registro.fecha }}</td>
                            <td>{{ registro.sueldo }}</td>
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
    </div>
</template>
<script>
import readXlsxFile from 'read-excel-file'
export default {
    data() {
        return {
            horas: '',
            items: []   
        }
    },
    mounted() {
        
    },
    methods: {
        importarExcel() {
            const input = document.getElementById('archivo');
            readXlsxFile(input.files[0],{sheet: 2}).then((rows) => {
                rows.forEach((element, index) => {
                    if (index > 4) {
                        let registroHora = {
                            idEmpleado: element[0],
                            nombre: element[1],
                            fecha: element[2],
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
                console.log(this.items)
            });
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
        width: 75%;
        height: 150px;
        cursor: pointer;
        position: absolute;
    }
</style>