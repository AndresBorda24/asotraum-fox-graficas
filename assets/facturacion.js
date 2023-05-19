import Alpine from "alpinejs";
import grafica1 from "./fact/grafica1";
import grafica2 from "./fact/grafica2";
import selectDates from "./partials/select-dates";
import selectByMonth from "./partials/selectDates/select-by-month";
import "./css/index.css";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("grafica1", grafica1);
    Alpine.data("grafica2", grafica2);
    Alpine.data("selectDates", selectDates);
    Alpine.data("selectByMonth", selectByMonth);
});

Alpine.start();
