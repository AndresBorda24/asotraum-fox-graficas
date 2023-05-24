<template x-if="Boolean(details)">
  <div class="p-2 rounded shadow" :style="{ backgroundColor: details.meta.color }">
    <div class="mb-2 border bg-body p-2 small">
      <span>Total Facturas: </span>
      <span
      class="fw-bold"
      x-text="details.meta.records"></span>
      <hr class="m-1">
      <span>Total: </span>
      <span
      class="fw-bold"
      x-text="formatter.format(details.meta.total)"></span>
    </div>

    <ul class="list-group d-block">
      <template x-for="t in details.data">
        <li class="list-group-item list-group-item-action d-flex small align-items-center py-1">
          <div class="flex-grow-1">
            <span
            class="fw-bold d-block"
            x-text="t.nombre"></span>
            <span
            class="small fw-light d-block"
            x-text="t.tercero"></span>
          </div>
          <div style="width: 120px;">
            <span
            class="fw-bold d-block text-end"
            x-text="formatter.format(t.total)"></span>
            <span
            class="small fw-light d-block text-end"
            x-text="t.records"></span>
          </div>
        </li>
      </template>
    </ul>
  </div>
</template>
