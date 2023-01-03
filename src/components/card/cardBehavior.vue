<template>
  <div class="bg-white rounded-md shadow-md p-4 mb-4">
    <div v-for="(n, i) in arrayCardBehavior.length" :key="i">
      <p v-if="arrayCardBehavior[i]" class="overflow-ellipsis" v-html="arrayCardBehavior[i]"></p>
      <hr v-if="n != arrayCardBehavior.length && arrayCardBehavior[n]" />
    </div>
    <button
      class="px-2 py-1 ripple bg-gray-200 transition-colors duration-100 ease-out hover:bg-gray-300 rounded float-right"
      @click="$emit('modal', 'behavior')"
    >
      Edit behavior
    </button>
    <div class="clear-both"></div>
  </div>
</template>

<script>
export default {
  name: 'cardBehavior',
  props: {
    projectData: Object,
  },
  data() {
    return {
      arrayCardBehavior: [],
    }
  },
  created() {
    //it's a lot of code, but it's really just formatting the content
    this.arrayCardBehavior = [
      this.projectData.hit_details && this.projectData.hit_details.rejectIfGoldWrong
        ? '<span class="font-bold">' +
          (this.projectData.hit_details.rejectIfGoldWrong ? 'Reject' : 'Accept anyway') +
          '</span>' +
          ' if gold is wrong and extend HIT to <span class="font-bold">' +
          this.projectData.hit_details.assignNumber +
          '</span> assignments (min ' +
          this.projectData.workers +
          '). Reason of reject: <span class="font-bold">' +
          this.projectData.hit_details.rejectReason +
          '</span>'
        : null,
      this.projectData.hit_details && this.projectData.hit_details.acceptIfGoldRight
        ? 'Automatically accept if gold is right'
        : null,
      this.projectData.hit_details && this.projectData.hit_details.block_worker_fast
        ? '<span class="font-bold">' +
          (this.projectData.hit_details.block_worker_fast ? 'Block' : 'Reject') +
          '</span>' +
          ' the worker if he/she spends less than <span class="font-bold">' +
          this.projectData.hit_details.rejectTime +
          '</span> seconds on a HIT'
        : null,
      this.projectData.hit_details && this.projectData.hit_details.block_worker_bad
        ? '<span class="font-bold">' +
          (this.projectData.hit_details.block_worker_bad ? 'Block' : 'Reject') +
          '</span>' +
          ' the worker if he/she misclassifies the gold <span class="font-bold">' +
          this.projectData.hit_details.missNumber +
          '</span> times on <span class="font-bold">' +
          this.projectData.hit_details.missNumberTotal +
          '</span> consecutive HITs'
        : null,
      this.projectData.hit_details && this.projectData.hit_details.reject_old
        ? 'Reject pending assignments from blocked/restricted users'
        : null,
    ]
    this.arrayCardBehavior = this.arrayCardBehavior.filter(Boolean)
    if (this.arrayCardBehavior.every((element) => element === null)) {
      this.arrayCardBehavior = ['Behavior not set for the projectData.']
    }
  },
}
</script>
