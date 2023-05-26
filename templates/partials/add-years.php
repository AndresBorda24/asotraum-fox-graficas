<div
x-data="addYears(years)"
class="d-flex flex-align-center flex-wrap gap-1">
  <select
  class="form-select form-select-sm w-auto"
  style="font-size: .75rem;"
  x-model="selectYear"
  @change="appendYear">
    <option value="" selected>Agrega A&ntilde;o</option>
    <template x-for="y in availableYears">
      <option x-text="y"></option>
    </template>
  </select>
  <template x-for="(y, index) in years">
    <button
    x-text="y"
    :class="{
      'closable-button': years.length > 1
    }"
    @click="removeYear(index)"
    class="btn btn-sm border"
    style="font-size: .65rem;">
    </button>
  </template>
</div>
