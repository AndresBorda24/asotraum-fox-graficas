<div
x-data="facturacionGeneral"
class="p-3 p-md-4 p-lg-5 py-lg-3 shadow bg-body text-muted mb-5">
  <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom p-2 mb-2 gap-2">
    <h4 class="m-0">Facturaci&oacute;n General</h4>
    <?= $this->fetch('./partials/select-dates.php') ?>
  </div>

  <div class="d-flex gap-2">
    <!-- Contenedor de la grafica -->
    <div class="flex-grow-1" id="facturacion-general"></div>
  </div>
</div>
