export default () => ({
    dateStart: undefined,
    dateEnd: undefined,
    events: {
        ["@update-selects-dates"]: "updateDates($event.detail)"
    },
    init() {
        const [x, y] = this.getDates();

        this.dateEnd   = this.getStringDate(y);
        this.dateStart = this.getStringDate(x);
    },
    /**
     * Retorna la fecha en tipo string aaaa-mm-dd
     * @param Date date
    */
    getStringDate(date) {
        return date.toJSON().substring(0, 10);
    },
    /**
     * Obtiene las fechas del ultimo mes.
     * Si es abril obtiene:
     * - start: 202x-03-01
     * - end: 202x-03-31
    */
    getDates() {
        const ctrl  = new Date();
        const end   = new Date(ctrl.setDate(0));
        const start = new Date(ctrl.setDate(1))

        return [start, end];
    },
    /**
     * Obtiene la fecha maxima para la fecha de inicio `startDate`
     * por defecto es una semana
    */
    getMaxStartDate() {
        const CANTIDAD_SEMANAS = 3;
        const de = new Date(this.dateEnd);
        const days = 1000 * 60 * 60 * 24 * 7 * CANTIDAD_SEMANAS;

        return this.getStringDate(new Date(de.getTime() - days));
    },
    /**
     * Cuando se da click en el boton al lado de los selects de las
     * fechas se envia un evento para que las graficas se actualizen.
    */
    sendEvent() {
        this.$dispatch('new-dates-range', {
            start: this.dateStart,
            end: this.dateEnd
        });
    },
    /**
     * Actualiza las fechas de los selects a partir del evento
     * `update-selects-dates`
    */
    updateDates({ start, end }) {
        this.dateEnd   = this.getStringDate( new Date(end) );
        this.dateStart = this.getStringDate( new Date(start) );
        this.sendEvent();
    },
    getText() {
        const end   = new Date(this.dateEnd);
        const start = new Date(this.dateStart);
        const options = {
            weekday: 'long',
            // year: 'numeric',
            timeZone: 'UTC',
            month: 'long',
            day: 'numeric'
        };
        return "Desde el " +
            start.toLocaleString('es-Co', options)  +
            " hasta el " +
            end.toLocaleString('es-Co', options);
    }
});
