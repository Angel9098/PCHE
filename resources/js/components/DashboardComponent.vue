<template>
    <div class="col-11">
        <h1 class="text-center my-4" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Bienvenido {{ nombre }}</h1>
        <div v-if="rol == 'administrador'" class="w-100 d-flex flex-row justify-content-center align-items-center gap-4">
            <div class="col-6 border border-1 rounded-3">
                <h3 class="text-center mt-2">RECUENTO HORAS EXTRA</h3>
                <apexchart width="100%" type="bar" :options="optionsBar" :series="series"></apexchart>
            </div>
            <div class="col-6 border border-1 rounded-3">
                <h3 class="text-center mt-2">HORAS EXTRA POR ÁREA</h3>
                <div class="d-flex flex-row justify-content-center col-12">
                    <select class="form-select ms-4 w-75" v-model="empresaID" @change="getHorasExtraByArea()">
                        <option value="0">Seleccione Empresa</option>
                        <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                            {{ empresa.nombre }}
                        </option>
                    </select>
                </div>
                <Doughnut :chart-options="chartOptionsDonut" :chart-data="chartDataDonut" :chart-id="chartId"
                    :dataset-id-key="datasetIdKey" :plugins="plugins" :css-classes="cssClasses" :styles="styles"
                    :width="width" :height="height" class="mb-2">
                </Doughnut>
            </div>
            <!--             <div class="col-6 border border-1 rounded-3">
                <h3 class="text-center mt-2" style="margin-bottom: 45px;">Horas extra recientes</h3>
                <div id="radialBar1"></div>
            </div> -->
        </div>
        <div v-if="rol == 'administrador'" class="mt-5 mb-5 w-10 d-flex flex-row justify-content-center align-items-center gap-4">
            <div class="col-12 border border-1 rounded-3">
                <h3 class="text-center mt-2">COMPARATIVO HORAS EXTRA</h3>
                <apexchart width="100%" type="bar" :options="optionsLines" :series="optionsLines.series" id="chartLines">
                </apexchart>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import moment from 'moment';
import { Doughnut } from 'vue-chartjs/legacy';
import { Chart, Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale } from 'chart.js'

Chart.register(Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale)

export default {
    components: {
        Doughnut
    },
    props: {
        chartId: {
            type: String,
            default:'doughnut-chart'
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
            default: 315
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
            empresas: [],
            empresaID: 0,
            serieDonut: [],
            categorysDonut: [],
            comparativoBars: [],
            dataBarRecuento: [],
            comparativoCategories: [],
            nombre: '',
            chartDataDonut: {
                labels: [],
                datasets: [
                    {
                        backgroundColor: ['#41B883', '#E46651', '#00D8FF'],
                        data: []
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
                    height: 360
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
                    categories:['Septiembre','Octubre','Noviembre']
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return '$' + value
                        }
                    }
                }
            },
            series: [/* {
                name: 'Horas extras USD',
                data: []
            } */],
            optionsRadial: {
                series: [45, 20, 63],
                legend: {
                    show: true
                },
                title: {
                    align: 'left'
                },
                chart: {
                    height: 500,
                    type: 'radialBar'
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: {
                                fontSize: '22px'
                            },
                            value: {
                                fontSize: '16px',
                                formatter: function (val) {
                                    return '$' + val
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    return '$' + 210
                                }
                            }
                        }
                    }
                },
                labels: ['Optimissa', 'Lat Mobile', 'Monetae']
            },
            optionsLines: {
                series: []/* [
                    {
                        name: 'Lat Mobile',
                        data: [22, 44, 10]
                    },
                    {
                        name: 'Monetae',
                        data: [9, 15, 60]
                    },
                    {
                        name: 'Optimissa',
                        data: [65, 56, 63]
                    },
                ] */,
                chart: {
                    type: 'bar',
                    height: 400
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Septiembre','Octubre','Noviembre']
                },
                yaxis: {
                    title: {
                        text:'$ USD'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "$" + val
                        }
                    }
                }
            }
        }
    },
    computed: {
        rol() {
            if (localStorage.getItem('userAdmin') != null) {
                return JSON.parse(JSON.stringify(localStorage.getItem('userAdmin')));
            }
            else return null;
        }
    },
    mounted() {
        var chartCircle = new ApexCharts(document.querySelector('#radialBar1'), this.optionsRadial);
        chartCircle.render();

        //var chartBar = new ApexCharts(document.querySelector("#chartLines"), this.optionsLines);
        //chartBar.render();

        this.leerData();
        this.getEmpresas();
        if(localStorage.getItem('user') !== null){
            this.usuario = JSON.parse(localStorage.getItem('user'));
        }
        this.getHorasExtraComparativo();
        this.getRecuentoHorasExtra();
    },
    methods: {
        cerrarSesion(){
            localStorage.removeItem('user');
            this.$router.push('/');
        },
        leerData() {
            if (localStorage.getItem('nombreUser') != null) {
                this.nombre = JSON.parse(localStorage.getItem('nombreUser'));
            }
        },
        getEmpresas() {
            axios.get("/empresas").then((response) => {
                // Formatear las empresas como 'id-nombre' y almacenarlas en 'empresas'
                this.empresas = response.data;
/*                 this.$swal({
                    title: 'Empresas Obtenidas',
                    icon: 'success',
                    showConfirmButton: false,
                    position: 'center',
                    timer: 2000
                }); */
            }).catch((error) => {
                console.error("Error al cargar empresas:", error);
            });
        },
        getRecuentoHorasExtra() {
            let dataAux = [];
            let xaxisAux = [];
            axios.get('calculo_horas/graficaDeTresMeses', { headers: { 'Content-type': 'application/json' } })
                .then(response => {
                    response.data.forEach(element => {
                        dataAux.push(element.horas_pagadas.toFixed(2));
                        xaxisAux.push(moment().month(element.Mes - 1).locale('es-mx').format('MMMM'));
                    });
                    this.dataBarRecuento.push({ name: 'Horas extras', data: dataAux });
                    this.series = this.dataBarRecuento;
                    this.$set(this.optionsBar, 'xaxis', { categories: xaxisAux });
                    //console.log(this.optionsBar.xaxis)
                });
        },
        getHorasExtraByArea() {
            this.chartDataDonut.labels = [];
            this.chartDataDonut.datasets[0].data = [];
            this.categorysDonut = [];
            this.serieDonut = [];
            axios.get(`dashboard/horasExtraEmpresa?idEmpresa=${this.empresaID}`, { headers: { 'Content-type': 'application/json' } }).then(resp => {
                resp.data.object.forEach(element => {
                    this.categorysDonut.push(element.nombre_area);
                    this.serieDonut.push(element.total_horas);
                });
                this.chartDataDonut.labels = this.categorysDonut;
                this.chartDataDonut.datasets[0].data = this.serieDonut;
            })
        },
        getHorasExtraComparativo() {
            axios.get('dashboard/horasExtraTotal', { headers: { 'Content-type': 'application/json' } }).then(resp => {
                resp.data.object.forEach(element => {
                    if (this.comparativoBars.length == 0) {
                        this.comparativoBars.push({ name: element.nombre_empresa, data: [element.total_horas.toFixed(2)] });
                        this.comparativoCategories.push(moment().month(element.periodo - 1).locale('es-mx').format('MMMM'));
                    } else {
                        if (this.comparativoBars.findIndex(item => item.name == element.nombre_empresa) != -1) {
                            let index = this.comparativoBars.findIndex(item => item.name == element.nombre_empresa);
                            this.comparativoBars[index].data.push(element.total_horas.toFixed(2));
                        } else {
                            this.comparativoBars.push({ name: element.nombre_empresa, data: [element.total_horas.toFixed(2)] });
                        }

                        if (this.comparativoCategories.findIndex(item => item == element.periodo) == -1) {
                            this.comparativoCategories.push(moment().month(element.periodo - 1).locale('es-mx').format('MMMM'));
                        }
                    }
                });
                this.optionsLines.series = this.comparativoBars;
                this.$set(this.optionsLines, 'series', this.comparativoBars);
                //this.$set(this.optionsLines.xaxis, 'categories', this.comparativoCategories);
                //this.optionsLines = { ...this.optionsLines.xaxis, categories: this.comparativoCategories };
                //console.log(this.optionsLines.series)
            })
        }
    }
}
</script>