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
    Alpine.data("iniciarGraficas", () => ({
        init() {
            // Aqui inicializamos las graficas con las fechas del ultimo mes
            const de = new Date();
            const ds = new Date();
            ds.setDate(1);
            this.$dispatch("new-dates-range", {
                start: ds.toJSON().substring(0, 10),
                end: de.toJSON().substring(0, 10)
            });
        }
    }))
});

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});

