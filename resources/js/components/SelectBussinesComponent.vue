<template>
  <div class="w-100 d-flex justify-content-center align-items-center" style="height: 90vh;">
    <div class="bg-white d-flex flex-column justify-content-center align-items-center w-50">
      <!-- Imagen centrada -->
      <div class="center-image" style="margin-bottom: 5%">
        <img src="assets/img/latinMobile.png" alt="logo" class="w-50" />
      </div>
      <form>
        <div class="d-flex flex-column justify-content-center">
          <br />
          <h2 class="text-center">SELECCIÓN DE EMPRESAS</h2>
          <br />
          <!-- Dropdown -->
          <div class="form-group d-flex justify-content-center">
            <select v-model="selectedOption" class="form-select">
              <option value="">Seleccione empresa</option>
              <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                {{ empresa.id }} - {{ empresa.nombre }}
              </option>
            </select>
          </div>
          <br />
          <div class="d-flex col-12 justify-content-center">
            <!-- Botón "Seleccionar" -->
            <button
              @click="seleccionar"
              class="btn btn-primary mt-2"
              type="button"
            >
              Seleccionar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

</template>

<script>
import axios from 'axios'; // Importa Axios

export default {
  data() {
    return {
      selectedOption: "", // Opción predeterminada seleccionada
      empresas: [], // Almacenar las empresas cargadas desde el endpoint
    };
  },
  created() {
    // Cargar las empresas desde el endpoint al cargar el componente
    this.fetchEmpresas();
  },
  methods: {
    fetchEmpresas() {
      // Llamar al endpoint de empresas utilizando Axios (cambia this.$axios a axios)
      axios.get("/empresas").then((response) => {
          // Formatear las empresas como 'id-nombre' y almacenarlas en 'empresas'
          this.empresas = response.data.map((empresa) => ({
            id: empresa.id,
            nombre: empresa.nombre,
          }));
        }).catch((error) => {
          console.error("Error al cargar empresas:", error);
        });
        if (JSON.parse(localStorage.getItem("user")) !== null) {
                const { empleado_id, imagen } = JSON.parse(
                    localStorage.getItem("user")
                );
               
                axios
                    .get(`empleadobyid?idEmpleado=${empleado_id}`)
                    .then((result) => {
                        this.id = result.data[0].id;
                       localStorage.setItem('userInfo' , JSON.stringify(result.data[0]))
                    })
                    .catch((error) => {});
            } else if (localStorage.getItem("user") === null)
                return this.cerrarSesion();
    },
    seleccionar() {
      // Manejar la acción de selección aquí
      if (this.selectedOption == '') {
        this.$toast.error('Seleccione empresa', {
          timeout: 3000,
          position: 'bottom-center',
          icon: true,
        });
      } else {
        localStorage.setItem('empresaID', JSON.stringify(this.selectedOption));
        this.$router.push('/dashboard');
      }
    },
  },
}
</script>

<style scoped>
.center-image {
  text-align: center;
}

/* Estilos adicionales según tus preferencias */
</style>
