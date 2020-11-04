<template>
    <div class="relative lg:w-5/6 pt-2 pb-8 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
        <div v-if="loading"></div>
        <div v-else>
            <h1 class="text-2xl mb-4 text-primary">HITs results (project ID: {{ $route.params.projectId }})</h1>
            <div class="flex flex-col xs:flex-row justify-end mb-2">
                <p class="text-md my-auto mr-1">View:</p>
                <div class="relative">
                    <select
                        class="appearance-none h-full rounded border block appearance-none bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        name="sortBy"
                        id="sortBy"
                        @change="view(viewType)"
                        v-model="viewType"
                    >
                        <option value="dots">
                            Dot Matrix
                        </option>
                        <option value="table">
                            Table
                        </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>
            <tableHIT :tmpData="progressData" v-if="viewType == 'table'" />
            <dotMatrixHIT :tmpData="progressData" v-else />
        </div>
    </div>
</template>
<script>
import tableHIT from '../components/tableHIT.vue'
import dotMatrixHIT from '../components/dotMatrixHIT.vue'
import axios from 'axios'
export default {
    name: 'HITList',
    components: {
        tableHIT,
        dotMatrixHIT,
    },
    data() {
        return {
            id: '',
            loading: true,
            progressData: [],
            viewType: 'dots',
        }
    },
    created() {
        this.getData()
    },
    methods: {
        getData() {
            this.id = this.$route.params.projectId
            axios
                .get(this.APIURL + '?action=getProjectInfo&id=' + this.id)
                .then(res => {
                    this.progressData = res.data.summary
                    this.loading = false
                })
                .catch(err => {
                    console.log(err)
                })
        },
        view(type) {
            if (type != this.viewType) {
                this.viewType = type
            }
        },
    },
}
</script>

<style scoped></style>
