<template>
    <div class="md:w-5/6 bg-white shadow-lg rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-4 mx-auto">
        <p class="text-2xl mb-4 text-orange-400">Review results</p>
        <p class="text-lg mb-4">Filters</p>

        <p class="text-lg mb-4">Results per page:</p>
        <input
            class="w-12"
            type="number"
            id="quantity"
            name="quantity"
            min="10"
            max="50"
            step="10"
            v-model="numPerPage"
        />

        <!-- <p class="text-lg mb-4">Show:</p>
        <form>
            <select name="choice">
                <option value="All" @click="trim('All')">All</option>
                <option value="Pending" @click="trim('Pending')">Only Pending Review</option>
                <option value="Approved" @click="trim('Approved')">Only Approved</option>
                <option value="Rejected" @click="trim('Rejected')">Only Rejected</option>
            </select>
        </form> -->

        <table class="">
            <thead>
                <!-- <tr>
                    <th class="px-1 py-1">
                        <input type="checkbox" v-model="all" />
                    </th>
                    <th class="px-4 py-2">HIT ID</th>
                    <th class="px-4 py-2"></th>
                    <th class="px-4 py-2"></th>
                    <th class="px-4 py-2"></th>
                    <th class="px-4 py-2"></th>
                </tr> -->
                <tr class="text-center bg-orange-300 border-b border-grey uppercase">
                    <th class="px-2 py-6" @click="selectAll(), (all = !all)">
                        <span
                            class="bg-white border-2 ml-1 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
                        >
                            <input type="checkbox" class="opacity-0 absolute" />
                            <svg
                                :class="!all ? '' : 'hidden'"
                                class="fill-current w-4 h-4 text-green-500 pointer-events-none"
                                viewBox="0 0 20 20"
                            >
                                <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                            </svg>
                        </span>
                    </th>
                    <th class="text-sm text-gray-700">HIT ID</th>
                    <th class="text-sm text-gray-700">Worker ID</th>
                    <th class="hidden sm:table-cell text-sm text-gray-700">
                        Lifetime Approval Rate
                    </th>
                    <th class="hidden md:table-cell text-sm text-gray-700">Date</th>
                    <th class="hidden md:table-cell text-sm text-gray-700" @click="sort('status')">
                        Status
                        <svg
                            class="inline"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="#4a5568"
                            width="24px"
                            height="24px"
                        >
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z" />
                        </svg>
                    </th>
                </tr>
            </thead>
            <tbody class="text-center ">
                <tr v-for="i in current" :key="i.HITId" class=" border-b">
                    <td class="px-3 py-4" @click="i.selected = !i.selected">
                        <span
                            class="bg-white border-2 ml-1 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
                        >
                            <input type="checkbox" class="opacity-0 absolute" />
                            <svg
                                :class="i.selected ? '' : 'hidden'"
                                class="fill-current w-4 h-4 text-green-500 pointer-events-none"
                                viewBox="0 0 20 20"
                            >
                                <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                            </svg>
                        </span>
                    </td>
                    <td class="border px-4 py-2">{{ i.HITId }}</td>
                    <td class="border px-4 py-2">{{ i.workerId }}</td>
                    <td class="border px-4 py-2">{{ i.approvalRate }}</td>
                    <td class="border px-4 py-2">{{ i.date }}</td>
                    <td class="border px-4 py-2">{{ i.status }}</td>
                </tr>
            </tbody>
        </table>
        <div class="grid grid-cols-3" v-if="data.length > numPerPage">
            <button
                @click="page--"
                v-if="data.length > numPerPage && page >= 1"
                class="bg-gray-300 w-48 hover:bg-gray-400 py-2 px-4 rounded m-2 focus:outline-none place-self-start"
            >
                Previous
            </button>
            <div v-else></div>
            <p class="text-center">Page {{ page + 1 }} of {{ pageNum + 1 }}</p>
            <button
                @click="page++"
                v-if="data.length > numPerPage && page < pageNum"
                class="bg-gray-300 w-48 hover:bg-gray-400 py-2 px-4 rounded m-2 focus:outline-none  place-self-end"
            >
                Next
            </button>
            <div v-else></div>
        </div>
    </div>
</template>

<script>
export default {
    watch: {
        page: function() {
            this.pageNum = Math.ceil(this.data.length / this.numPerPage) - 1
            this.current = []
            this.currentPage()
        },
        numPerPage: function() {
            this.pageNum = Math.ceil(this.data.length / this.numPerPage) - 1
            this.current = []
            this.currentPage()
        },
    },

    methods: {
        currentPage() {
            for (
                let i = this.page * this.numPerPage;
                i < this.page * this.numPerPage + parseInt(this.numPerPage);
                i++
            ) {
                if (this.data[i]) {
                    this.current.push(this.data[i])
                }
                // console.log(i)
                // console.log(
                //     this.numPerPage,
                //     this.page,
                //     this.page * this.numPerPage + parseInt(this.numPerPage),
                //     this.pageNum
                // )
            }
        },
        trim(mode) {
            if (mode == 'All') {
                this.data = this.results
            } else {
                for (let i = 0; this.results.length; i++) {
                    if (this.results[i].status == mode) {
                        this.data.push(this.results[i])
                        console.log(this.data.length)
                    }
                }
            }
        },
        selectAll() {
            for (let i = 0; i < this.data.length; i++) {
                if (this.all == false) {
                    this.data[i].selected = false
                } else {
                    this.data[i].selected = true
                }
            }
        },
        // sort(mode){
        //     for(let i = 0; i < this.results.length; i++){

        //     }
        // }
    },
    created() {
        this.data = this.results
        this.pageNum = Math.ceil(this.results.length / this.numPerPage) - 1
        this.currentPage()
    },
    data() {
        return {
            all: false,
            page: 0,
            numPerPage: 50,
            current: [],
            pageNum: 1,
            data: null,
            results: [
                {
                    HITId: 's8duh33k3hbs',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '56h6g4g353gt',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'gj450k059kj0',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'gr49iv9t20coek',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'fj40tgk95jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'hf49tgj58j50p',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'jf04jf3499',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'fkek3838fu8ugj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: '3rf448tj84jg8',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: '4f48j8jg0gk',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'f4jfjg9ijg949',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'skrdl0s3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'mxwmi98e9i0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'xakir3802kdk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'fn4nunfnf83q',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'aaakea8490',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'jweofjcirjjoi90',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'adoiejvn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'jncuorj9320ef',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '498tjfgjos94',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'oi6jfir843e9i',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '48tu4ifkm9w0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'eji93dkm20',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'eacoem4492dko',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'aklma9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'jfjfje9t4',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'foifj9k05l0tk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'f4u940kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 's8duh33k3hb',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '56h6g4g353g',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'gj450k059kj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'gr49iv9t20cok',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'fj40tgk9jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'hf49tgj5850p',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'jf04jf499',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'fkek388fu8ugj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: '3rf448j84jg8',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: '4f48j8g0gk',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'f4jfjg9jg949',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'skrdls3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'mxwmi989i0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'xakir302kdk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'fn4nnfnf83q',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'aaakea890',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'jweofcirjjoi90',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'adoievn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'jncurj9320ef',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '498tfgjos94',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'oi6jir843e9i',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '48t4ifkm9w0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'eji3dkm20',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'eacom4492dko',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'akma9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'jfjfe9t4',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'foif9k05l0tk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'f4u90kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: '8duh33k3hb',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '6h6g4g353g',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'g450k059kj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'g49iv9t20cok',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'j40tgk9jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'f49tgj5850p',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'f04jf499',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'kek388fu8ugj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'rf448j84jg8',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: '448j8g0gk',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'fjfjg9jg949',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'krdls3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'mwmi989i0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'xair302kdk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'f4nnfnf83q',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'aakea890',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'weofcirjjoi90',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'doievn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'jcurj9320ef',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '48tfgjos94',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'o6jir843e9i',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '4t4ifkm9w0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'i3dkm20',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'ecom4492dko',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'ama9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'jffe9t4',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'fif9k05l0tk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '4u90kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
            ],
        }
    },
}
</script>

<style></style>
