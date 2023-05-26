/**
 * Para agregar este componente el padre DEBE tener una propiedad llamada years,
 * y esta debe ser igualada a un array vacio []
*/
export default (parentYears) => ({
    years: parentYears,
    selectYear: '',
    /**
     * En esta variable se guardan los anios que se pueden
     * seleccionar. Por defecto simepre se selecciona el mas
     * reciente
    */
    ctrlYears: [],
    init() {
        let ctrl = (new Date()).getFullYear();

        // Solamente 4 anios
        for (let i = 0; i <= 3; i++) {
            this.ctrlYears.push( ctrl-- + '' );
        }

        this.years.push(
            this.ctrlYears[0]
        );
    },
    /**
     * Muestra los anios disponibles (aquellos que aun no han sido
     * seleccionados)
    */
    availableYears() {
        return this.ctrlYears.filter(_ => ! this.years.includes(_));
    },
    /**
     * Agrega un anio al array principal (years). Esto, por supuesto,
     * afecta a availableYears.
    */
    appendYear() {
        if (this.selectYear === '') return;

        this.years.push( this.selectYear );
        this.selectYear = '';
    },
    /**
     * Elimina un anio al array principal (years). Esto tambien
     * afecta a availableYears.
    */
    removeYear(index) {
        if (this.years.length === 1) return;
        this.years.splice(index, 1);
    }
});
