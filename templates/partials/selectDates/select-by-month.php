<div x-data="selectByMonth" class="mt-1 w-100">
  <!-- Trimestres  -->
  <div class="d-flex w-100 gap-1">
    <template x-for="_ in 4">
      <button
      style="font-size: .65rem;"
      x-text="'Trim.' + _"
      @click="byTrimestre(_ - 1)"
      class="btn btn-sm flex-grow-1 border rounded-0"></button>
    </template>
  </div>

  <!-- Mensual -->
  <div class="d-flex w-100 flex-wrap">
    <template x-for="(month, index) in months" :key="index">
      <button
      @click="byMonth( index )"
      x-text="month"
      class="btn btn-sm col-sm-1 col-2" style="font-size: .65rem;">
      </button>
    </template>
  </div>

    <!-- Semestres  -->
  <div class="d-flex w-100 gap-1">
    <template x-for="_ in 2">
      <button
      style="font-size: .65rem;"
      x-text="'Sem. ' + _"
      @click="bySemestre(_ - 1)"
      class="btn btn-sm flex-grow-1 border rounded-0"></button>
    </template>
  </div>
</div>
