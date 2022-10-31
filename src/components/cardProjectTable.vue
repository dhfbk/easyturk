<template>
  <div class="bg-white rounded-md shadow-md p-4 mb-4">
    <div class="rounded-md -mx-4 -my-4">
      <table class="w-full">
        <thead>
          <tr class="sm-rotated tracking-widest sm:tracking-normal">
            <th class="text-xs text-white px-1">
              <div><span># HITs</span></div>
            </th>
            <th class="text-xs text-white px-1"><span>APPROVED</span></th>
            <th class="text-xs text-white px-1"><span>REJECTED</span></th>
            <th class="text-xs text-white px-1"><span>COMPLETED</span></th>
            <th class="text-xs text-white px-1"><span>AVAILABLE</span></th>
            <th class="text-xs text-white px-1"><span>PENDING</span></th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr v-for="(i, k) in projectData.summary" :key="k" class="hover:bg-gray-100">
            <td class="border-r text-sm text-gray-700 font-medium border-b">
              {{ i.count }}
            </td>
            <td class="border-r text-sm text-gray-700 font-medium border-b">
              {{ i.assignments_approved }}
            </td>
            <td class="border-r text-sm text-gray-700 font-medium border-b">
              {{ i.assignments_rejected }}
            </td>
            <td class="border-r text-sm text-gray-700 font-medium border-b">
              {{ i.assignments_completed }}
            </td>
            <td class="border-r text-sm text-gray-700 font-medium border-b">
              {{ i.assignments_available }}
            </td>
            <td class="text-sm text-gray-700 font-medium border-b">
              {{ i.assignments_pending }}
            </td>
          </tr>
          <tr>
            <td class="border-r text-sm text-gray-100 bg-primary font-medium">
              {{ totals.count }}
            </td>
            <td class="border-r text-sm text-gray-100 bg-primary font-medium">
              {{ totals.assignments_approved }}
            </td>
            <td class="border-r text-sm text-gray-100 bg-primary font-medium">
              {{ totals.assignments_rejected }}
            </td>
            <td class="border-r text-sm text-gray-100 bg-primary font-medium">
              {{ totals.assignments_completed }}
            </td>
            <td class="border-r text-sm text-gray-100 bg-primary font-medium">
              {{ totals.assignments_available }}
            </td>
            <td class="text-sm text-gray-100 bg-primary font-medium">
              {{ totals.assignments_pending }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'cardProjectTable',
  props: {
    projectData: Object,
    totals: Object,
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
