<template>
    <div class="relative lg:w-5/6 pt-2 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
        <div v-if="loading"></div>
        <div class="flex justify-between flex-wrap items-center" v-else>
            <div class="flex flex-row place-content-center w-full mb-4">
                <button
                    @click="$router.push({ name: 'projectView', params: { projectId: $route.params.projectId } })"
                    :content="'Back'"
                    v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
                    class="rounded ripple bg-transparent hover:bg-gray-400 p-2 focus:outline-none"
                >
                    <svg class="inline" style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
                    </svg>
                    <span class="sr-only">Back to project</span>
                </button>
                <span class="text-lg sm:text-xl text-primary mr-auto ml-2 overflow-ellipsis mt-2">
                    HITs results (project ID: {{ $route.params.projectId }})
                </span>
                <div class="flex flex-col xs:flex-row justify-end">
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
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                        >
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <tableHIT :sortedData="sortedData" v-if="viewType == 'table'" />
            <dotMatrixHIT :sortedData="sortedData" v-else />
        </div>
    </div>
</template>
<script>
import tableHIT from '../components/tableHIT.vue'
import dotMatrixHIT from '../components/dotMatrixHIT.vue'
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
            viewType: 'table',
            sortedData: [],
        }
    },
    created() {
        this.getData()
    },
    methods: {
        getData() {
            this.id = this.$route.params.projectId
            this.API.get('?action=getProjectInfo&id=' + this.id)
                .then(res => {
                    this.progressData = res.data.summary

                    //count to set defualt data view

                    var cout = 0
                    for (let i = 0; i < this.progressData.length; i++) {
                        cout += this.progressData[i].count
                        if (cout >= 20) {
                            this.viewType = 'dots'
                            break
                        }
                    }

                    //

                    //data sorting: first completed hits, then incomplete hits (each category ordered by most approved hits  to least approved)

                    var arrComp = []
                    var arrNotComp = []
                    var arrNotTou = []

                    for (let i = 0; i < this.progressData.length; i++) {
                        if (this.progressData[i].assignments_completed == 0) {
                            arrNotTou.push(this.progressData[i])
                        } else if (this.progressData[i].assignments_available > 0) {
                            arrNotComp.push(this.progressData[i])
                        } else {
                            arrComp.push(this.progressData[i])
                        }
                    }

                    arrComp = arrComp.sort(function(a, b) {
                        return (
                            a.assignments_rejected - b.assignments_rejected ||
                            b.assignments_approved - a.assignments_approved ||
                            b.assignments_available - a.assignments_available
                        )
                    })

                    arrNotComp = arrNotComp.sort(function(a, b) {
                        return (
                            a.assignments_rejected - b.assignments_rejected ||
                            b.assignments_approved - a.assignments_approved ||
                            b.assignments_available - a.assignments_available
                        )
                    })

                    this.sortedData = arrComp.concat(arrNotComp).concat(arrNotTou)

                    console.log(this.sortedData)

                    //

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
