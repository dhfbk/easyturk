<template>
  <div class="flex flex-col my-6">
    <div class="flex flex-row mx-auto w-11/12">
      <span :style="{ width: submitted + '%' }" v-if="submitted != 0">
        <div
          :content="progressData.hits_submitted + '/' + progressData.hits_total"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          class="h-8 bg-green-500 transition duration-100 ease-in-out transform hover:-translate-y-1 hover:shadow-md rounded-l"
          :class="[notPublished == 0 && published == 0 ? 'rounded-r' : '']"
        ></div>
      </span>
      <span :style="{ width: published + '%' }" v-if="published != 0">
        <div
          :content="progressData.hits_inserted - progressData.hits_submitted + '/' + progressData.hits_total"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          class="h-8 bg-blue-500 transition duration-100 ease-in-out transform hover:-translate-y-1 hover:shadow-md"
          :class="[submitted == 0 ? 'rounded-l' : '', notPublished == 0 ? 'rounded-r' : '']"
        ></div>
      </span>
      <span :style="{ width: notPublished + '%' }" v-if="notPublished != 0">
        <div
          :content="progressData.hits_total - progressData.hits_inserted + '/' + progressData.hits_total"
          v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
          class="h-8 bg-gray-400 transition duration-100 ease-in-out transform hover:-translate-y-1 rounded-r hover:shadow-md"
        ></div>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    progressData: Object,
  },
  data() {
    return {
      notPublished: 0,
      published: 0,
      submitted: 0,
    }
  },
  created() {
    this.notPublished =
      ((parseInt(this.progressData.hits_total) - parseInt(this.progressData.hits_inserted)) /
        parseInt(this.progressData.hits_total)) *
      100
    this.submitted = (parseInt(this.progressData.hits_submitted) / parseInt(this.progressData.hits_total)) * 100
    if (this.progressData.hits_submitted != this.progressData.hits_inserted) {
      this.published =
        ((parseInt(this.progressData.hits_inserted) - parseInt(this.progressData.hits_submitted)) /
          parseInt(this.progressData.hits_total)) *
        100
    }
  },
}
</script>

<style></style>
