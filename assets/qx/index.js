import "../css/index.css";

import Alpine from "alpinejs";
import general from "./components/general";
import datesHandler from "./components/dates-handler";

window.Alpine = import.meta.env.PROD ? undefined : Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("General", general);
    Alpine.data("DatesHandler", datesHandler);
});

document.addEventListener("alpine:initialized", function () {
});

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});
