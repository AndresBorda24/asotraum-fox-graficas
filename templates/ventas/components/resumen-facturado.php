<div
x-data="resumenFacturado"
x-bind="events"
class="p-3 p-md-4 p-lg-5 py-lg-3 text-muted my-5 border shadow">
  <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom p-2 mb-2 gap-2">
    <div class="flex-grow-1">
      <h4 class="m-0">Gr&aacute;fica Resumen Facturaci&oacute;n </h4>
      <?= $this->fetch('./partials/add-years.php') ?>
    </div>
    <?= $this->fetch('./partials/select-dates.php') ?>
  </div>

  <div class="d-flex flex-column shadow-sm">
    <!-- Esta Grafica solo muestra los resultados totales  -->
    <div x-data="resumenFacturadoTotales()">
      <div class="flex-grow-1 border border-bottom-0 bg-body" id="resumen-facturado-total"></div>
    </div>

    <!-- Contenedor de la grafica -->
    <div class="flex-grow-1 border bg-body" id="resumen-facturado"></div>
  </div>
</div>
