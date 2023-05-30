import axios from 'axios';
import ApexCharts from 'apexcharts';
import { showLoader, hideLoader } from "../../partials/loader";

export default () => ({
    data: {},
    years: [],
    // Grafica Principal
    chart: undefined,
    chartWrapper: "resumen-facturado",
    // Esto nos permite imprimir el total en pesos $ xxx.xxx.xxx
    formatter: new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
        minimumFractionDigits: 0
    }),
    events: {
        ['@new-dates-range']: "updateChart($event.detail)"
    },
    async init() {
        /**
         * Creamos la grafia `vacia`, sin datos (seires)
        */
        this.createChart();
        this.chart.render();
    },
    /**
     * Realiza la consulta a la API e iguala la variable `data` de la clase al
     * resultado.
    */
    async getData(start, end) {
        try {
            const API = process.env.API + "/ventas/facturado";
            return axios
                .get(`${API}?start=${start}&end=${end}`)
                .catch(error => console.error("Axios Handler: ", error));
        } catch (e) {
            alert("Ha ocurrido un error.");
            console.error(e);
        }
    },
    /**
     * Handler del evento de actualizacion de fechas
    */
    async updateChart({ start, end }) {
        showLoader();
        const res = await Promise.all(
            this.years.map(y => {
                const s = start.replace(/\w+/, y);
                const e = end.replace(/\w+/, y);

                return this.getData(s, e);
            })
        ).finally(hideLoader);

        this.data = res.map(r => r.data);

        this.updateChartSeries();
    },
    /**
     * Actualiza las series y las categorias del grafico
    */
    updateChartSeries() {
        const mainChart = this.data.map(d => {
            const data = Object.keys(d.data).map(k => ({
                x: k.split(' '),
                y: d.data[k].total
            }));

            return {
                data,
                name: "20" + d.meta.dates.end.substring(6)
            };
        });

        this.chart.updateSeries(mainChart);
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            chart: {
                type: "treemap",
                height: '540px',
                stacked: true
            },
            noData: {
                text: "No info..."
            },
            series: [],
            legend: {
                show: true,
                showForSingleSeries: true,
                position: "top"
            },
            dataLabels: {
                enabled: true,
                offsetY: -15,
                formatter: (val, opts) => {
                    const total = this.formatter.format(Math.round(
                            parseInt(opts.value) / 1000000
                        ))
                        + ' ' + 'M';
                    const puesto = (((opts.dataPointIndex) % 11) + 1) + "). ";

                    return [puesto, ...val, total];
                },
                style: {
                    fontSize: "9px",
                    colors: ["#414141"]
                }
            },
            tooltip: {
                y: {
                    formatter: (val) => this.formatter
                        .format(Math.round(parseInt(val) / 1000000))
                        + ' ' + 'M'
                }
            },
        }

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    },
    /**
     * Obtiene el total faturado general
    */
    getTotal() {
        return this.formatter.format(
            this.data.total_facturado.reduce((a, f) => a + f, 0)
        );
    }
});
