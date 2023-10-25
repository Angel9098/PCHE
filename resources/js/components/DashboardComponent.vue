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
                <Doughnut :chart-options="chartOptionsDonut" :chart-data="chartDataDonut" :chart-id="chartId" :width="width" :style="anchoGraficaCircular" class="mb-2">
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
        width: {
            type: Number,
            default: 600
        },
        height: {
            type: Number,
            default: 315
        }
    },
    data() {
        return {
            screenWidth: window.innerWidth,
            anchoGraficaCircular: { height: '0' },
            usuario: {},
            empresas: [],
            empresaID: 0,
            serieDonut: [],
            categorysDonut: [],
            comparativoBars: [],
            comparativoCategories: [],
            dataBarRecuento: [],
            nombre: '',
            //config para grafica de anillo
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
            //config para grafica de barras de 3 meses
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
            series: [],
            //config para grafica comparativo
            optionsLines: {
                series: [],
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
    created() {
        window.addEventListener("resize", this.handleResize);
    },
    destroyed() {
        window.removeEventListener("resize", this.handleResize);
    },
    mounted() {
        //var chartCircle = new ApexCharts(document.querySelector('#radialBar1'), this.optionsRadial);
        //chartCircle.render();
        this.handleResize();
        this.leerData();
        this.getEmpresas();
        if(localStorage.getItem('user') !== null){
            this.usuario = JSON.parse(localStorage.getItem('user'));
        }
        this.getHorasExtraComparativo();
        this.getRecuentoHorasExtra();
    },
    methods: {
        handleResize() {
            this.screenWidth = window.innerWidth;

            if (this.screenWidth >= 960 && this.screenWidth < 1000) this.anchoGraficaCircular.height = "235px";
            if (this.screenWidth >= 1000 &&  this.screenWidth < 1040) this.anchoGraficaCircular.height = "245px";
            if (this.screenWidth >= 1040 && this.screenWidth < 1100) this.anchoGraficaCircular.height = "255px";
            if (this.screenWidth >= 1100 && this.screenWidth < 1140) this.anchoGraficaCircular.height = "270px";
            if (this.screenWidth >= 1140 && this.screenWidth < 1180) this.anchoGraficaCircular.height = "280px";
            if (this.screenWidth >= 1180 && this.screenWidth < 1220) this.anchoGraficaCircular.height = "290px";
            if (this.screenWidth >= 1220 && this.screenWidth < 1260) this.anchoGraficaCircular.height = "300px";
            if (this.screenWidth >= 1260 && this.screenWidth < 1300) this.anchoGraficaCircular.height = "315px";
            if (this.screenWidth >= 1300 && this.screenWidth < 1340) this.anchoGraficaCircular.height = "325px";
            if (this.screenWidth >= 1340 && this.screenWidth < 1390) this.anchoGraficaCircular.height = "340px";
            if (this.screenWidth >= 1390 && this.screenWidth < 1430) this.anchoGraficaCircular.height = "350px";
            if (this.screenWidth >= 1430 && this.screenWidth < 1470) this.anchoGraficaCircular.height = "360px";
            if (this.screenWidth >= 1470 && this.screenWidth < 1510) this.anchoGraficaCircular.height = "375px";
            if (this.screenWidth >= 1510 && this.screenWidth < 1540) this.anchoGraficaCircular.height = "385px";
            if (this.screenWidth >= 1540 && this.screenWidth < 1570) this.anchoGraficaCircular.height = "395px";
            if (this.screenWidth >= 1570 && this.screenWidth < 1600) this.anchoGraficaCircular.height = "400px";
            if (this.screenWidth >= 1600 && this.screenWidth < 1640) this.anchoGraficaCircular.height = "415px";
            if (this.screenWidth >= 1640 && this.screenWidth < 1670) this.anchoGraficaCircular.height = "425px";
            if (this.screenWidth >= 1670 && this.screenWidth < 1700) this.anchoGraficaCircular.height = "430px";
            if (this.screenWidth >= 1700 && this.screenWidth < 1740) this.anchoGraficaCircular.height = "440px";
            if (this.screenWidth >= 1740 && this.screenWidth < 1780) this.anchoGraficaCircular.height = "450px";
            if (this.screenWidth >= 1780 && this.screenWidth < 1810) this.anchoGraficaCircular.height = "460px";
            if (this.screenWidth >= 1810 && this.screenWidth < 1840) this.anchoGraficaCircular.height = "470px";
            if (this.screenWidth >= 1840 && this.screenWidth < 1880) this.anchoGraficaCircular.height = "480px";
            if (this.screenWidth >= 1880 && this.screenWidth < 1900) this.anchoGraficaCircular.height = "490px";
            if (this.screenWidth >= 1900 && this.screenWidth < 1920) this.anchoGraficaCircular.height = "495px";
            if (this.screenWidth >= 1920 && this.screenWidth < 1950) this.anchoGraficaCircular.height = "500px";
        },
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
                this.empresas = response.data;
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