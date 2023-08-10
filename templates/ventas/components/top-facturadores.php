<div
x-data="topFacturadores"
x-bind="events"
class="p-3 p-md-4 p-lg-5 py-lg-3 text-muted my-5 border shadow">
  <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom p-2 mb-2 gap-2">
    <div class="flex-grow-1">
      <h4 class="m-0">Top 15 Facturadores</h4>
    </div>
    <?= $this->fetch('./partials/select-dates.php') ?>
  </div>

  <div class="d-flex flex-column shadow-sm">
    <!-- Contenedor de la grafica -->
    <div class="flex-grow-1 border bg-body" id="top-facturadores"></div>
  </div>
</div>
