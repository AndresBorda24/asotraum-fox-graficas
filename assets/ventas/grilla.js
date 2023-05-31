import Alpine  from "alpinejs";
import grilla from "./components/grilla";

import selectDates from "../partials/select-dates";
import selectByMonth from "../partials/selectDates/select-by-month";

import "../css/index.css"
import "datatables.net-dt/css/jquery.dataTables.min.css"

window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
    Alpine.data("grilla", grilla);
    Alpine.data("selectDates", selectDates);
    Alpine.data("selectByMonth", selectByMonth);
});

document.addEventListener("DOMContentLoaded", () => {
    Alpine.start();
});
