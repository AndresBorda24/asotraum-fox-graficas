import ApexCharts from 'apexcharts';

/**
 * Este componente es un componente hijo para
 * resumen-facturado.js
 *
 * ----- ** IMPORTANTE ** -----
 * `data` viene de resumen-facturado.js
*/
export default () => ({
    // Grafica de Barras mostrando totales
    barChart: undefined,
    barChartWrapper: "resumen-facturado-total",
    /**
     * Crea la grafica de barras (totales) pero `NO` la renderiza.
    */
    async init() {
        /**
         * Cuando se actualiza data
        */
        this.$watch('data', () => this.updateChartSeries())

        /**
         * Creamos la grafia `vacia`, sin datos (seires)
        */
        this.createBarChart();
        this.barChart.render();
    },
    /**
     * Crea la grafica pero `NO` la renderiza.
    */
    createBarChart() {
        const options = {
            series: [],
            chart: {
                type: 'bar',
                height: 180
            },
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
                        + ' ' + 'M'
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
                    text: "Millones"
                }
            },
        };

        this.barChart = new ApexCharts(
            document.getElementById(this.barChartWrapper),
            options
        );
    },
    /**
     * Actualiza las series y las categorias del grafico
    */
    updateChartSeries() {
        const barChart = this.data.reduce((acc, d) => {
            acc.series.push(d.meta.total.cash);
            acc.labels.push(
                "20" + d.meta.dates.end.substring(6)
            );

            return acc;
        }, { series: [], labels: [] });

        this.barChart.updateOptions({
            xaxis: {
                categories: barChart.labels
            },
            series: [{
                data: barChart.series
            }]
        });
    }
});
