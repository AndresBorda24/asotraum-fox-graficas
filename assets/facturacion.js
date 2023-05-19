import Alpine from "alpinejs";
import grafica1 from "./fact/grafica1";
import "./css/index.css";

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("grafica1", grafica1);
});

Alpine.start();
