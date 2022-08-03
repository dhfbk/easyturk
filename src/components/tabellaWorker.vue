<template>
    <div class="container mx-auto">
        <table class="w-full sm:w-5/6 mx-auto shadow-lg rounded">
            <thead class="bg-primary">
                <tr class="text-center border-b text-white border-grey uppercase">
                    <th class="py-2 px-2 text-sm">Worker ID</th>
                    <th class="py-2 px-2 text-sm">Approval Rate</th>
                    <th class="py-2 px-2 hidden sm:table-cell text-sm">Status</th>
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
                class="
                    py-2
                    px-4
                    bg-white
                    mt-4
                    rounded
                    transition-colors
                    duration-100
                    ease-out
                    border-2 border-solid border-primary
                    text-gray-800
                    hover:text-gray-100 hover:bg-primary
                    
                "
            >
                View all
            </button>
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

<style scoped></style>
