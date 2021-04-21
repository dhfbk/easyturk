<template>
    <div class="bg-white rounded-md shadow-md p-4 mb-4">
        <div v-if="!Object.keys(tableData).length">
            {{ emptyText }}
        </div>
        <div class="rounded-md -mx-4 -my-4 overflow-x-auto" v-else>
            <table class="w-full">
                <thead>
                    <tr class="sm-rotated">
                        <th v-for="(headText, k) in tableData.head" :key="k" class="text-xs text-white px-1">
                            {{ headText }}
                        </th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr
                        v-for="(bodyText, k) in tableData.body"
                        :key="k"
                        :class="k != tableData.body.length - 1 ? 'border-b' : ''"
                        class="hover:bg-gray-100"
                    >
                        <td
                            v-for="(bodyField, j) in bodyText"
                            :key="j"
                            :class="j != bodyText.length - 1 ? 'border-r' : ''"
                            class="text-sm text-gray-700 font-medium px-1"
                            v-html="bodyField"
                        ></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: 'cardTable',
    props: {
        tableData: Object,
        emptyText: String,
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
