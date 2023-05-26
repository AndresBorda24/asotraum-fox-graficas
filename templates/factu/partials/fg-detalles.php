<div
class="d-grid gap-3 mt-2"
style="
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  justify-content: center;
">
  <template x-for="d in data">
    <div class="small border p-1 bg-body">
      <div class="p-1 mb-1 border-bottom">
        <span
        x-text="'20' + d.meta.dates.end.substring(6)"
        class="d-block text-center fw-bold fs-6 mb-1"></span>
        <span class="d-block">
          Facturado:
          <span
          class="fw-semibold"
          x-text="formatter.format(d.meta.total.cash)"></span>
        </span>
        <span class="d-block">
          # Facturas:
          <span
          x-text="d.meta.total.records"></span>
        </span>
      </div>

      <ul class="small list-group list-group-flush">
        <template x-for="tipo in Object.keys(d.data)">
          <li class="d-flex align-items-center">
            <span
            class="flex-grow-1"
            x-text="tipo"></span>
            <div style="width: 45%">
              <span
              class="fw-semibold d-block text-end"
              x-text="formatter.format(d.data[ tipo ].total)"></span>
              <span
              class="fw-light d-block text-end"
              x-text="d.data[ tipo ].records"></span>
            </div>
          </li>
        </template>
      </ul>
    </div>
  </template>
</div>
