<section
  x-data="MotivosCancelacion"
  x-bind="events"
  id="motivos-cancelacion-container"
  style="grid-template-columns: 1fr 1fr;"
  class="rounded border border-dashed bg-body-tertiary border-dark-subtle position-relative d-flex d-md-grid flex-column gap-2"
>
  <div class="d-flex flex-column">
    <header class="text-dark-subtle p-3 border-bottom">
      <span class="fs-4">Motivos de Cancelación</span>
      <p class="text-muted m-0">Mostrando los 9 motivos de cancelación más recurrentes en el rango de fechas indicado. </p>
    </header>
    <div id="motivos-cancelacion" class="flex-grow-1 -d-flex -mx-auto -justify-content-center"></div>
  </div>
  <!-- <div class="p-2"> </div> -->
  <template x-if="data !== null">
    <div class="p-3 d-flex align-items-center">
      <table class="w-100 table table-primary table-bordered table-sm small m-0">
        <thead>
          <tr>
            <th colspan="2">Motivo Cancelación</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="([key, total], index) in Object.entries(data.data.total)">
            <tr>
              <td :style="{ backgroundColor: colors[index] }"></td>
              <td :style="{ backgroundColor: colors[index]+30 }">
                <span x-text="key"></span>
                <template x-if="key === 'otros'">
                  <ul class="m-0 small">
                    <template x-for="([key, total], index) in Object.entries(data.data.otros)">
                      <li class="d-flex justify-content-between px-3">
                        <span x-text="Boolean(key) ? key : 'Motivo x'"></span>
                        <span x-text="total"></span>
                      </li>
                    </template>
                  </ul>
                </template>
              </td>
              <td x-text="total"></td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </template>
</section>