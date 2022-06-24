<template>
    <div class="container mx-auto">
        <!-- <div class="flex flex-col xs:flex-row justify-end mb-2">
            <p class="text-md my-auto mr-1">Status:</p>
            <div class="relative">
                <select
                    class="appearance-none h-full rounded border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    name="sortBy"
                    id="sortBy"
                    @change="sortBy(sortType)"
                    v-model="sortType"
                >
                    <option v-for="item in sortOptions" :key="item.value" :value="item.value">
                        {{ item.text }}
                    </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                    </svg>
                </div>
            </div>
        </div> -->
        <div class="overflow-x-auto">
            <table class="w-full shadow-lg rounded border">
                <thead>
                    <tr class="text-center bg-primary border-b border-gray-300 uppercase">
                        <th class="p-1 text-sm text-white">HIT ID</th>
                        <th class="p-1 text-sm text-white">Status</th>
                        <th class="p-1 table-cell text-sm text-white" @click="sort('assignments_approved', orderApp)">
                            Approved<svg
                                class="inline"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="white"
                                width="24px"
                                height="24px"
                            >
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z" />
                            </svg>
                        </th>
                        <th class="p-1 table-cell text-sm text-white" @click="sort('assignments_rejected', orderRej)">
                            Rejected<svg
                                class="inline"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="white"
                                width="24px"
                                height="24px"
                            >
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z" />
                            </svg>
                        </th>
                        <th class="p-1 table-cell text-sm text-white" @click="sort('assignments_available', orderAva)">
                            Available<svg
                                class="inline"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="white"
                                width="24px"
                                height="24px"
                            >
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z" />
                            </svg>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white text-center">
                    <tr class="border-b border-gray-300 hover:bg-gray-100" v-for="(hit, id) in current" :key="id">
                        <td class="table-cell py-1 border-r border-gray-300">
                            <router-link :to="{ name: 'viewHIT', params: { hitId: hit.id } }">
                                <p class="text-sm text-gray-700 font-medium">{{ hit.id }}</p>
                            </router-link>
                        </td>

                        <td class="table-cell py-1 border-r border-gray-300">
                            <p class="text-sm text-gray-700 font-medium">
                                {{ hit.assignments_available == 0 ? 'Completed' : 'Assignable' }}
                            </p>
                        </td>
                        <td class="table-cell py-1 border-r border-gray-300">
                            <p class="text-sm text-gray-700 font-medium">{{ hit.assignments_approved }}</p>
                        </td>
                        <td class="table-cell py-1 border-r border-gray-300">
                            <p class="text-sm text-gray-700 font-medium">{{ hit.assignments_rejected }}</p>
                        </td>
                        <td class="table-cell py-1 border-r border-gray-300">
                            <p class="text-sm text-gray-700 font-medium">{{ hit.assignments_available }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="grid grid-cols-3 mt-2" v-if="totalData.length > numPerPage">
                <button
                    @click="page--"
                    v-if="totalData.length > numPerPage && page >= 1"
                    class="
                        bg-gray-400
                        md:w-48
                        w-24
                        hover:bg-gray-300
                        py-2
                        px-4
                        rounded
                        m-2
                        focus:outline-none
                        place-self-start
                    "
                >
                    Previous
                </button>
                <div v-else></div>
                <p class="text-center">Page {{ page + 1 }} of {{ pageNum + 1 }}</p>
                <button
                    @click="page++"
                    v-if="totalData.length > numPerPage && page < pageNum"
                    class="
                        bg-gray-400
                        md:w-48
                        w-24
                        hover:bg-gray-300
                        py-2
                        px-4
                        rounded
                        m-2
                        focus:outline-none
                        place-self-end
                    "
                >
                    Next
                </button>
                <div v-else></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'tableHIT',
    props: {
        sortedData: Array,
    },
    data() {
        return {
            page: 0,
            numPerPage: 20,
            current: [],
            pageNum: 1,
            totalData: [],
            orderApp: 'asc',
            orderRej: 'asc',
            orderAva: 'asc',
        }
    },
    created() {
        // this.filtraHIT()
        for (let i = 0; i < this.sortedData.length; i++) {
            for (let x = 0; x < this.sortedData[i].count; x++) {
                var hit = {
                    assignments_available: this.sortedData[i].assignments_available,
                    assignments_approved: this.sortedData[i].assignments_approved,
                    assignments_rejected: this.sortedData[i].assignments_rejected,
                    id: this.sortedData[i].hits[x],
                }
                this.totalData.push(hit)
            }
        }
        this.pageNum = Math.ceil(this.totalData.length / this.numPerPage) - 1
        this.currentPage()
    },
    watch: {
        page: function() {
            this.pageNum = Math.ceil(this.totalData.length / this.numPerPage) - 1
            this.current = []
            this.currentPage()
        },
        // numPerPage: function() {
        //     this.pageNum = Math.ceil(this.totalData.length / this.numPerPage) - 1
        //     this.page = 0
        //     this.current = []
        //     this.currentPage()
        // },
    },

    methods: {
        sortBy(mode) {
            if (this.filtri.stato != mode) {
                this.filtri.stato = mode
                this.filtraHIT()
            }
        },
        currentPage() {
            for (
                let i = this.page * this.numPerPage;
                i < this.page * this.numPerPage + parseInt(this.numPerPage);
                i++
            ) {
                if (this.totalData[i]) {
                    this.current.push(this.totalData[i])
                }
            }
        },
        sort(mode, order) {
            if (order == 'asc') {
                this.totalData.sort((x, y) => {
                    return x[mode] - y[mode]
                })
            } else {
                this.totalData.sort((x, y) => {
                    return y[mode] - x[mode]
                })
            }
            if (order == 'asc') {
                if (mode == 'assignments_approved') {
                    this.orderApp = 'desc'
                } else if (mode == 'assignments_rejected') {
                    this.orderRej = 'desc'
                } else if (mode == 'assignments_available') {
                    this.orderAva = 'desc'
                }
            } else {
                if (mode == 'assignments_approved') {
                    this.orderApp = 'asc'
                } else if (mode == 'assignments_rejected') {
                    this.orderRej = 'asc'
                } else if (mode == 'assignments_available') {
                    this.orderAva = 'asc'
                }
            }
            this.current = []
            this.page = 0
            this.currentPage()
        },
        // mostra solo le HIT di una categoria (quella passata nell funzione filtraHIT)
        // filtraHIT() {
        //     if (this.filtri.stato == 'all') {
        //         this.hitDaMostrare = JSON.parse(JSON.stringify(this.datiHIT))
        //     } else {
        //         this.hitDaMostrare = JSON.parse(JSON.stringify(this.datiHIT))
        //         let i = 0
        //         while (this.hitDaMostrare[i] != undefined) {
        //             if (this.hitDaMostrare[i].HITStatus != this.filtri.stato) {
        //                 this.hitDaMostrare.splice(i, 1)
        //                 i--
        //             }
        //             i++
        //         }
        //     }
        // },
    },
}
</script>

<style></style>
