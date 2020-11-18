<template>
    <div class="bg-white rounded-md shadow-md p-4 mb-4">
        <div v-if="!Object.keys(tableData).length">
            {{ emptyText }}
        </div>
        <div class="rounded-md border -mx-4 -my-4 overflow-x-auto" v-else>
            <table class="w-full rounded-md text-xs">
                <thead>
                    <tr>
                        <th v-for="(headText, k) in tableData.head" :key="k" class="text-white px-1">
                            {{ headText }}
                        </th>
                    </tr>
                </thead>
                <tbody class="text-center rounded-b-md">
                    <tr v-for="(bodyText, k) in tableData.body" :key="k" class="hover:bg-gray-100">
                        <td v-for="(bodyField, j) in bodyText" :key="j" class="border-r text-gray-700 border-b">
                            {{ bodyField }}
                        </td>
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
        emptyText: String
    },
    data() {
        return {

        }
    },
    created() {
        console.log(this.tableData);
    },
    methods: {
    }
}
</script>

<style scoped>

table {
    width: 99%;
    margin: 2px auto;
}

table tr th {
    background: #0068b4;
}

table td {
    white-space: nowrap;
    text-align: left;
    padding: 0 2px;
}

</style>
