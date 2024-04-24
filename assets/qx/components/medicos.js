import axios from 'axios';
import ApexCharts from 'apexcharts';
import { createLoader, removeLoader } from "../../partials/loader";

export default () => ({
    data: null,
    chart: undefined,
    chartWrapper: "medicos",
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
        const endPoint = `${import.meta.env.VITE_API}/qx/medicos`;
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
            series: ['A', 'H'].map(tipo => ({
                name: tipo === "A" ? "Ambulatorias" : "Hospitalarias",
                data: Object.values(this.data?.data ?? {}).map(d => d[tipo])
            })),
            xaxis: {
                categories: Object.keys(this.data?.data ?? {}),
            }
        });
    },

    /** Crea la grafica pero `NO` la renderiza.  */
    createChart() {
        const options = {
            chart: {
                type: 'bar',
                width: '95%',
                height: 500,
                stacked: true,
            },
            noData: {
                text: "No info..."
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    dataLabels: {
                        total: {
                            enabled: true,
                            offsetX: 0,
                            style: {
                                fontSize: '13px',
                                fontWeight: 900
                            }
                        }
                    }
                },
            },
            series: [],
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
