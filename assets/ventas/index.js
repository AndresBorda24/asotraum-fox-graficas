import Alpine from "alpinejs";
// Partials Generales
import addYears from "../partials/add-years";
import selectDates from "../partials/select-dates";
import selectByMonth from "../partials/selectDates/select-by-month";
// Componentes Especificos
import rfTotales from "./partials/rf-totales";
import topFacturadores from "./components/top-facturadores";
import resumenxEntidad from "./components/resumen-x-entidad";
import resumenFacturado from "./components/resumen-facturado";
import facturacionGeneral from "./components/facturacion-general";

import "../css/index.css";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("addYears", addYears);
    Alpine.data("selectDates", selectDates);
    Alpine.data("selectByMonth", selectByMonth);
    Alpine.data("resumenFacturado", resumenFacturado);
    Alpine.data("resumenxEntidad", resumenxEntidad);
    Alpine.data("resumenFacturadoTotales", rfTotales);
    Alpine.data("facturacionGeneral", facturacionGeneral);
    Alpine.data("topFacturadores", topFacturadores);
});


document.addEventListener("alpine:initialized", () => {
    // Desde aqui se realiza la primera carga de las graficas.
    const x = new Date;
    x.setDate(0)
    document.querySelector(`.month-${x.getMonth()}`)?.click();
})

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});
