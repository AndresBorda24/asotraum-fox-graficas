import axios from 'axios';
import ApexCharts from 'apexcharts';
import { createLoader, removeLoader } from "../../partials/loader";

export default () => ({
    days: 3,
    data: {},
    zoom: false,
    chart: undefined,
    chartWrapper: "admisiones-summary",
    /**
     * Aqui se guarda la informacion de la seccion al dar click
    */
    async init() {
        /**
         * Creamos la grafia `vacia`, sin datos (seires)
        */
        this.createChart();
        this.chart.render();
        this.updateChart();

        this.$watch("zoom", (val) => {
            // Activa / Desactiva el zoom en mobile
            this.chart.updateOptions({
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: {
                            zoom: {
                                enabled: val
                            }
                        },
                    },
                }]
            });
        });
    },
    /**
     * Realiza la consulta a la API e iguala la variable `data` de la clase al
     * resultado.
    */
    async getData() {
        const endPoint = `${import.meta.env.VITE_API}/admisiones/summary?days=${this.days}`;
        return axios
            .get(`${endPoint}`)
            .catch(error => console.error("Axios Handler: ", error));
    },
    /**
     * Handler del evento de actualizacion de fechas
    */
    async updateChart() {
        /**
         * Consultamos la base de datos
        */
        createLoader(`#${this.chartWrapper}-container`);
        const res = await this.getData();
        removeLoader(`#${this.chartWrapper}-container`);

        this.data = res.data;
        this.updateChartSeries();
    },
    /**
     * Actualiza las series y las categorias del grafico
    */
    updateChartSeries() {
        const series = Object.keys(this.data.data).reverse().map(key => {
            return {
                "name": key,
                data: Object.values(this.data.data[key])
            }
        });

        const key = Object.keys(this.data.data)[0];
        const categories = Object.keys(this.data.data[key])

        this.chart.updateOptions({
            series,
            xaxis: {
                type: 'category',
                categories
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        zoom: {
                            enabled: false
                        }
                    },
                    xaxis: {
                        type: 'category',
                        labels: {
                            rotate: 90,
                            offsetY: 40
                        }
                    }
                },
            }]
        });
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            chart: {
                type: 'area',
                height: 350
            },
            dataLabels: {
                enabled: false
            },
            noData: {
                text: "No info..."
            },
            series: [],
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'category',
                categories: []
            },
        }

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    }
})