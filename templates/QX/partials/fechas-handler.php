<div
  x-data="DatesHandler"
  class="d-flex justify-content-start gap-2 align-items-center lh-1"
>
  <label class="small">
    Desde:
    <input
      type="date"
      class="form-control form-control-sm d-inline-block"
      name="dates-from"
      x-model="from"
    >
  </label>
  <label class="small">
    Hasta:
    <input
      type="date"
      class="form-control form-control-sm d-inline-block"
      name="dates-to"
      x-model="to"
    >
  </label>
  <button class="btn btn-sm btn-light" @click="updateDates">
    <span><?= $this->fetch("./icons/search.php") ?></span>
  </button>
</div>