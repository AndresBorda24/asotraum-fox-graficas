<div
class="small d-flex flex-wrap align-items-center flex-fill"
x-data="selectDates"
x-bind="events">
  <span class="fw-bold flex-grow-1">
    (<span x-text="getText"></span>)
  </span>
  <div>
    <div class="d-flex gap-1 align-items-center">
      <button
      class="btn btn-sm btn-outline-primary p-1 pt-0"
      @click="sendEvent">
        <?= $this->fetch('./partials/icons/lupa.php') ?>
      </button>
    </div>
  </div>
  <?= $this->fetch('./partials/selectDates/select-by-month.php') ?>
</div>
