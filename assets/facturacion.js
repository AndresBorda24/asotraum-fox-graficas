import Alpine from "alpinejs";
import selectDates from "./partials/select-dates";
import selectByMonth from "./partials/selectDates/select-by-month";
import resumenFacturado from "./fact/resumen-facturado";
import "./css/index.css";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("selectDates", selectDates);
    Alpine.data("selectByMonth", selectByMonth);
    Alpine.data("resumenFacturado", resumenFacturado);
});

Alpine.start();
