<div
x-data="grafica1"
class="p-3 p-md-4 p-lg-5 shadow bg-body text-muted mb-4">
  <h4 class="border-bottom">
    Gr&aacute;fica Resumen Facturaci&oacute;n <span class="fw-bold small">(Abril 2023)</span>
  </h4>
  <div class="d-flex gap-2">
    <div
    class="flex-grow-1"
    id="grafica-1"></div>
    <div>
      <ul class="list-group shadow">
        <template x-for="(c, i) in data.categories">
          <li class="list-group-item list-group-item-light d-flex gap-2">
            <span x-text="i + 1 + '- '"></span>
            <span x-text="c + ' '" class="flex-grow-1 text-muted"></span>
            <span x-text="formatter.format(
              data.total_facturado[i]
            )"
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
