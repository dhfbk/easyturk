<template>
  <div class="bg-white rounded-md shadow-md p-4 mb-4">
    <div class="rounded-md -mx-4 -my-4" v-if="assignmentData.body.length > 0">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="sm-rotated tracking-widest sm:tracking-normal">
              <th v-for="head in assignmentData.head" :key="head" class="text-xs text-white px-1">
                {{ head }}
              </th>
              <th class="text-xs text-white px-1">Reject</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr
              v-for="(i, k) in assignmentData.body"
              :key="k"
              class="hover:bg-gray-100"
              :class="k != assignmentData.body.length - 1 ? 'border-b' : ''"
            >
              <td class="border-r text-sm text-gray-700 font-medium">
                {{ i[0] }}
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                {{ i[1] }}
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                {{ i[2] }}
              </td>
              <td class="border-r text-sm text-gray-700 font-medium">
                <button
                  v-if="i[1] == 'Rejected'"
                  class="ripple hover:bg-blue-600 bg-primary flex justify-center text-white transition-colors duration-100 ease-out py-0.5 px-1 rounded mx-auto m-0.5 hidden xs:flex w-16"
                  @click="rejectAssignment(i, k)"
                >
                  <svg
                    :class="loading[k] ? 'animate-spin fill-current' : 'hidden'"
                    style="width: 20px; height: 20px"
                    viewBox="0 0 24 24"
                  >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                  </svg>
                  <span :class="loading[k] ? 'hidden' : 'inline'"> Unreject </span>
                </button>
                <button
                  v-else
                  class="ripple hover:bg-blue-600 bg-primary text-white transition-colors duration-100 ease-out py-0.5 px-1 rounded mx-auto m-0.5 hidden xs:flex"
                  @click="rejectAssignment(i, k)"
                >
                  <svg
                    :class="loading[k] ? 'animate-spin fill-current' : 'hidden'"
                    style="width: 20px; height: 20px"
                    viewBox="0 0 24 24"
                  >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                  </svg>
                  <span :class="loading[k] ? 'hidden' : 'inline'"> Reject </span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-else>No workers to display.</div>
  </div>
</template>

<script>
import globalMixin from '../../globalMixin.js'

export default {
  mixins: [globalMixin],
  name: 'cardAssignments',
  props: {
    assignmentData: Object,
    emptyText: String,
  },
  data() {
    return {
      loading: [],
    }
  },
  created() {
    for (let i = 0; i < this.assignmentData.body.length; i++) {
      this.loading.push(false)
    }
  },
  methods: {
    /**
     * Reject an assignment or unreject it
     * @param {String} assignment ID of the assignment to alter
     * @param {*} index index of the assignment used for changing the loading status
     */
    rejectAssignment(assignment, index) {
      if (this.loading.every((element) => element == false)) {
        this.loading[index] = true
        this.API()
          .post(
            '',
            {
              assignment: assignment[2],
              action: 'unReject',
            },
            { 'Content-Type': 'application/x-www-form-urlencoded' }
          )
          .then((response) => {
            if (response.data.result == 'ERR') {
              this.$emit('rejected', 'Error: assignment could not be changed')
            } else {
              this.$emit('rejected', '')
            }
          })
          .catch(() => {
            this.$emit('rejected', 'Error: assignment could not be changed')
          })
          .then(() => {
            this.loading[index] = false
          })
      }
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
