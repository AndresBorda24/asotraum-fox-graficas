<section
  x-data="Medicos"
  x-bind="events"
  id="medicos-container"
  class="rounded border border-dashed bg-body-tertiary border-dark-subtle position-relative"
>
  <div class="d-flex flex-column">
    <header class="text-dark-subtle p-3 border-bottom">
      <span class="fs-4">Médicos</span>
      <p class="text-muted m-0">Mostrando los 10 médicos que más cirugías han realizado.</p>
    </header>
    <div id="medicos"></div>
  </div>
</section>
