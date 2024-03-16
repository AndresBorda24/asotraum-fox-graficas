<div x-data="selectByMonth" x-bind="events" class="mt-1 w-100">
  <!-- Trimestres  -->
  <div class="d-flex w-100 gap-1">
    <template x-for="_ in 4">
      <button
      style="font-size: .65rem;"
      x-text="'Trim.' + _"
      @click="byTrimestre(_ - 1)"
      :class="{ 'active': isSelected('T', _) }"
      class="btn btn-sm btn-outline-primary flex-grow-1"></button>
    </template>
  </div>

  <!-- Mensual -->
  <div class="d-flex w-100 flex-wrap my-1">
    <template x-for="(month, index) in months" :key="index">
      <button
      @click="byMonth( index )"
      x-text="month"
      :class="{ 'active': isSelected('M', index + 1), [`month-${index}`]: true }"
      class="btn btn-sm btn-outline-primary border-0 col-sm-1 col-2"
      style="font-size: .65rem;">
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
      :class="{ 'active': isSelected('S', _) }"
      class="btn btn-sm btn-outline-primary flex-grow-1"></button>
    </template>
  </div>
</div>
