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

  <a
    target="_blank"
    href="/estadisticas-qx"
    class="home-graph-link btn btn-sm btn-outline-primary py-1 px-4 rounded-5 lh-1 border-0 position-absolute top-0 end-0 m-1 mt-2 text-decoration-none d-flex align-items-center gap-2"
  >
    <span class="fs-6 d-none d-sm-block">Ir a Grilla</span>
    <span class="fs-4"> <?= $this->fetch("./icons/link.php") ?> </span>
  </a>

  <template x-teleport="#general-summary">
    <div class="p-3 rounded bg-light shadow">
      <header class="mb-2 position-relative">
        <h3> Cirugías </h3>
        <template x-if="data !== null">
          <span
            class="badge text-bg-primary position-absolute top-0 end-0 m-1"
            x-text="(new Date).toJSON().substring(0, 10)"></span>
        </template>
      </header>
      <div class="d-flex ">
        <span
          style="font-size: 3.7rem"
          class="text-dark border border-primary p-2 rounded-bottom-pill bg-primary-subtle"
        > <?= $this->fetch("./icons/doctor.php") ?> </span>
        <p class="p-2 text-end text-muted m-0 align-self-center">
          Cantidad total de cirugías programadas es de
          <span x-text="total.neto" class="fw-bold"></span>, de las cuales
          <span x-text="total.cumplidas" class="fw-bold"></span> ya han sido cumplidas
        </p>
      </div>
    </div>
  </template>
</div>