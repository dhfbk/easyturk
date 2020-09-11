<template>
    <div class="rounded shadow-lg mx-0 md:mx-4 p-4 col-span-3 row-span-3">
        <div class="font-bold text-lg m-1">Workers</div>
        <div class="container">
            <table class="w-full flex flex-row flex-no-wrap sm:bg-white sm:shadow-lg my-5">
                <thead class="text-white">
                    <tr
                        class="bg-orange-500 flex flex-col flex-no wrap sm:table-row mb-2 sm:mb-0"
                        v-for="n in 10"
                        :key="n"
                    >
                        <th class="p-3 text-left">Worker ID</th>
                        <th class="p-3 text-left">Approval Rate</th>
                        <th class="p-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="flex-1 sm:flex-none">
                    <tr
                        class="flex flex-col flex-no wrap sm:table-row mb-2 sm:mb-0"
                        v-for="n in 10"
                        :key="n"
                    >
                        <td
                            class="transition duration-200 ease-in-out border-grey-light border hover:bg-gray-100 p-3"
                        >
                            workerId
                        </td>
                        <td
                            class="transition duration-200 ease-in-out border-grey-light border hover:bg-gray-100 p-3"
                        >
                            approvalRate
                        </td>
                        <td
                            class="transition duration-200 ease-in-out border-grey-light border hover:bg-gray-100 p-3"
                        >
                            status
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button
            class="transition duration-200 ease-in-out bg-white hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow mt-4"
        >
            View all
        </button>
    </div>
</template>

<script>
export default {
    name: 'tabellaWorker',
}
</script>

<style scoped>
@media (min-width: 640px) {
    table {
        display: inline-table !important;
    }

    thead tr:not(:first-child) {
        display: none;
    }
}

td:not(:last-child) {
    border-bottom: 0;
}

th:not(:last-child) {
    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
}
</style>
