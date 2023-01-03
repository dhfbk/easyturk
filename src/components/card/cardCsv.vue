<template>
  <div class="bg-white rounded-md shadow-md p-4 mb-4">
    <span class="font-bold">CSV status</span>
    <div class="mb-1">
      <span v-if="projectData.numData > 0">
        <div class="flex flex-row flex-wrap justify-between content-center items-center csvData">
          <span class="mr-2">
            <div>
              <span>Data:&nbsp;</span>
              <span class="text-green-600">uploaded</span>
            </div>
            <div>
              <span>Rows:&nbsp;</span>
              <span>{{ projectData.numData }}</span>
            </div>
          </span>
          <span class="flex flex-row buttonsData justify-center">
            <button
              class="px-2 py-1 ripple bg-gray-200 transition-colors duration-100 ease-out hover:bg-gray-300 rounded"
              @click="
                $router.push({
                  name: 'CSVDataView',
                  params: { projectId: projectData.id, type: 'data' },
                })
              "
            >
              View data
            </button>
            <button
              v-if="projectData.status < 1"
              @click="$emit('modal', 'deleteStd')"
              :content="'Delete standard csv'"
              v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
              class="ml-2 ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10 flex items-center justify-center"
            >
              <svg style="width: 24px; height: 24px" viewBox="0 0 24 24">
                <path
                  fill="rgba(26, 32, 44, 1)"
                  d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                />
              </svg>
              <span class="sr-only">Delete standard csv</span>
              <!-- <span class="sr-only" v-if="projectData.numData > 0">Overwrite standard csv</span>
                                <span class="sr-only" v-else>Upload standard csv</span> -->
            </button>
            <button
              v-if="projectData.status < 1"
              :content="projectData.numData > 0 ? 'Overwrite standard csv' : 'Upload standard csv'"
              v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
              @click="$emit('modal', 'std')"
              class="ml-2 ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10 flex items-center justify-center"
            >
              <svg style="width: 24px; height: 24px" viewBox="0 0 24 24">
                <path
                  fill="rgba(26, 32, 44, 1)"
                  d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M12,17V15H7V17H12M17,11H7V13H14.69C13.07,14.07 12,15.91 12,18C12,19.09 12.29,20.12 12.8,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19A2,2 0 0,1 21,5V12.8C20.12,12.29 19.09,12 18,12L17,12.08V11M17,9V7H7V9H17Z"
                />
              </svg>
              <span class="sr-only" v-if="projectData.numData > 0">Overwrite standard csv</span>
              <span class="sr-only" v-else>Upload standard csv</span>
            </button>
          </span>
        </div>
      </span>
      <span v-else>
        <div class="flex flex-row flex-wrap justify-between content-center items-center csvData">
          <span class="mr-2"> <span>Data:&nbsp;</span><span class="text-red-500"> not uploaded</span> </span>
          <span class="flex flex-row buttonsData justify-center">
            <button
              v-if="projectData.status < 1"
              :content="'Upload standard csv'"
              v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
              @click="$emit('modal', 'std')"
              class="ml-2 ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10 flex items-center justify-center"
            >
              <svg style="width: 24px; height: 24px" viewBox="0 0 24 24">
                <path
                  fill="rgba(26, 32, 44, 1)"
                  d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M12,17V15H7V17H12M17,11H7V13H14.69C13.07,14.07 12,15.91 12,18C12,19.09 12.29,20.12 12.8,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19A2,2 0 0,1 21,5V12.8C20.12,12.29 19.09,12 18,12L17,12.08V11M17,9V7H7V9H17Z"
                />
              </svg>
              <span class="sr-only">Upload standard csv</span>
            </button>
          </span>
        </div>
      </span>
    </div>
    <hr />
    <div class="mt-1">
      <span v-if="projectData.numGold > 0">
        <div class="flex flex-row flex-wrap justify-between content-center items-center csvData">
          <span class="mr-2">
            <div>
              <span>Gold:&nbsp;</span>
              <span class="text-green-600">uploaded</span>
            </div>
            <div>
              <span>Rows:&nbsp;</span>
              <span>{{ projectData.numGold }}</span>
            </div>
          </span>
          <span class="flex flex-row buttonsData justify-center">
            <button
              class="px-2 py-1 ripple bg-gray-200 transition-colors duration-100 ease-out hover:bg-gray-300 rounded"
              @click="
                $router.push({
                  name: 'CSVDataView',
                  params: { projectId: projectData.id, type: 'gold' },
                })
              "
            >
              View gold
            </button>
            <button
              v-if="projectData.status < 1"
              @click="$emit('modal', 'deleteGld')"
              :content="'Delete gold csv'"
              v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
              class="ml-2 ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10 flex items-center justify-center"
            >
              <svg style="width: 24px; height: 24px" viewBox="0 0 24 24">
                <path
                  fill="rgba(26, 32, 44, 1)"
                  d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                />
              </svg>
              <span class="sr-only">Delete gold csv</span>
            </button>
            <button
              v-if="projectData.status < 1"
              :content="projectData.numGold > 0 ? 'Overwrite gold csv' : 'Upload gold csv'"
              v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
              @click="$emit('modal', 'gld')"
              :class="{ 'cursor-not-allowed': projectData.numData == 0 }"
              class="ml-2 ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 24px; height: 24px">
                <path
                  fill="rgba(26, 32, 44, 1)"
                  d="m11.99806,14.99892l-5,0l0,2l5,0m5,-6l-10,0l0,2l7.69,0c-1.62,1.07 -2.69,2.91 -2.69,5c0,1.09 0.29,2.12 0.8,3l-7.8,0c-1.11,0 -2,-0.9 -2,-2l0,-14c0,-1.11 0.89,-2 2,-2l14,0a2,2 0 0 1 2,2l0,7.8c-0.88,-0.51 -1.91,-0.8 -3,-0.8l-1,0.08l0,-1.08m0,-2l0,-2l-10,0l0,2"
                />
                <g>
                  <path
                    fill="rgba(26, 32, 44, 1)"
                    d="m15.75665,19.4418l1.01849,0c0,0.54999 0.69767,1.01849 1.52774,1.01849c0.83007,0 1.52774,-0.46851 1.52774,-1.01849c0,-0.56017 -0.52962,-0.76387 -1.64996,-1.03377c-1.0796,-0.2699 -2.42402,-0.606 -2.42402,-2.02171c0,-0.91155 0.74859,-1.68561 1.78237,-1.94532l0,-1.11016l1.52774,0l0,1.11016c1.03377,0.25972 1.78237,1.03377 1.78237,1.94532l-1.01849,0c0,-0.54999 -0.69767,-1.01849 -1.52774,-1.01849c-0.83007,0 -1.52774,0.46851 -1.52774,1.01849c0,0.56017 0.52962,0.76387 1.64996,1.03377c1.0796,0.2699 2.42402,0.606 2.42402,2.02171c0,0.91155 -0.74859,1.68561 -1.78237,1.94532l0,1.11016l-1.52774,0l0,-1.11016c-1.03377,-0.25972 -1.78237,-1.03377 -1.78237,-1.94532z"
                  />
                </g>
              </svg>
              <span class="sr-only" v-if="projectData.numGold > 0">Overwrite gold csv</span>
              <span class="sr-only" v-else>Upload gold csv</span>
            </button>
          </span>
        </div>
      </span>
      <span v-else>
        <div class="flex flex-row flex-wrap justify-between content-center items-center csvData">
          <span class="mr-2"> <span>Gold:&nbsp;</span><span class="text-red-500"> not uploaded</span> </span>
          <span class="flex flex-row buttonsData justify-center">
            <button
              v-if="projectData.status < 1"
              :content="'Upload gold csv'"
              v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
              @click="$emit('modal', 'gld')"
              :class="{ 'cursor-not-allowed': projectData.numData == 0 }"
              class="ml-2 ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10 flex items-center justify-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 24px; height: 24px">
                <path
                  fill="rgba(26, 32, 44, 1)"
                  d="m11.99806,14.99892l-5,0l0,2l5,0m5,-6l-10,0l0,2l7.69,0c-1.62,1.07 -2.69,2.91 -2.69,5c0,1.09 0.29,2.12 0.8,3l-7.8,0c-1.11,0 -2,-0.9 -2,-2l0,-14c0,-1.11 0.89,-2 2,-2l14,0a2,2 0 0 1 2,2l0,7.8c-0.88,-0.51 -1.91,-0.8 -3,-0.8l-1,0.08l0,-1.08m0,-2l0,-2l-10,0l0,2"
                />
                <g>
                  <path
                    fill="rgba(26, 32, 44, 1)"
                    d="m15.75665,19.4418l1.01849,0c0,0.54999 0.69767,1.01849 1.52774,1.01849c0.83007,0 1.52774,-0.46851 1.52774,-1.01849c0,-0.56017 -0.52962,-0.76387 -1.64996,-1.03377c-1.0796,-0.2699 -2.42402,-0.606 -2.42402,-2.02171c0,-0.91155 0.74859,-1.68561 1.78237,-1.94532l0,-1.11016l1.52774,0l0,1.11016c1.03377,0.25972 1.78237,1.03377 1.78237,1.94532l-1.01849,0c0,-0.54999 -0.69767,-1.01849 -1.52774,-1.01849c-0.83007,0 -1.52774,0.46851 -1.52774,1.01849c0,0.56017 0.52962,0.76387 1.64996,1.03377c1.0796,0.2699 2.42402,0.606 2.42402,2.02171c0,0.91155 -0.74859,1.68561 -1.78237,1.94532l0,1.11016l-1.52774,0l0,-1.11016c-1.03377,-0.25972 -1.78237,-1.03377 -1.78237,-1.94532z"
                  />
                </g>
              </svg>
              <span class="sr-only">Upload gold csv</span>
            </button>
          </span>
        </div>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'cardCsv',
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
