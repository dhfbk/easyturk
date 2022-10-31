<template>
  <div class="bg-white rounded-md shadow-md p-4 mb-4">
    <span class="font-bold">Status:</span>
    <span class="flex flex-col justify-between buttonsData items-start">
      <span v-if="projectData.status == 0 && projectData.numData > 0">
        <span class="text-green-500">Data uploaded. </span>Ready to
        <span class="font-bold text-primary"> create HITs.</span>
      </span>
      <span v-else-if="projectData.status == 0 && projectData.numData == 0">
        <span class="text-green-500">Project created. </span
        ><span class="font-bold text-primary">Upload csv data </span>and<span class="font-bold text-primary">
          csv gold</span
        >
        to advance.
      </span>
      <span v-if="projectData.status == 1">
        <span class="text-green-500">HITs created. </span>Ready to
        <span class="font-bold text-primary"> set the layout.</span>
      </span>
      <span v-if="projectData.status == 2">
        <span class="text-green-500">HIT layout set. </span>Ready to finally
        <span class="font-bold text-primary"> publish the project!</span>
      </span>
      <span v-if="projectData.status == 3">
        <span class="text-green-500">Project has been launched and is now available to the Workers. </span>You can
        launch more HITs or
        <span class="font-bold text-primary"> check the results.</span>
      </span>
      <button
        @click="$emit('modal', 'instructions')"
        class="ripple px-2 py-1 mt-1 bg-gray-200 hover:bg-gray-300 rounded transition-colors duration-100 ease-out self-end"
      >
        Need help?
      </button>
    </span>
  </div>
</template>

<script>
export default {
  name: 'cardStatus',
  props: {
    projectData: Object,
  },
}
</script>

<style scoped>
@media screen and (max-width: 648px) {
  .buttonsData {
    margin: 0 auto;
    width: 100%;
    align-items: center;
  }
}
</style>
