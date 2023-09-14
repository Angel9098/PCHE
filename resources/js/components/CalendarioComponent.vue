<template>
    <div class="container">
        <br>
        <h2 class="h1 text-center">SELECCIÓN DE FECHA DE CORTE</h2>
        <br>
        <div class="content-container">
            <v-date-picker is-expanded v-model="selectedDate" class="vdp-datepicker"></v-date-picker>
            <textarea placeholder=" " class="textbox" rows="10" disabled></textarea>
        </div>
    
        <h2 class="h1 text-center mt-5">HISTORIAL DE FECHAS DE CORTE</h2>
        <table class="table table-hover table-bordered mt-4">
            <thead class="table-primary bg-primary">
                <tr class="text-center">
                    <th scope="col">Descripción</th>
                    <th scope="col">Fecha de corte</th>
                    <th scope="col">Fecha de creación</th>
                </tr>
            </thead>
            <tbody v-if="cortes.length > 0">
                <tr class="text-center" v-for="corte in cortes" :key="corte.id">
                    <td>{{ corte.descripcion }}</td>
                    <td>{{ formatFecha(corte.fecha_corte) }}</td>
                    <td>{{ formatFecha(corte.created_at) }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="3" class="text-center">No hay fechas de corte para mostrar</td>
                </tr>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item" v-for="page in lastPage" :key="page" :class="{ 'active': page === currentPage }">
                    <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                </li>
                <li class="page-item" :class="{ 'disabled': currentPage === lastPage }">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="corteModal" tabindex="-1" aria-labelledby="corteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="corteModalLabel">Agregar fecha de corte</h5>
                        <button type="button" class="close" @click="cerrarModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" v-model="descripcionCorte">
                        </div>
                        <p v-if="errorDescripcion" class="text-danger">La descripción no puede estar vacía.</p>
                        <p v-if="errorSubmit" class="text-danger">{{ errorSubmit }}</p>
                        <p>Fecha seleccionada: {{ selectedDate }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="enviarCorte">Agregar fecha de corte</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
    data() {
        return {
            selectedDate: null,
            cortes: [],
            currentPage: 1,
            lastPage: 1,
            descripcionCorte: '',
            errorDescripcion: false,
            errorSubmit: null
        };
    },
    watch: {
        selectedDate(newDate) {
            if (newDate) {
                $('#corteModal').modal('show');
            }
        }
    },
    created() {
        this.getCortes();
    },
    methods: {
        cerrarModal() {
            $('#corteModal').modal('hide');
        },
        async getCortes() {
            try {
                const response = await axios.get('/cortes?page=' + this.currentPage);
                this.cortes = response.data.data;
                this.lastPage = response.data.last_page;
            } catch (error) {
                console.error("Error al obtener los cortes:", error.response ? error.response.data : error.message);
            }
        },
        changePage(page) {
            this.currentPage = page;
            this.getCortes();
        },
        async enviarCorte() {
            if (!this.descripcionCorte.trim()) {
                this.errorDescripcion = true;
                return;
            } else {
                this.errorDescripcion = false;
            }

            try {
                const response = await axios.post('/cortes/crear', {
                    descripcion: this.descripcionCorte,
                    fecha_corte: this.selectedDate
                });
                this.descripcionCorte = '';
                this.errorSubmit = null;
                $('#corteModal').modal('hide');
                this.getCortes();
            } catch (error) {
                this.errorSubmit = "Error: no se pudo guardar la fecha de corte.";
                setTimeout(() => {
                    $('#corteModal').modal('hide');
                    this.selectedDate = null;
                    this.descripcionCorte = '';
                    this.errorSubmit = null;
                }, 5000);
            }
        },
        formatFecha(date) {
            return moment(date).format('DD/MM/YYYY');
        }
    }
}
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

.textbox, .vdp-datepicker {
    flex: 1;
    padding: 10px;
    margin: 0 10px;
}

.borderCircle {
    border-radius: 20px;
    border-color: white;
}
</style>
