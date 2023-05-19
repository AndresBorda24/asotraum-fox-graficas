export default () => ({
    dateStart: undefined,
    dateEnd: undefined,
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
    }
});
