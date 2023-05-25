<div
x-data="facturacionGeneral"
x-bind="events"
class="p-3 p-md-4 p-lg-5 py-lg-3 shadow bg-body border-bottom border-top text-muted mb-5">
  <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom p-2 mb-2 gap-2">
    <h4 class="m-0">Facturaci&oacute;n General</h4>
    <?= $this->fetch('./partials/select-dates.php') ?>
  </div>

  <div class="d-flex gap-2 align-items-center">
    <template x-if="typeof data.data !== 'undefined'">
      <!-- Muestra El total general de facturacion -->
      <div
      style="min-width: 280px;"
      class="small bg-body border rounded-1 shadow text-center p-2">
        <h6 class="text-center fw-semibold">Informaci&oacute;n General:</h6>
        <span>Total Facturas: </span>
        <span
        class="fw-bold"
        x-text="data.meta.total.records"></span>
        <hr class="m-1">
        <span>Total: </span>
        <span
        class="fw-bold"
        x-text="formatter.format(data.meta.total.cash)"></span>
      </div>
    </template>

    <!-- Contenedor de la grafica -->
    <div class="mx-auto" id="facturacion-general"></div>

    <!-- Detalles -->
    <?= $this->fetch("./factu/partials/fg-detalles.php") ?>
  </div>
</div>
