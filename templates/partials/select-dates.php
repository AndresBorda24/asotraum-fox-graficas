<div class="d-flex small gap-1 align-items-center" x-data="selectDates">
  <div>
    <label class="form-label m-0">Desde:</label>
    <input
    x-model="dateStart"
    min="<?= date('Y') ?>-01-01"
    :max="getMaxStartDate"
    type="date"
    class="form-control form-control-sm">
  </div>
  <div>
    <label class="form-label m-0">Hasta:</label>
    <input
    x-model="dateEnd"
    type="date"
    class="form-control form-control-sm">
  </div>
  <button class="btn btn-sm btn-outline-primary" @click="sendEvent">#</button>
</div>
