<div
  x-data="admisionesSummary"
  id="admisiones-summary-container"
  style="grid-column: 1 / -1;"
  class="home-graph p-3 text-muted border-dark-subtle position-relative rounded overflow-auto bg-light"
>
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-2">
    <div class="flex-grow-1">
      <h4 class="m-0 fs-6 badge text-bg-warning">Ingreso pacientes</h4>
      <template x-if="data.length == 0">
        <p class="my-2">
          Cargando información, por favor espera &#8230;
        </p>
      </template>
      <template x-if="data.data">
        <div>
          <p class="mt-2 mb-1">Mostrando cantidad de admisiones por hora de los siguientes días: </p>
          <ul class="mb-2">
            <template x-for="day in Object.keys(data.data)">
              <li>
                <span
                  x-text="day"
                  class="fw-bold"
                ></span>
                <span
                  class="small"
                  x-text="`(${Object.values(data.data[day]).reduce((a, b) => a + b, 0)})`"
                ></span>
              </li>
            </template>
          </ul>
        </div>
      </template>
    </div>
  </div>

  <div>
    <!-- Contenedor de la grafica -->
    <div class="mx-auto bg-body border shadow-sm" id="admisiones-summary"></div>
  </div>
</div>