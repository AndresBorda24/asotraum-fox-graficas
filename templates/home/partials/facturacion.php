<div
  x-data="facturacionGeneral"
  x-bind="events"
  id="facturacion-general-container"
  class="home-graph p-3 text-muted border-dark-subtle position-relative rounded overflow-auto bg-light"
>
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-2">
    <div class="flex-grow-1">
      <h4 class="m-0 fs-6 badge text-bg-warning">Facturaci&oacute;n General - Ventas</h4>
      <template x-if="data.length">
        <p class="my-2">
          Mostrando información comprendida entre el siguiente rango de fechas: 
          <span 
            class="badge text-bg-primary" 
            x-text="new Date(data[0].meta?.dates?.start).toJSON().substring(0, 10)"
          ></span> &
          <span 
            class="badge text-bg-primary" 
            x-text="new Date(data[0].meta?.dates?.end).toJSON().substring(0, 10)"
          ></span>
        </p>
      </template>

      <template x-if="data.length == 0">
        <p class="my-2">
          Cargando información, por favor espera &#8230;
        </p>
      </template>
    </div>
  </div>

  <div x-data="addYears(years)">
    <!-- Contenedor de la grafica -->
    <div class="mx-auto bg-body border shadow-sm" id="facturacion-general"></div>
  </div>
  <a 
    href="<?= $this->link("ventas") ?>"
    class="home-graph-link btn btn-sm btn-outline-primary py-1 px-4 rounded-5 lh-1 border-0 position-absolute top-0 end-0 m-1 mt-2 text-decoration-none d-flex align-items-center gap-2" 
  >
    <span class="fs-6 d-none d-sm-block">Ir a Ventas</span>
    <span class="fs-4"> <?= $this->fetch("./icons/link.php") ?> </span>
  </a>
</div>