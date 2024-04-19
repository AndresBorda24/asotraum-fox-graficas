import "../css/home.css";

import Alpine from "alpinejs";
import addYears from "../partials/add-years";
import qxSummary from "./partials/qx-summary";
import admisiones from "./partials/admisiones";
import admisionesSummary from "./partials/admisiones-summary";
import facturacionGeneral from "../ventas/components/facturacion-general";

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data("addYears", addYears);
    Alpine.data("qxSummary", qxSummary);
    Alpine.data("admisionesGenearl", admisiones);
    Alpine.data("admisionesSummary", admisionesSummary);
    Alpine.data("facturacionGeneral", facturacionGeneral);
    Alpine.data("cambiarFechas", () => ({
        to: "",
        from: "",
        init() {
            // Aqui inicializamos las graficas con las fechas del ultimo mes
            const to = new Date();
            const from = new Date();
            from.setDate(1);

            this.to = to.toJSON().substring(0, 10);
            this.from = from.toJSON().substring(0, 10);
            this.updateDates();
        },

        updateDates() {
            this.updateChart({
                end: this.to,
                start: this.from
            });
        }
    }));
});

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});

