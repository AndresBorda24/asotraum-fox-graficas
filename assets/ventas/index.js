import Alpine from "alpinejs";
// Partials Generales
import addYears from "../partials/add-years";
import selectDates from "../partials/select-dates";
import selectByMonth from "../partials/selectDates/select-by-month";
// Componentes Especificos
import rfTotales from "./partials/rf-totales";
import topFacturadores from "./components/top-facturadores";
import resumenFacturado from "./components/resumen-facturado";
import facturacionGeneral from "./components/facturacion-general";

import "../css/index.css";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("addYears", addYears);
    Alpine.data("selectDates", selectDates);
    Alpine.data("selectByMonth", selectByMonth);
    Alpine.data("resumenFacturado", resumenFacturado);
    Alpine.data("resumenFacturadoTotales", rfTotales);
    Alpine.data("facturacionGeneral", facturacionGeneral);
    Alpine.data("topFacturadores", topFacturadores);
});

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});
