import axios from "axios";
import { showLoader, hideLoader } from "../../partials/loader"
// Datatables
import jQuery from "jquery";
import DataTable from 'datatables.net-dt';

export default () => ({
    // Aqui se almaciena la info en cada solicitus.
    data: {},
    // Url de donde se descarga el excel
    excelUrl: process.env.API + "/ventas/excel",
    endPoint: process.env.API + "/ventas/grilla",
    datatable: new DataTable("#datatable", {
        scrollY: '45vh',
        pageLength: 50,
        aaSorting: [3, "asc"],
        columnDefs: [
            { target: -1, width: '200px' }
        ]
    }),
    events: {
        ['@new-dates-range']: "getData($event.detail.start, $event.detail.end)"
    },
    /**
     * Generamos la grilla y realizamos el primer llamado a la API
    */
    async init() {
        jQuery( this.datatable.table().container() )
            .addClass("mt-4");
        // await this.getData();
    },
    /** Realiza la peticion al API */
    async getData(start = null, end = null) {
        try {
            showLoader();
            let ep = this.endPoint;

            if (Boolean(start) && Boolean(end)) {
                ep += `?start=${start}&end=${end}`;
            }

            const { data } = await axios
                .get(ep).finally(hideLoader);

            this.data = data;
            this.updateGrilla();
        } catch (e) {
            console.error("Request Error: ", e);
        }
    },
    /**
     * Actualiza la grilla xD
    */
    updateGrilla() {
        this.datatable.clear();
        this.datatable.rows.add(this.data.data).draw();
    },
});
