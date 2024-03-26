<div
  x-data="qxSummary"
  id="qx-summary-container"
  class="home-graph p-3 text-muted border-dark-subtle position-relative rounded overflow-auto bg-light"
>
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-2">
    <div class="flex-grow-1">
      <h4 class="m-0 fs-6 badge text-bg-warning">Resumen Quirofano</h4>
      <template x-if="data == null">
        <p class="my-2">
          Cargando información, por favor espera &#8230;
        </p>
      </template>
      <template x-if="data != null">
        <p class="my-2">
          Mostrando Cantidad, Tipo y Estado de cirugías programadas para el dia:
          <span
            class="badge text-bg-primary"
            x-text="(new Date).toJSON().substring(0, 10)"
          ></span>
        </p>
      </template>
    </div>
  </div>

  <div>
    <!-- Contenedor de la grafica -->
    <div class="mx-auto bg-body border shadow-sm" id="qx-summary"></div>
  </div>
</div>