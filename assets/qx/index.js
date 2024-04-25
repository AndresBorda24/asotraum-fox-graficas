import "../css/index.css";

import Alpine from "alpinejs";
import general from "./components/general";
import medicos from "./components/medicos";
import ocupacion from "./components/ocupacion";
import datesHandler from "./components/dates-handler";
import motivosCancelacion from "./components/motivos-cancelacion";

window.Alpine = import.meta.env.PROD ? undefined : Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("General", general);
    Alpine.data("Medicos", medicos);
    Alpine.data("Ocupacion", ocupacion);
    Alpine.data("DatesHandler", datesHandler);
    Alpine.data("MotivosCancelacion", motivosCancelacion);
});

document.addEventListener("alpine:initialized", function () {
});

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});
