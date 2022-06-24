<template>
    <div class="relative lg:w-5/6 pt-2 pb-8 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
        <button
            @click="$router.go(-1)"
            :content="'Back'"
            v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
            class="rounded ripple bg-transparent hover:bg-gray-300 p-2 focus:outline-none"
        >
            <svg class="inline" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
            </svg>
            <span class="sr-only">Back</span>
        </button>
        <p class="text-lg sm:text-xl text-primary mr-auto ml-2 overflow-ellipsis">Review results</p>
        <div class="container mx-auto">
            <div class="mb-2">
                <p class="text-lg mb-2">Filters</p>
                <div class="flex flex-row justify-start mb-4">
                    <p class="text-md my-auto mr-1">Results per page:</p>
                    <input
                        class="
                            appearance-none
                            h-full
                            w-20
                            rounded
                            border
                            block
                            appearance-none
                            bg-white
                            border-gray-400
                            text-gray-700
                            pl-2
                            pr-1
                            py-2
                            leading-tight
                            focus:outline-none focus:bg-white focus:border-gray-500
                            mr-3
                        "
                        type="number"
                        id="quantity"
                        name="quantity"
                        min="10"
                        max="50"
                        step="10"
                        v-model="numPerPage"
                    />
                    <p class="text-md my-auto mr-1">Show:</p>
                    <div class="relative">
                        <select
                            class="
                                appearance-none
                                h-full
                                rounded
                                border
                                block
                                appearance-none
                                w-full
                                bg-white
                                border-gray-400
                                text-gray-700
                                py-2
                                pl-2
                                pr-12
                                leading-tight
                                focus:outline-none focus:bg-white focus:border-gray-500
                            "
                            name="choice"
                            id="choice"
                            @change="onlyShow(sortType)"
                            v-model="sortType"
                        >
                            <option v-for="i in types" :value="i" :key="i">{{ i }}</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                        >
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                    <button
                        class="
                            inline-flex
                            flex-row
                            items-center
                            py-2
                            px-4
                            ml-auto
                            rounded
                            transition
                            duration-100
                            ease-out
                            bg-gray-400
                            hover:bg-gray-300
                            focus:outline-none
                        "
                    >
                        <svg style="width: 24px; height: 24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z" />
                        </svg>
                        <span class="ml-1">Download SVG</span>
                    </button>
                </div>
            </div>
            <resultsTable :current="current" v-on:selectAll="selectAll()" v-on:sort="sort(mode)" />
            <div class="grid grid-cols-3 mt-2" v-if="data.length > numPerPage">
                <button
                    @click="page--"
                    v-if="data.length > numPerPage && page >= 1"
                    class="bg-gray-400 w-48 hover:bg-gray-300 py-2 px-4 rounded m-2 focus:outline-none place-self-start"
                >
                    Previous
                </button>
                <div v-else></div>
                <p class="text-center">Page {{ page + 1 }} of {{ pageNum + 1 }}</p>
                <button
                    @click="page++"
                    v-if="data.length > numPerPage && page < pageNum"
                    class="bg-gray-400 w-48 hover:bg-gray-300 py-2 px-4 rounded m-2 focus:outline-none place-self-end"
                >
                    Next
                </button>
                <div v-else></div>
            </div>
        </div>
    </div>
</template>

<script>
import resultsTable from '../components/resultsTable'
export default {
    components: {
        resultsTable,
    },
    watch: {
        page: function() {
            this.pageNum = Math.ceil(this.data.length / this.numPerPage) - 1
            this.current = []
            this.currentPage()
        },
        numPerPage: function() {
            this.pageNum = Math.ceil(this.data.length / this.numPerPage) - 1
            this.page = 0
            this.current = []
            this.currentPage()
        },
    },

    methods: {
        //preleva i dati da mostrare nella pagina corrente
        currentPage() {
            for (
                let i = this.page * this.numPerPage;
                i < this.page * this.numPerPage + parseInt(this.numPerPage);
                i++
            ) {
                if (this.data[i]) {
                    this.current.push(this.data[i])
                }
            }
        },
        //usato per mostrare solamente i dati che hanno un certo stato
        onlyShow(x) {
            this.data = []
            if (x != 'All') {
                for (let i = 0; i < this.results.length; i++) {
                    if (this.results[i].status == x) {
                        this.data.push(this.results[i])
                    }
                }
            } else {
                this.data = this.results
            }
            // console.log(x)

            this.page = 0
            this.current = []
            this.currentPage()
        },
        //seleziona tutte le checkbox nella pagina corrente
        selectAll() {
            this.all = !this.all
            for (let i = 0; i < this.data.length; i++) {
                if (this.all == false) {
                    this.data[i].selected = false
                } else {
                    this.data[i].selected = true
                }
            }
        },
        //effettua il sorting per il parametro che viene ricevuto
        sort(mode) {
            this.data.sort((x, y) => {
                return x.status == mode ? -1 : y.status == mode ? 1 : 0
            })
            if (mode == 'Submitted') {
                this.mode = 'Approved'
            } else if (mode == 'Approved') {
                this.mode = 'Rejected'
            } else {
                this.mode = 'Submitted'
            }
            this.current = []
            this.currentPage()
        },
    },
    created() {
        this.data = this.results
        this.pageNum = Math.ceil(this.results.length / this.numPerPage) - 1
        this.currentPage()
    },
    data() {
        return {
            sortType: 'All',
            types: ['All', 'Submitted', 'Approved', 'Rejected'],
            page: 0,
            numPerPage: 50,
            current: [],
            pageNum: 1,
            data: null,
            mode: 'Submitted',
            results: [
                {
                    HITId: 's8duh33k3hbs',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'fj40tgk95jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'skrdl0s3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'adoiejvn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'aklma9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'f4u940kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 's8duh33k3hb',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'fj40tgk9jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'skrdls3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'adoievn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'akma9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'f4u90kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: '8duh33k3hb',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: false,
                },
                {
                    HITId: 'j40tgk9jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                    selected: true,
                },
                {
                    HITId: 'krdls3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: 'doievn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: false,
                },
                {
                    HITId: 'ama9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
                    status: 'Submitted',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                    selected: true,
                },
                {
                    HITId: '4u90kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Submitted',
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
