<template>
    <div class="container">
        <h1 class="text-center my-4">Bienvenido  {{ nombre }}</h1>
        <div class="w-100 d-flex flex-row justify-content-center align-items-center gap-4">       
            <div class="col-6">
                <apexchart width="100%" type="bar" :options="optionsBar" :series="series"></apexchart>
            </div>
            <div id="radialBar1" class="col-6">

            </div>
<!--             <Doughnut
                :chart-options="chartOptionsDonut"
                :chart-data="chartDataDonut"
                :chart-id="chartIdDonut"
                :dataset-id-key="datasetIdKey"
                :plugins="plugins"
                :css-classes="cssClasses"
                :styles="styles"
                :width="width"
                :height="height"
            >
            </Doughnut> -->
        </div>
    </div>

</template>
<script>
import { Bar } from 'vue-chartjs/legacy';
import { Doughnut } from 'vue-chartjs/legacy';
import { Chart, Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale } from 'chart.js'

Chart.register(Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale)

export default {
    components: {
        Bar,
        Doughnut
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
        },
        chartIdDonut: {
            type: String,
            default: 'doughnut-chart'
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
                        label: 'Horas extra canceladas',
                        backgroundColor: ['#f87979'],
                        data: [40, 20, 12]
                    }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            chartDataDonut: {
                labels: ['Optimissa', 'Lat Mobile', 'Monetae'],
                datasets: [
                    {
                        backgroundColor: ['#41B883', '#E46651', '#00D8FF'],
                        data: [45, 20, 63]
                    }
                ]
            },
            chartOptionsDonut: {
                responsive: true,
                maintainAspectRatio: false
            },
            optionsBar: {
                chart: {
                    id: 'apexchart-bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: 'top'
                    }
                  }  
                },
                xaxis: {
                    categories:['Enero', 'Febrero', 'Marzo']
                }
            },
            series: [{
                name: 'Horas extras USD',
                data: [40, 20, 12]
            }],
            optionsRadial: {
                series: [45, 20, 63],
                legend: {
                    show: true
                },
                title: {
                    align: 'left'  
                },
                chart: {
                    height: 350,
                    type: 'radialBar'
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: {
                                fontSize: '22px'
                            },
                            value: {
                                fontSize: '16px'
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    return 249
                                }
                            }
                        }
                    }
                },
                labels: ['Optimissa', 'Lat Mobile', 'Monetae']
            }
        }
    },
    mounted() {
        var chartCircle = new ApexCharts(document.querySelector('#radialBar1'), this.optionsRadial);
        chartCircle.render();
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
                this.nombre =result.data[0].nombres + ' '+result.data[0].apellidos
            }).catch(error => {})
        }
    }
}
</script>