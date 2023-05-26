import axios from 'axios';
import ApexCharts from 'apexcharts';
import { showLoader, hideLoader } from "../partials/loader";

export default () => ({
    data: {},
    years: [],
    chart: undefined,
    chartWrapper: "resumen-facturado",
    formatter: new Intl.NumberFormat('es-CO', {
        style: 'currency', currency: 'COP'
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
            showLoader();
            const API = "https://graficas-fact.local/api";
            const { data } = await axios.get(
                `${API}/ventas/facturado?start=${start}&end=${end}` // Refactorizar
            ).finally( () => hideLoader());
            this.data = data;
        } catch (e) {
            alert("Ha ocurrido un error.");
            console.error(e);
        }
    },
    /**
     * Handler del evento de actualizacion de fechas
    */
    async updateChart({ start, end }) {
        /**
         * Consultamos la base de datos
        */
        await this.getData(start, end);
        this.updateChartSeries();
    },
    /**
     * Actualiza las series y las categorias del grafico
    */
    updateChartSeries(){
        this.chart.updateOptions({
            labels: this.data.categories
        });

        this.chart.updateSeries([{
            type: "column",
            name: "Facturado por Entidad",
            data: this.data.total_facturado
        }, {
            type: "line",
            name: "Cantidad Total de Facturas",
            data: this.data.total_facturas
        }]);
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            chart: {
                type: "line",
                height: '520px'
            },
            noData: {
                text: "No info..."
            },
            series: [],
            legend: { position: "top" },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            xaxis: {
                type: 'categories',
                labels: {
                    hideOverlappingLabels: true,
                    rotateAlways: true,
                    minHeight: 120,
                    trim: true,
                    rotate: -45,
                    style: { fontSize: '10px' }
                },
            },
            yaxis: [{
                title: { text: 'Facturado' },
                labels: {
                    formatter: (val) => this.formatter.format(parseInt(val))
                }
            }, {
                opposite: true,
                title: { text: 'Cantidad de Facturas' }
            }]
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
