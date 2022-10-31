<template>
  <div class="bg-white rounded-md shadow-md p-4 mb-4">
    <div v-for="(n, i) in titles.length" :key="n">
      <div v-if="titles[i]">
        <hr v-if="i != 0" />
        <span class="font-bold">{{ titles[i] }}:&nbsp;</span>
        <p class="overflow-ellipsis" v-html="projectData[i]"></p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'cardPlain',
  props: {
    projectData: Array,
    titles: Array,
  },
}
</script>

<style></style>
