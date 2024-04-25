export default () => ({
    to: "",
    from: "",

    init() {
        const to = new Date();
        const from = new Date();
        from.setDate(1);

        this.to = to.toJSON().substring(0, 10);
        this.from = from.toJSON().substring(0, 10);
        setTimeout(() => this.updateDates(), 1000);
    },

    updateDates() {
        this.$dispatch('new-dates-range', {
            to: this.to,
            from: this.from
        });
    }
});