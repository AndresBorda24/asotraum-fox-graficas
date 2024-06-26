<div
x-data="facturacionGeneral"
x-bind="events"
id="facturacion-general-container"
class="p-3 p-md-4 p-lg-5 py-lg-3 text-muted mb-5 border shadow position-relative rounded overflow-auto">
  <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom p-2 mb-2 gap-2">
    <div class="flex-grow-1">
      <h4 class="m-0">Facturaci&oacute;n General</h4>
      <?= $this->fetch('./partials/add-years.php') ?>
    </div>
    <?= $this->fetch('./partials/select-dates.php') ?>
  </div>

  <div>
    <!-- Contenedor de la grafica -->
    <div class="mx-auto bg-body border shadow-sm" id="facturacion-general"></div>

    <!-- Muestra los totales de los anios seleccionados -->
    <?= $this->fetch('./ventas/partials/fg-detalles.php') ?>
  </div>
</div>
