<template>
  <div class="bg-white rounded-md shadow-md p-4 mb-4">
    <div class="text-lg mb-4">
      <div class="flex content-center flex-col sm:flex-row" v-if="dati.type == 'HIT'">
        <svg style="width: 24px" viewBox="0 0 24 24">
          <path
            d="M19 3H14.82C14.4 1.84 13.3 1 12 1S9.6 1.84 9.18 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3M7 8H9V12H8V9H7V8M10 17V18H7V17.08L9 15H7V14H9.25C9.66 14 10 14.34 10 14.75C10 14.95 9.92 15.14 9.79 15.27L8.12 17H10M11 4C11 3.45 11.45 3 12 3S13 3.45 13 4 12.55 5 12 5 11 4.55 11 4M17 17H12V15H17V17M17 11H12V9H17V11Z"
          />
        </svg>
        <div>
          <span class="font-bold">HIT total:&nbsp;</span>
          <span>{{ dati.totale }}</span>
        </div>
      </div>
      <div class="flex content-center flex-col sm:flex-row" v-if="dati.type == 'aggregate'">
        <svg style="width: 24px" viewBox="0 0 24 24">
          <path d="M16,11.78L20.24,4.45L21.97,5.45L16.74,14.5L10.23,10.75L5.46,19H22V21H2V3H4V17.54L9.5,8L16,11.78Z" />
        </svg>
        <div>
          <span class="font-bold">Completed HITs:&nbsp;</span>
          <span>{{ dati.totale }}</span>
        </div>
      </div>
      <div class="flex content-center flex-row items-center flex-wrap" v-if="dati.type == 'assignment'">
        <svg style="width: 24px" viewBox="0 0 24 24">
          <path
            d="M17,9H7V7H17M17,13H7V11H17M14,17H7V15H14M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z"
          />
        </svg>
        <div>
          <span class="font-bold">Assignment total:</span>
        </div>
        <div class="flex flew-row justify-between ml-1">
          <span class="flex items-center">
            {{ dati.totale }}
          </span>
          <button
            :content="'Extend assignments'"
            v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
            class="ml-2 ripple bg-gray-200 hover:bg-gray-300 text-gray-900 rounded h-10 w-10 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 24px; height: 24px">
              <path
                fill="rgba(26, 32, 44, 1)"
                d="M2,16H10V14H2M18,14V10H16V14H12V16H16V20H18V16H22V14M14,6H2V8H14M14,10H2V12H14V10Z"
              />
              />
            </svg>
            <span class="sr-only">Extend assignments</span>
          </button>
        </div>
      </div>
    </div>
    <div class="flex justify-evenly flex-wrap">
      <ve-progress
        v-for="progress in dati.ellipse_progress"
        :key="progress.caption"
        :progress="progress.progress"
        :legend-value="progress.legend_value"
        :color="'#0091FF'"
        :size="pieSize"
        :half="progress.half"
        :angle="progress.angle"
        class="mx-2 mb-2"
      >
        <template #legend-value>
          <span>/{{ dati.totale }}</span>
        </template>
        <template #legend-caption>
          <p>{{ progress.caption }}</p>
        </template>
      </ve-progress>
    </div>
    <div class="w-full flex justify-start -mt-4" v-if="dati.type == 'HIT'">
      <router-link
        :to="{
          name: 'HITlist',
          params: {
            projectId: dati.idPrj,
          },
        }"
      >
        <button
          type="submit"
          class="ripple hover:bg-primary flex-row items-center py-2 px-4 bg-transparent rounded transition-colors duration-100 ease-out border-2 border-solid border-primary hover:text-white h-full"
        >
          Go to HITs
        </button>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'cardAnalytics',
  props: {
    dati: Object,
  },
  data() {
    return {
      pieSize: 150,
    }
  },
}
</script>

<style scoped>
path {
  fill: rgba(45, 55, 72, 1);
}
</style>
