import axios from 'axios';
import ApexCharts from 'apexcharts';
import { createLoader, removeLoader } from "../../partials/loader";

export default () => ({
    selectedDays: ['1', '2', '3'], // Asegúrate de que esté definido aquí
    data: {},
    chart: undefined,
    chartWrapper: "admisiones-summary",
    zoom : false,

    async init() {
        this.createChart();
        this.chart.render();
        this.updateChart();

        this.$watch("zoom", (val) => {
            this.chart.updateOptions({
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: {
                            zoom: { enabled: val }
                        }
                    }
                }]
            });
        });
    },

    async updateChart() {
        createLoader(`#${this.chartWrapper}-container`);
        const tiposIngreso = this.selectedDays.join(","); // Convierte el array a una cadena
        // Llama a tu API con los tipos seleccionados
        const res = await axios.get(`${import.meta.env.VITE_API}/admisiones/clasepro-horas`, {
            params: { selectedDays: tiposIngreso } // Envía los tipos de ingreso como parámetros
        });
        removeLoader(`#${this.chartWrapper}-container`);
        this.data = res.data; // Asigna los datos obtenidos a this.data
        this.updateChartSeries(); // Actualiza la gráfica
    },

    updateChartSeries() {
        const responseData = this.data;  // Asigna los datos recibidos del servidor
        const series = {};

        // Seleccionar una fecha de manera dinámica
        const allDates = Object.keys(responseData.data); // Obtiene todas las fechas
        const selectedDate = allDates[Math.floor(Math.random() * allDates.length)]; // Selecciona una fecha al azar

        const categories = Object.keys(this.data.data[selectedDate]);

        // Agrupar datos por fecha y hora
        Object.keys(this.data).forEach(item => {
            const { fecha, hora, clasepro, total } = item;

            // Asegurarse de que la serie esté configurada para cada fecha
            if (!series[fecha]) {
                series[fecha] = { name: fecha, data: Array(24).fill(0) };
            }
            // Asigna el total a la hora correspondiente
            series[fecha].data[parseInt(hora)] = total;

            // Asegúrate de que las categorías estén en orden
            if (!categories.includes(hora)) {
                categories.push(hora);
            }
        });

        // Convierte el objeto series a un array
        const seriesArray = Object.keys(this.data.data).map((f) => {
            return {
                name: f,
                data: Object.values(this.data.data[f])
            }
        })


        this.chart.updateOptions({
            series: seriesArray,
            xaxis: { categories: [...new Set(categories)] } // Asegúrate de que las categorías estén en orden
        });
    },

    createChart() {
        const options = {
            chart: { type: 'area', height: 350 },
            dataLabels: {
                enabled: false
            },
            noData: {
                text: "No info..."
            },
            series: [],
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'category',
                categories: []
            },
        };

        this.chart = new ApexCharts(document.getElementById(this.chartWrapper), options);
    }
});
