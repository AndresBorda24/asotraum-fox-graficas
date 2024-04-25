import axios from 'axios';
import ApexCharts from 'apexcharts';
import { createLoader, removeLoader } from "../../partials/loader";

export default () => ({
    data: null,
    chart: undefined,
    chartWrapper: "ocupacion",

    /**
     * Aqui se guarda la informacion de la seccion al dar click
    */
    async init() {
        this.createChart();
        this.chart.render();
        this.updateChart();
    },

    /**
     * Realiza la consulta a la API e iguala la variable `data` de la clase al
     * resultado.
    */
    async getData() {
        const endPoint = `${import.meta.env.VITE_API}/qx/ocupacion`;
        return axios
            .get(endPoint)
            .catch(error => console.error("Axios Handler: ", error));
    },

    /**
     * Handler del evento de actualizacion de fechas
    */
    async updateChart() {
        createLoader(`#${this.chartWrapper}-container`);
        const res = await this.getData();
        removeLoader(`#${this.chartWrapper}-container`);

        this.data = res.data;
        this.updateChartSeries();
    },

    /** Actualiza las series y las categorias del grafico */
    updateChartSeries() {
        this.chart.updateOptions({
            series: Object.values(this.data),
            labels: Object.keys(this.data)
        });
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            series: [],
            chart: {
                height: 390,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    offsetY: 0,
                    startAngle: 0,
                    endAngle: 270,
                    hollow: {
                        margin: 5,
                        size: '30%',
                        background: 'transparent',
                        image: undefined,
                    },
                    dataLabels: {
                        name: {
                            show: false,
                        },
                        value: {
                            show: false,
                        }
                    },
                    barLabels: {
                        enabled: true,
                        useSeriesColors: true,
                        margin: 8,
                        fontSize: '16px',
                        formatter: function (seriesName, opts) {
                            return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex] + "%"
                        },
                    },
                }
            },
            labels: []
        }

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    }
})