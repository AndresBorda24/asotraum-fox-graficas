export default () => ({
    events: {
        ["@update-selects-dates.document"]: "updateSelected"
    },

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
     * Determina que fecha esta seccionada. Index 0 es el tipo y el index 1
     * hace referencia al mes / semestre 7 trimestre
    */
    selected: [ "", 0 ],

    init() {
        const x = new Date;
        x.setDate(0);

        this.byMonth(x.getMonth());
    },

    /**
     * Al momento de seleccionar una fecha an cualquiera de las graficas, 
     * actualiza la propiedad `selected` en todos los componentes.
     */
    updateSelected({ detail }) {
        this.selected = detail.selected
    },

    /**
     * Despacha el evento de actualizacion de los selects de las fechas de
     * inicio y final
    */
    sendEvent(start, end) {
        const selected = JSON.parse(JSON.stringify(this.selected));

        this.$dispatch("update-selects-dates", {
            start,
            end, 
            selected    
        });
    },
    /**
     * Despacha el evento basado en un mes
    */
    byMonth(month) {
        this.selected = ["M", month];
        const start = new Date(this.currentYear, month, 1);
        const end   = new Date(this.currentYear, month + 1, 0);

        this.sendEvent(start, end);
    },
    /**
     * Despacha el evento basado en un trimestre
    */
    byTrimestre( t ) {
        this.selected = ["T", t];
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
        this.selected = ["S", s];
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
    },

    /**
     * Determina si un boton ha sido seleccionado o no.
    */
    isSelected( tipo, n ) {
        return (
            this.selected[0] === tipo
            & this.selected[1] === n - 1
        );
    }
});
