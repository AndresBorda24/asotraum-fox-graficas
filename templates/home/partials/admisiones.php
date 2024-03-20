<div
  x-data="admisionesGenearl"
  id="admisiones-general-container"
  class="home-graph p-3 text-muted border-dark-subtle position-relative rounded overflow-auto bg-light"
>
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-2 gap-2">
    <div class="flex-grow-1">
      <h4 class="m-0 fs-6 badge text-bg-warning">Tipo de Atención - Admisiones</h4>
      <template x-if="data.length">
        <p class="my-2">
            Mostrando información del día de hoy: 
            <span x-text="data[0].fecha" class="badge text-bg-primary"></span>
        </p>
      </template>

      <template x-if="data.length == 0">
        <p class="my-2">
          Cargando información, por favor espera &#8230;
        </p>
      </template>
    </div>
  </div>

  <div>
    <!-- Contenedor de la grafica -->
    <div class="mx-auto bg-body border shadow-sm" id="admisiones-general"></div>
  </div>
</div>