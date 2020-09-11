<template>
    <div class="container mx-auto">
        <table class="w-full sm:w-5/6 mx-auto shadow-lg rounded">
            <thead>
                <tr class="text-center bg-orange-300 border-b border-grey uppercase">
                    <th class="py-2 px-2 text-sm text-gray-700">Worker ID</th>
                    <th class="py-2 px-2 text-sm text-gray-700">Approval Rate</th>
                    <th class="py-2 px-2 hidden sm:table-cell text-sm text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white text-center">
                <tr class="border-b border-grey-light hover:bg-gray-100" v-for="n in 10" :key="n">
                    <td class="table-cell py-2 border-r border-grey-light">workerId</td>
                    <td class="table-cell py-2 border-r border-grey-light">approvalRate</td>
                    <td class="hidden sm:table-cell py-2 border-r border-grey-light">status</td>
                </tr>
            </tbody>
        </table>
        <router-link to="/workers" v-if="showBtn">
            <button
                type="submit"
                class="py-2 px-4 bg-white mt-4 rounded-md transition duration-150 ease-in-out border-2 border-solid border-orange-400 hover:bg-orange-400 focus:outline-none"
            >View all</button>
        </router-link>
    </div>
</template>

<script>
export default {
    name: 'tabellaWorker',
    data() {
        return {
            showBtn: false,
        }
    },
    created() {
        if (this.$route.path != '/workers') {
            this.showBtn = true
        }
    },
}
</script>

<style scoped>
</style>
