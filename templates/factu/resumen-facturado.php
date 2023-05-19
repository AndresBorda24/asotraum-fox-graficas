<div
x-data="resumenFacturado"
x-bind="events"
class="p-3 p-md-4 p-lg-5 py-lg-3 shadow bg-body text-muted mb-4">
  <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom p-2 mb-2 gap-2">
    <h4 class="m-0">Gr&aacute;fica Resumen Facturaci&oacute;n </h4>
    <?= $this->fetch('./partials/select-dates.php') ?>
  </div>

  <div class="d-flex gap-2">
    <!-- Contenedor de la grafica -->
    <div class="flex-grow-1" id="resumen-facturado"></div>

    <!-- Tabla resumen -->
    <div class="small">
      <ul class="list-group shadow">
        <template x-for="(c, i) in data.categories">
          <li class="list-group-item list-group-item-light d-flex gap-2">
            <span
            x-text="i + 1 + '- '"></span>
            <span
            x-text="c + ' '"
            class="flex-grow-1 text-muted"></span>
            <span
            x-text="formatter.format(data.total_facturado[i])"
            class="fw-semibold text-end"
            style="width: 128px;"></span>
          </li>
        </template>
        <template x-if="data.total_facturado">
          <li class="list-group-item list-group-item-info d-flex">
            <span class="flex-grow-1">Total:</span>
            <span x-text="getTotal"></span>
          </li>
        </template>
      </ul>
    </div>
  </div>
</div>
