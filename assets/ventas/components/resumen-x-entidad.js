import axios from 'axios';
import ApexCharts from 'apexcharts';
import formatter from "../../partials/money-formatter";
import { createLoader, removeLoader } from "../../partials/loader";

export default () => ({
    data: {},
    year: new Date().getFullYear(),
    chart: undefined,
    wrapper: "resumen-x-entidad",
    endPoint: process.env.API + "/ventas/resumen-x-entidad",
    events: {
        ['@new-dates-range']: "getData($event.detail)"
    },
    formatter: formatter,
    init() {
        this.createChart();
        this.chart.render();
    },
    /**
     * Realiza la peticion a la base de datos
    */
    async getData({ start, end }) {
        try {
            createLoader(`#${this.wrapper}-container`);
            const { data } = await axios
                .get(`${this.endPoint}?start=${start}&end=${end}`)
                .finally(() => removeLoader(`#${this.wrapper}-container`));
            this.data = data;
            this.updateChart();
        } catch (e) {
            console.error("Fetch resumen-x-entidad error: ", e);
        }
    },
    /**
     * Actualiza la info de la grafica.
    */
    updateChart() {
        this.chart.updateOptions({
            series: Object.values(this.data.data),
            xaxis: {
                categories: this.data.meta.labels
            }
        });
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            series: [],
            chart: { type: 'bar', height: 600, stacked: true },
            noData: { text: "No info..." },
            colors: this.colors,
            legend: { show: true },
            stroke: { width: 1, colors: ['#fff'] },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    dataLabels: {
                        total: {
                            enabled: true,
                            offsetX: 10,
                            style: {
                                fontSize: '10px',
                                fontWeight: 900
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => {
                    const total = this.formatter.format(Math.round(
                        parseInt(val) / 1000000
                    ))
                        + ' ' + 'M';

                    return total;
                },
                style: { fontSize: "12px" }
            },
            tooltip: {
                y: {
                    formatter: (val) => this.formatter
                        .format(parseInt(val))
                }
            },
            xaxis: {
                labels: {
                    formatter: (val) => {
                        return this.formatter.format(Math.round(val / 1000000))
                            + ' ' + 'M';
                    }
                },
                title: { text: "Millones Facturados" }
            },
        };

        this.chart = new ApexCharts(
            document.getElementById(this.wrapper),
            options
        );
    }
});
