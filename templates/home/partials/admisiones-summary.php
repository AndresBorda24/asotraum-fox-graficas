<div
        x-data="admisionesSummary"
        id="admisiones-summary-container"
        style="grid-column: 1 / -1;"
        class="home-graph p-3 text-muted border-dark-subtle position-relative rounded overflow-auto bg-light"
>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-2">
        <div class="flex-grow-1">
            <h4 class="m-0 fs-6 badge text-bg-warning">Ingreso pacientes</h4>

            <!-- Checkboxes de tipos -->
            <div>
                <label>Tipo de ingreso del paciente:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="day-1" value="1" x-model="selectedDays" @change="updateChart()" checked>
                    <label class="form-check-label" for="day-1">Ambulatorio</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="day-2" value="2" x-model="selectedDays" @change="updateChart()" checked>
                    <label class="form-check-label" for="day-2">Hospitalización</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="day-3" value="3" x-model="selectedDays" @change="updateChart()" checked>
                    <label class="form-check-label" for="day-3">Urgencias</label>
                </div>
            </div>


            <template x-if="data.length == 0">
                <p class="my-2">
                    Cargando información, por favor espera &#8230;
                </p>
            </template>
            <template x-if="data.data">
                <div>
                    <p class="mt-2 mb-1">Mostrando cantidad de admisiones por tipo de admision: </p>
                    <ul class="mb-2">

                    </ul>
                    <div class="form-check d-block d-md-none">
                        <input class="form-check-input" type="checkbox" x-model="zoom" id="habilitar-zoom">
                        <label class="form-check-label" for="habilitar-zoom"> Habilitar Zoom </label>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div>
        <!-- Contenedor de la grafica -->
        <div class="mx-auto bg-body border shadow-sm" id="admisiones-summary"></div>
    </div>
</div>
