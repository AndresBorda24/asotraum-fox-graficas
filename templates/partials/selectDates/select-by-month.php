<div x-data="selectByMonth" class="mt-1 w-100">
  <template x-for="month in currentMonth">
    <button
    @click="sendEvent( month )"
    x-text="months[ month - 1 ]"
    class="btn btn-sm" style="font-size: .65rem;">
    </button>
  </template>
</div>
