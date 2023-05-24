import axios from 'axios';
import ApexCharts from 'apexcharts';
import { showLoader, hideLoader } from "../partials/loader";

export default () => ({
    data: {},
    chart: undefined,
    chartWrapper: "facturacion-general",
    /**
     * Esto nos ayuda a dar formato de moneda a algunos valores.
    */
    formatter: new Intl.NumberFormat('es-CO', {
        style: 'currency', currency: 'COP'
    }),
    events: {
        ['@new-dates-range']: "updateChart($event.detail)"
    },
    /**
     * Aqui se guarda la informacion de la seccion al dar click
    */
    details: undefined,
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
                `${API}/ventas/resumen-general?start=${start}&end=${end}` // Refactorizar
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
            labels: Object.keys(this.data.data),
        });

        this.chart.updateSeries(
            Object.values(this.data.data).map(_ => _.meta.records),
        );

        if (typeof this.details !== 'undefined') {
            this.setupDetails(
                this.details.meta.index,
                this.details.meta.color
            );
        }
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createChart() {
        const options = {
            chart: {
                type: "pie",
                width: 600,
                events: {
                    legendClick: (context, seriesId, config) => {
                        const color = config.globals.colors[ seriesId ];

                        this.setupDetails(seriesId, color);
                    }
                }
            },
            noData: {
                text: "No info..."
            },
            series: [],
            legend: { position: "bottom" }
        }

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    },
    /**
     * Cuando se le da click a una de las leyendas de la grafia carga la
     * informacion referente a esta y la muesta en una lista.
    */
    setupDetails( index, color ) {
        this.details = Object.values(
            this.data.data
        )[ index ];

        this.details.meta.color = color;
        this.details.meta.index = index;
    }
});
