import axios from 'axios';
import ApexCharts from 'apexcharts';
import { createLoader, removeLoader } from "../../partials/loader";

export default () => ({
    data: {},
    chart: undefined,
    chartWrapper: "admisiones-general",
    /**
     * Aqui se guarda la informacion de la seccion al dar click
    */
    async init() {
        /**
         * Creamos la grafia `vacia`, sin datos (seires)
        */
        this.createChart();
        this.chart.render();
        this.updateChart();
    },
    /**
     * Realiza la consulta a la API e iguala la variable `data` de la clase al
     * resultado.
    */
    async getData() {
        const endPoint = "http://192.168.1.1/asotrauma/servicios/estadisticas/traerEst.php";
        return axios
            .get(`${endPoint}`)
            .catch(error => console.error("Axios Handler: ", error));
    },
    /**
     * Handler del evento de actualizacion de fechas
    */
    async updateChart() {
        /**
         * Consultamos la base de datos
        */
        createLoader(`#${this.chartWrapper}-container`);
        const res = await this.getData();
        removeLoader(`#${this.chartWrapper}-container`);

        console.log(res);
        this.data = res.data
        this.updateChartSeries();
    },
    /**
     * Actualiza las series y las categorias del grafico
    */
    updateChartSeries() {
        const goal = {
            name: "Total Admisiones",
            value: this.data[0].total,
            strokeHeight: 5,
            strokeColor: '#FCBB1C'
        };

        this.chart.updateOptions({
            series: [{
                name: this.data[0].fecha,
                data: [{
                    x: "Hospitalarios",
                    y: this.data[0].hospita,
                    goals: [goal]
                },{
                    x: "Ambulatorios",
                    y: this.data[0].ambula,
                    goals: [goal]
                },{
                    x: "Urgencias",
                    y: this.data[0].urgencia,
                    goals: [goal]
                }]
            }],
            legend: {
                show: true,
                showForSingleSeries: true,
                customLegendItems: ['Tipo Atención', `Total Admisiones ${this.data[0].total}`],
                markers: {
                    fillColors: ['#269ffb', '#FCBB1C']
                }
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
                height: 350
            },
            noData: {
                text: "No info..."
            },
            yaxis: {
                title: {
                    text: "N° Admisiones"
                }
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: [] 
        }

        this.chart = new ApexCharts(
            document.getElementById(this.chartWrapper),
            options
        );
    }
})