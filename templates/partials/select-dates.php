<div
class="small d-flex flex-wrap align-items-center"
x-data="selectDates"
x-bind="events">
  <span class="fw-bold flex-grow-1">
    (<span x-text="getText"></span>)
  </span>
  <div>
    <div class="d-flex gap-1 align-items-center">
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
      <button
      class="btn btn-sm btn-outline-primary p-1 pt-0"
      @click="sendEvent">
        <?= $this->fetch('./partials/icons/lupa.php') ?>
      </button>
    </div>
  </div>
  <?= $this->fetch('./partials/selectDates/select-by-month.php') ?>
</div>
