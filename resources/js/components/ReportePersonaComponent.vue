<template>
  <div>
    <!-- Contenido para el PDF -->
    <div ref="pdfContent" class="pdf-wrapper">
      <!-- Encabezado -->
      <div class="header">
        <div class="logo-container">
          <img src="/assets/img/latinMobile.png" alt="logo" class="logo">
        </div>
        <div class="title-container">
          <h2 class="title">PCHE</h2>
          <h4>Reporte de Horas de Empleado</h4>
          <p class="company-name">{{ companyName }}</p>
        </div>
        <div class="area-date-container">
          <p class="area">{{ areaFromAPI }}aarea</p>
          <p class="date">{{ currentDate }}</p>
        </div>
      </div>

      <!-- Tabla con las 7 columnas -->
      <table class="table-margin">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Horas Diurnas</th>
            <th>Horas Nocturnas</th>
            <th>Diurnas en Día de Descanso</th>
            <th>Nocturnas en Día de Descanso</th>
            <th>Horas Diurnas en Asueto</th>
            <th>Horas Nocturnas en Asueto</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id">
            <td>{{ item.date }}</td>
            <td>{{ item.diurnas }}</td>
            <td>{{ item.nocturnas }}</td>
            <td>{{ item.diurnasDescanso }}</td>
            <td>{{ item.nocturnasDescanso }}</td>
            <td>{{ item.diurnasAsueto }}</td>
            <td>{{ item.nocturnasAsueto }}</td>
          </tr>
          <tr>
            <td>Total</td>
            <td>{{ totalDiurnas }}</td>
            <td>{{ totalNocturnas }}</td>
            <td>{{ totalDiurnasDescanso }}</td>
            <td>{{ totalNocturnasDescanso }}</td>
            <td>{{ totalDiurnasAsueto }}</td>
            <td>{{ totalNocturnasAsueto }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Espaciador para separar la tabla del pie de página -->
      <div class="spacer"></div>

      <!-- Paginación como pie de página -->
      <div class="pagination">
        pg {{ currentPage }} de {{ totalPages }}
      </div>
    </div>
    <button @click="generatePDF">Generar PDF</button>
  </div>
</template>

<script>
import html2pdf from "html2pdf.js";

export default {
  data() {
    return {
      companyName: '',
      areaFromAPI: '',
      currentDate: new Date().toLocaleDateString(),
      items: [
        {
          id: 1,
          date: "2023-09-15",
          diurnas: 5,
          nocturnas: 3,
          diurnasDescanso: 2,
          nocturnasDescanso: 1,
          diurnasAsueto: 0,
          nocturnasAsueto: 0
        },
      ],
      currentPage: 1,
      totalPages: 1,
      totalDiurnas: 0, // Estos totales se calcularán según tus necesidades
      totalNocturnas: 0,
      totalDiurnasDescanso: 0,
      totalNocturnasDescanso: 0,
      totalDiurnasAsueto: 0,
      totalNocturnasAsueto: 0,
    };
  },
  mounted() {
    // Traer el nombre de la empresa y el área desde el API
    this.$axios.get('/api/company-name').then(response => {
      this.companyName = response.data.name;
    });
    this.$axios.get('/api/area').then(response => {
      this.areaFromAPI = response.data.area;
    });
  },
  methods: {
    generatePDF() {
      const content = this.$refs.pdfContent;
      const pdfOptions = {
        margin: 10,
        filename: "documento.pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait" },
      };
      html2pdf().from(content).set(pdfOptions).save();
    }
  }
}
</script>

<style scoped>
.pdf-wrapper {
  position: relative;
  min-height: 100%;
}
.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px;
}
.logo-container {
  flex: 0 0 auto;
}
.logo {
  width: 150px;
  height: 100px;
}
.title-container {
  flex: 1;
  text-align: center;
}
.title, h4 {
  margin: 0;
}
h4 {
  margin-top: 20px;
}
.company-name {
  font-size: 1.2em;
  margin: 0;
  text-align: center;
}
.area-date-container {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}
.area, .date {
  margin: 0;
}
.table-margin {
  margin: 20px 0;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
table, th, td {
  border: 1px solid black;
}
th, td {
  padding: 8px;
  text-align: left;
}
.spacer {
  height: 50px;
}
.pagination {
  position: absolute;
  bottom: 0;
  right: 0;
  text-align: right;
  margin-top: 20px;
  font-size: 0.9em;
}
</style>
