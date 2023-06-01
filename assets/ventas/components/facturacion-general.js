import axios from 'axios';
import ApexCharts from 'apexcharts';
import formatter from "../../partials/money-formatter";
import { showLoader, hideLoader } from "../../partials/loader";

export default () => ({
    data: [],
    years: [],
    chart: undefined,
    chartWrapper: "facturacion-general",
    formatter: formatter,
    events: {
        ['@new-dates-range']: "updateChart($event.detail)"
    },
    /**
     * Aqui se guarda la informacion de la seccion al dar click
    */
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
        const endPoint = process.env.API + "/ventas/resumen-general";
        return axios
            .get(`${endPoint}?start=${start}&end=${end}`)
            .catch(error => console.error("Axios Handler: ", error));
    },
    /**
     * Handler del evento de actualizacion de fechas
    */
    async updateChart({ start, end }) {
        /**
         * Consultamos la base de datos
        */
        showLoader();
        const res = await Promise.all(
            this.years.map(y => {
                const s = start.replace(/\w+/, y);
                const e = end.replace(/\w+/, y);

                return this.getData(s, e);
            })
        );
        hideLoader();

        this.data = res.reduce((acc, data) => {
            if (typeof data === 'undefined') return acc;

            acc.push(data.data);
            return acc;
        }, []);

        this.updateChartSeries();
    },
    /**
     * Actualiza las series y las categorias del grafico
    */
    updateChartSeries() {
        this.chart.updateOptions({
            series: this.data.map(s => ({
                name: '20' + s.meta.dates.end.substring(6),
                data: Object.keys(s.data).map(d => ({
                    x: d,
                    y: s.data[d].total
                }))
            })),
        });
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            chart: {
                type: 'bar',
                height: 350
            },
            noData: {
                text: "No info..."
            },
            yaxis: {
                labels: {
                    formatter: (val) => {
                        return this.formatter.format(Math.round(val / 1000000))
                            + ' ' + 'M';
                    }
                },
                title: {
                    text: "Millones"
                }
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            dataLabels: {
                formatter: (val) => {
                     return this.formatter.format(Math.round(val / 1000000))
                        + ' ' + 'M';
                }
            },
            series: [],
            legend: { position: "bottom" }
        }

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    }
});
