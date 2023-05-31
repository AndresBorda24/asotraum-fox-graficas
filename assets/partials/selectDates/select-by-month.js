export default () => ({
    /** Esto es para mostrar los meses en spanish */
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
    currentYear: new Date().getFullYear(),
    /**
     * Despacha el evento de actualizacion de los selects de las fechas de
     * inicio y final
    */
    sendEvent(start, end) {
        this.$dispatch("update-selects-dates", {
            start,
            end
        });
    },
    /**
     * Despacha el evento basado en un mes
    */
    byMonth(month) {
        const start = new Date(this.currentYear, month, 1);
        const end   = new Date(this.currentYear, month + 1, 0);

        this.sendEvent(start, end);
    },
    /**
     * Despacha el evento basado en un trimestre
    */
    byTrimestre( t ) {
        const y = this.currentYear;
        const dates = [
            {start: y + '-01-01', end: y + '-03-31'},
            {start: y + '-04-01', end: y + '-06-30'},
            {start: y + '-07-01', end: y + '-09-30'},
            {start: y + '-10-01', end: y + '-12-31'}
        ];

        this.sendEvent(
            dates[t].start,
            dates[t].end
        );
    },
    /**
     * Despacha el evento basado en un semestre
    */
    bySemestre( s ) {
        const y = this.currentYear;
        const dates = [
            {start: y + '-01-01', end: y + '-06-30'},
            {start: y + '-07-01', end: y + '-12-31'}
        ];

        this.sendEvent(
            dates[s].start,
            dates[s].end
        );
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
