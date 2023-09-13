<template>
    <div class="container">
        <br>
        <h1 class="h1 text-center">Selección de fecha de corte</h1>
        <div class="content-container">
            <v-date-picker is-expanded v-model="selectedDate" class="vdp-datepicker"></v-date-picker>
            <textarea placeholder="Escribe algo aquí..." class="textbox" rows="4" disabled></textarea>
        </div>
    
        <h1 class="h1 text-center mt-5">Historial de fechas de corte</h1>
        <div class="card bg-white borderCircle">
            <div class="card-body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Mostrar Filtros</label>
                </div>
                <div class="d-flex flex-row gap-3 justify-content-between align-items-center mt-3">
                    <div class="col-6 form-group">
                        <input type="text" class="form-control" id="search" :placeholder="'Buscar...'">
                    </div>
                    <div class="col-4">
                        <select class="form-select">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                
                <table class="table table-hover table-bordered mt-4">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th scope="col">Nombre</th>
                            <th scope="col">DUI</th>
                            <th scope="col">Cargo</th>
                            <th scope="col">Horas</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Juan Pérez</td>
                            <td>01234569-7</td>
                            <td>Desarrollador</td>
                            <td>12</td>
                            <td>31/07/2023</td>
                            <td>Activo</td>
                        </tr>
                        <tr>
                            <td>Juan Pérez</td>
                            <td>01234569-7</td>
                            <td>Desarrollador</td>
                            <td>12</td>
                            <td>31/07/2023</td>
                            <td>Activo</td>
                        </tr>
                    </tbody>
                </table>

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedDate: null
        };
    },
    methods: {
        // Puedes agregar tus métodos aquí 
    }
}
</script>

<style scoped>

.content-container {
    display: flex;
    justify-content: space-around; /* Distribuye el espacio alrededor de los elementos */
    align-items: center;
    width: 100%; /* Asegura que el contenedor ocupe todo el ancho disponible */
    max-width: 800px; /* Puedes ajustar este valor según tus preferencias */
    margin: 0 auto; /* Centra el contenedor en la página */
}

.textbox, .vdp-datepicker {
    flex: 1; /* Asegura que ambos elementos (calendario y cuadro de texto) ocupen el mismo espacio */
    padding: 10px;
    margin: 0 10px; /* Espacio entre los elementos */
}


.borderCircle {
    border-radius: 20px;
    border-color: white;
}
</style>