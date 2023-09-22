<template>
    <div>
     <main class="container">       
         <h1 class="text-center my-4">Bienvenido  {{ nombre }}</h1>
        <Bar
        :chart-options="chartOptions"
        :chart-data="charData"
        :chart-id="chartId"
        :dataset-id-key="datasetIdKey"
        :plugins="plugins"
        :css-classes="cssClasses"
        :styles="styles"
        :width="width"
        :height="height"
        ></Bar>
     </main>
    </div>

</template>
<script>
import { Bar } from 'vue-chartjs/legacy'
import { Chart, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

Chart.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
    components: {
        Bar
    },
    props: {
        chartId: {
            type: String,
            default:'bar-chart'
        },
        datasetIdKey: {
            type: String,
            default: 'label'
        },
        width: {
            type: Number,
            default: 600
        },
        height: {
            type: Number,
            default: 400
        },
        cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => { }
        },
        plugins: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            usuario: {},
            nombre: '',
            charData: {
                labels: ['Enero', 'Febrero', 'Marzo'],
                datasets: [
                    {
                        label: 'Ventas',
                        backgroundColor: ['#f87979', '#2898ee', '#0cbccc'],
                        data: [40, 20, 12]
                    }
                ]
            },
            chartOptions: {
                responsive: true
            }
        }
    },
    mounted() {
        this.leerData();
        if(localStorage.getItem('user') !== null){
            this.usuario = JSON.parse(localStorage.getItem('user'));
        }
    },
    methods: {
        cerrarSesion(){
            localStorage.removeItem('user');
            this.$router.push('/');
        },
        leerData(){
            const {empleado_id} = JSON.parse(localStorage.getItem("user"));
            axios.get(`empleadobyid?idEmpleado=${empleado_id}`).then((result) => {
                this.nombre =result.data[0].nombres
            }).catch(error => {})
        }
    }
}
</script>