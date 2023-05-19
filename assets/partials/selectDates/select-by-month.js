export default () => ({
    months: [
        'Ene',
        'Feb',
        'Mar',
        'Abr',
        'May',
        'Jun',
        'Jul',
        'Ago',
        'Sep',
        'Oct',
        'Nov',
        'Dic'
    ],
    currentMonth: new Date().getMonth(),
    /**
     * Despacha el evento de actualizacion de los selects de las fechas de
     * inicio y final
    */
    sendEvent( month ) {
        const [start, end] = this.getDates(month);

        this.$dispatch("update-selects-dates", {
            start,
            end
        });
    },
    /**
     * Obtiene las fechas con respecto a un mes
    */
    getDates( month ) {
        const ctrl  = new Date();
        ctrl.setMonth(month);

        const end   = new Date(ctrl.setDate(0));
        const start = new Date(ctrl.setDate(1))

        return [start, end];
    }
});
