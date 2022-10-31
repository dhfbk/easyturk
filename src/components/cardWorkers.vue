<template>
  <div class="bg-white rounded-md shadow-md p-4 mb-4">
    <div class="rounded-md -mx-4 -my-4" v-if="currPage.length > 0">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="sm-rotated tracking-widest sm:tracking-normal">
              <th class="text-xs text-white px-1">
                <div><span>WORKER ID</span></div>
              </th>
              <th class="text-xs text-white px-1" @click="sortedArr('total_hits', hitsSort)">
                <span>TOTAL HITs</span>
              </th>
              <th class="text-xs text-white px-1"><span>WRONG HITs</span></th>
              <th class="text-xs text-white px-1" @click="sortedArr('blocked', blockSort)"><span>BLOCKED</span></th>
              <th class="text-xs text-white px-1"><span>RESTRICTED</span></th>
              <th class="text-xs text-white px-1"><span>BLOCK</span></th>
              <th class="text-xs text-white px-1"><span>RESTRICT</span></th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr
              v-for="(i, k) in currPage"
              :key="k"
              class="hover:bg-gray-100"
              :class="k == currPage.length + 1 ? '' : 'border-b'"
            >
              <td class="border-r text-sm text-gray-700 font-medium">
                {{ i.worker_id }}
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                {{ i.total_hits }}
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                {{ i.wrong_hits }}
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                <span class="text-green-600" v-if="i.blocked == 0">No</span>
                <span class="text-red-600" v-else>Yes</span>
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                <span class="text-green-600" v-if="i.blocked == 0">No</span>
                <span class="text-red-600" v-else>Yes</span>
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                <button
                  class="ripple hover:bg-blue-600 bg-primary text-white transition-colors duration-100 ease-out py-0.5 px-1 rounded mx-auto m-0.5 hidden xs:flex"
                >
                  Block
                </button>
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                <button
                  class="ripple hover:bg-blue-600 bg-primary text-white transition-colors duration-100 ease-out py-0.5 px-1 rounded mx-auto m-0.5 hidden xs:flex"
                >
                  Restrict
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="flex justify-between items-center">
        <button
          @click="page--"
          :disabled="page == 0"
          class="ripple px-2 py-1 mb-4 ml-4 mt-2 bg-gray-200 hover:bg-gray-300 rounded transition-colors duration-100 ease-out"
        >
          Previous
        </button>
        <span>{{ page + 1 }}/{{ numPages }}</span>
        <button
          @click="page++"
          :disabled="page == numPages - 1"
          class="ripple px-2 py-1 mb-4 mr-4 mt-2 bg-gray-200 hover:bg-gray-300 rounded transition-colors duration-100 ease-out"
        >
          Next
        </button>
      </div>
    </div>
    <div v-else>No workers to display.</div>
  </div>
</template>

<script>
export default {
  name: 'cardWorkers',
  props: {
    projectData: Object,
    totals: Object,
  },
  data() {
    return {
      statusText: [],
      page: 0,
      currPage: [],
      currSort: [],
      hitsSort: true,
      blockSort: true,
      restrSort: true,
    }
  },
  computed: {
    workersNum() {
      return this.workersInfoArr.length
    },
    numPages() {
      return Math.ceil(this.workersNum / 10)
    },
    workersInfoArr() {
      return Object.values(this.projectData.workersInfo)
    },
  },
  created() {
    this.currSort = this.workersInfoArr
    this.currPage = this.currSort.slice(0, 10)
  },
  methods: {
    sortedArr(by, asc) {
      if (asc) {
        this.currSort.sort((a, b) => a[by] - b[by])
      } else {
        this.currSort.sort((a, b) => b[by] - a[by])
      }
      if (by == 'total_hits') {
        this.hitsSort = !this.hitsSort
        this.blockSort = true
        this.restrSort = true
      } else if (by == 'blocked') {
        this.blockSort = !this.blockSort
        this.hitsSort = true
        this.restrSort = true
      } else if (by == 'restricted') {
        this.restrSort = !this.restrSort
        this.hitsSort = true
        this.blockSort = true
      }
      if (this.page == 0) {
        this.currPage = this.currSort.slice(this.page * 10, this.page * 10 + 10)
      }
      this.page = 0
    },
  },
  watch: {
    page() {
      this.currPage = this.currSort.slice(this.page * 10, this.page * 10 + 10)
    },
  },
}
</script>

<style scoped>
table tr th {
  background: #0068b4;
}
/* top-left border-radius */
table tr:first-child th:first-child {
  border-top-left-radius: 0.375rem;
}

/* top-right border-radius */
table tr:first-child th:last-child {
  border-top-right-radius: 0.375rem;
}

/* bottom-left border-radius */
table tr:last-child td:first-child {
  border-bottom-left-radius: 0.375rem;
}

/* bottom-right border-radius */
table tr:last-child td:last-child {
  border-bottom-right-radius: 0.375rem;
}
</style>
