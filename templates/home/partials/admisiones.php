<div x-data="admisionesGenearl" id="admisiones-general-container" class="position-relative">
  <div class="p-3 pb-0 rounded bg-light shadow">
    <header class="mb-2 position-relative">
      <h3>
        Admisiones
        <span class="fs-6" x-text="data[0] && `(${data[0].total})`"></span>
      </h3>
      <span
      x-text="data[0] && data[0].fecha"
      class="badge text-bg-primary position-absolute top-0 end-0"
      ></span>
    </header>
    <div class="d-flex align-items-center flex-column">
      <div id="admisiones-general"></div>
    </div>
  </div>
</div>