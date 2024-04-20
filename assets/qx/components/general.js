import axios from 'axios';
import ApexCharts from 'apexcharts';
import { createLoader, removeLoader } from "../../partials/loader";

export default () => ({
    data: null,
    chart: undefined,
    chartWrapper: "qx-summary",
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
        const endPoint = `${import.meta.env.VITE_API}/qx/summary`;
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
        const tipo = [];
        const estado = [];

        const data = this.data.data;
        ['Cumplidas', 'Canceladas', 'Pendientes'].forEach(t => {
            const serie = { name: t, group: "tipo", data: [] };
            Object.keys(data).forEach(key => serie.data.push(
                data[key][t]
            ));
            tipo.push(serie);
        });

        ['Ambulatorias', 'Hospitalarias'].forEach(e => {
            const serie = { name: e, group: "estado", data: [] };
            Object.keys(data).forEach(key => serie.data.push(
                data[key][e]
            ));
            estado.push(serie);
        });

        this.chart.updateOptions({
            series: [...tipo, ...estado],
            xaxis: {
                type: 'category',
                categories: Object.keys(data).map(
                    q => [q, `Total: ${data[q]['total']}`]
                )
            }
        });
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            chart: {
                type: 'bar',
                stacked: true,
                height: 350
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            noData: {
                text: "No info..."
            },
            series: [],
            xaxis: {
                type: 'category',
                categories: []
            },
        }

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    },

    /** Sumatoria del total de todos los quirofanos */
    get total() {
        const totales = {
            neto: 0,
            cumplidas: 0
        };

        if (this.data === null) return totales;

        return Object.keys(this.data.data).reduce((total, qx) => {
            total.neto      += this.data.data[qx].total;
            total.cumplidas += this.data.data[qx].Cumplidas
            return total;
        }, totales);
    },

    get fechas() {
        return this.data?.dates;
    }
})