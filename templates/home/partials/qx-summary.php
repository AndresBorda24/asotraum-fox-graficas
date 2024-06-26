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
          Mostrando Cantidad, Tipo y Estado de cirugías programadas para el dia
          <span class="badge text-bg-primary d-inline-block mx-1" x-text="fechas?.from"></span> hasta
          <span class="badge text-bg-primary d-inline-block ms-1" x-text="fechas?.to"></span>
        </p>
      </template>
    </div>
  </div>

  <div>
    <!-- Contenedor de la grafica -->
    <div class="mx-auto bg-body border shadow-sm" id="qx-summary"></div>
  </div>

  <div class="position-absolute top-0 end-0 m-1 mt-2 d-flex gap-2">
    <a
      target="_blank"
      href="/estadisticas-qx"
      class="home-graph-link btn btn-sm btn-outline-primary py-1 rounded-5 lh-1 border-0 text-decoration-none d-flex align-items-center gap-1"
    >
      <span class="fs-6 d-none d-sm-block">Ir a Grilla</span>
      <span class="fs-4"> <?= $this->fetch("./icons/link.php") ?> </span>
    </a>

    <a
      target="_blank"
      href="<?= $this->link("qx") ?>"
      class="home-graph-link btn btn-sm btn-outline-primary py-1 rounded-5 lh-1 border-0 text-decoration-none d-flex align-items-center gap-1"
    >
      <span class="fs-6 d-none d-sm-block">Gráficas</span>
      <span class="fs-4"> <?= $this->fetch("./icons/link.php") ?> </span>
    </a>
  </div>


  <template x-teleport="#general-summary">
    <div class="py-2 d-flex flex-column gap-2 px-3 rounded bg-light shadow">
      <header class="position-relative">
        <span class="fw-semibold fs-5">Cirugías</span>
      </header>
      <a class="d-flex flex-fill text-decoration-none text-dark" href="#qx-summary">
        <span
          style="font-size: 3.7rem"
          class="text-dark border border-primary p-2 rounded-bottom-pill bg-primary-subtle"
        > <?= $this->fetch("./icons/doctor.php") ?> </span>
        <p class="p-2 text-end text-muted m-0 align-self-center">
          Cantidad total de cirugías programadas es de
          <span x-text="total.neto" class="fw-bold"></span>, de las cuales
          <span x-text="total.cumplidas" class="fw-bold"></span> ya han sido cumplidas
        </p>
      </a>
      <footer class="d-flex gap-2 pt-2 border-top align-items-center">
        <label for="from" class="small flex-fill">
          Desde
          <input
            class="form-control form-control-sm"
            type="date"
            name="from"
            id="from"
            x-model="from"
          >
        </label>
        <label for="to" class="small flex-fill">
          Hasta
          <input
            class="form-control form-control-sm"
            type="date"
            name="to"
            id="to"
            x-model="to"
          >
        </label>
        <button
          class="btn btn-sm border"
          @click="updateChart"
        ><?= $this->fetch("./icons/search.php") ?></button>
      </footer>
    </div>
  </template>
</div>