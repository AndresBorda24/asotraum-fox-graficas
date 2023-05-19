<div x-data="selectByMonth">
  <template x-for="month in currentMonth">
    <button
    @click="sendEvent( month )"
    x-text="months[ month - 1 ]"
    class="btn btn-sm" style="font-size: .6rem;">
    </button>
  </template>
</div>
