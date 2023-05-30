import axios from 'axios';
import ApexCharts from 'apexcharts';
import { showLoader, hideLoader } from "../../partials/loader";

export default () => ({
    data: {},
    years: [new Date().getFullYear()],
    // Grafica Principal
    chart: undefined,
    chartWrapper: "top-facturadores",
    // Esto nos permite imprimir el total en pesos $ xxx.xxx.xxx
    formatter: new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
        minimumFractionDigits: 0
    }),
    /**
     * Estos son los colores que tomaran las columnas
     * Se hizo de esta forma para evitar duplicados.
    */
    colors: [
        "#f44336",
        "#e81e63",
        "#9c27b0",
        "#673ab7",
        "#3f51b5",
        "#2196f3",
        "#03a9f4",
        "#00bcd4",
        "#009688",
        "#4caf50",
        "#8bc34a",
        "#cddc39",
        "#ffeb3b",
        "#ffc107",
        "#ff9800",
        "#ff5722",
        "#795548",
        "#9e9e9e",
        "#607d8b",
        "#000000"
    ],
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
            const API = process.env.API + "/ventas/top-facturadores";
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

        /**
         * Una vez que tenemos la data, actualizamos la grafica
        */
        this.updateChartSeries();
    },
    /**
     * Actualiza las series y las categorias del grafico
    */
    updateChartSeries() {
        const _ = this.data.map(d => {
            const data = d.data.reduce((acc, _) => {
                acc.labels.push(_.quien);
                acc.data.push(_.cuanto);

                return acc;
            }, { labels: [], data: [] });

            return data;
        });


        this.chart.updateOptions({
            xaxis: {
                categories: _[0].labels
            },
            series: [{
                name: "Total Facturado",
                data: _[0].data
            }]
        });;
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            series: [],
            chart: {
                type: 'bar',
                height: 600
            },
            colors: this.colors,
            legend: { show: false },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    distributed: true
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
                style: {
                    fontSize: "12px",
                    colors: ["#414141"]
                }
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
                title: {
                    text: "Millones Facturados"
                }
            },
        };

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    }
});
