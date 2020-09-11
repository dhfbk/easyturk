<template>
    <div class="rounded shadow-lg mx-4 p-4 col-span-3 row-span-3">
        <div class="font-bold text-lg m-1">Workers</div>
        <div class="container">
            <table class="w-full sm:w-5/6 mx-auto shadow-lg rounded">
                <thead>
                    <tr class="text-center bg-orange-300 border-b border-grey uppercase">
                        <th class="py-2 px-2 text-sm text-gray-700">Worker ID</th>
                        <th class="py-2 px-2 text-sm text-gray-700">Approval Rate</th>
                        <th class="py-2 px-2 hidden sm:table-cell text-sm text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-center">
                    <tr
                        class="border-b border-grey-light hover:bg-gray-100"
                        v-for="n in 10"
                        :key="n"
                    >
                        <td class="table-cell py-2 border-r border-grey-light">workerId</td>
                        <td class="table-cell py-2 border-r border-grey-light">approvalRate</td>
                        <td class="hidden sm:table-cell py-2 border-r border-grey-light">status</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button
            class="transition duration-200 ease-in-out bg-white hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow mt-4 focus:outline-none"
        >View all</button>
    </div>
</template>

<script>
export default {
    name: 'tabellaWorker',
}
</script>

<style scoped>
</style>
