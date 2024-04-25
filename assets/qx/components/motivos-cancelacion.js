import axios from 'axios';
import ApexCharts from 'apexcharts';
import { createLoader, removeLoader } from "../../partials/loader";

export default () => ({
    data: null,
    chart: undefined,
    colors: [
        '#9e0142', '#d53e4f', '#f46d43', '#fdae61', '#fee08b', '#e6f598',
        '#abdda4', '#66c2a5', '#3288bd', '#5e4fa2'
    ],
    chartWrapper: "motivos-cancelacion",
    events: {
        ['@new-dates-range.document']: "updateChart($event.detail)"
    },

    /**
     * Aqui se guarda la informacion de la seccion al dar click
    */
    async init() {
        this.createChart();
        this.chart.render();
    },

    /**
     * Realiza la consulta a la API e iguala la variable `data` de la clase al
     * resultado.
    */
    async getData(from, to) {
        const endPoint = `${import.meta.env.VITE_API}/qx/motivos-cancelacion`;
        return axios
            .get(endPoint, {
                params: { from, to }
            })
            .catch(error => console.error("Axios Handler: ", error));
    },

    /**
     * Handler del evento de actualizacion de fechas
    */
    async updateChart({ from, to }) {
        createLoader(`#${this.chartWrapper}-container`);
        const res = await this.getData(from, to);
        removeLoader(`#${this.chartWrapper}-container`);

        this.data = res.data;
        this.updateChartSeries();
    },

    /** Actualiza las series y las categorias del grafico */
    updateChartSeries() {
        this.chart.updateOptions({
            series: Object.values(this.data?.data.total ?? {}),
            labels: Object.keys(this.data?.data.total ?? {}),
        });
    },

    /** Crea la grafica pero `NO` la renderiza.  */
    createChart() {
        const options = {
            chart: {
                type: 'pie',
                width: '100%'
            },
            noData: {
                text: "No info..."
            },
            colors: this.colors,
            legend: {
                show: false
            },
            series: [],
            responsive: [{
                breakpoint: 766,
                options: {
                    chart: {
                        height: 400
                    }
                }
            }]
        }

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    },

    get fechas() {
        return this.data?.dates;
    }
})
