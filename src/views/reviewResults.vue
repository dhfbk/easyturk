<template>
    <div class="md:w-5/6 bg-white shadow-lg rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-4 mx-auto">
        <p class="text-2xl mb-4 text-orange-400">Review results</p>

        <p class="text-lg mb-4">Results per page</p>
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

        <p class="text-lg mb-4">Filter</p>

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
                    <th class="px-2 py-6">
                        <input type="checkbox" v-model="all" />
                    </th>
                    <th class="text-sm text-gray-700">HIT ID</th>
                    <th class="text-sm text-gray-700">Worker ID</th>
                    <th class="hidden sm:table-cell text-sm text-gray-700">
                        Lifetime Approval Rate
                    </th>
                    <th class="hidden md:table-cell text-sm text-gray-700">Date</th>
                    <th class="hidden md:table-cell text-sm text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="text-center ">
                <tr v-for="i in current" :key="i.HITId" class=" border-b">
                    <td class="border">
                        <input type="checkbox" v-if="i.status == 'Pending'" />
                    </td>
                    <td class="border px-4 py-2">{{ i.HITId }}</td>
                    <td class="border px-4 py-2">{{ i.workerId }}</td>
                    <td class="border px-4 py-2">{{ i.approvalRate }}</td>
                    <td class="border px-4 py-2">{{ i.date }}</td>
                    <td class="border px-4 py-2">{{ i.status }}</td>
                </tr>
            </tbody>
        </table>
        <div class="grid grid-cols-3" v-if="results.length > numPerPage">
            <button
                @click="page--"
                v-if="results.length > numPerPage && page >= 1"
                class="bg-gray-300 w-48 hover:bg-gray-400 py-2 px-4 rounded m-2 focus:outline-none place-self-start"
            >
                Previous
            </button>
            <div v-else></div>
            <p class="text-center">Page {{ page + 1 }} of {{ pageNum + 1 }}</p>
            <button
                @click="page++"
                v-if="results.length > numPerPage && page < pageNum"
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
            this.pageNum = Math.ceil(this.results.length / this.numPerPage) - 1
            this.current = []
            this.currentPage()
        },
        numPerPage: function() {
            this.pageNum = Math.ceil(this.results.length / this.numPerPage) - 1
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
                if (this.results[i]) {
                    this.current.push(this.results[i])
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
    },
    created() {
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
            results: [
                {
                    HITId: 's8duh33k3hbs',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '56h6g4g353gt',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'gj450k059kj0',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'gr49iv9t20coek',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },

                {
                    HITId: 'fj40tgk95jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'hf49tgj58j50p',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'jf04jf3499',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'fkek3838fu8ugj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: '3rf448tj84jg8',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: '4f48j8jg0gk',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'f4jfjg9ijg949',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'skrdl0s3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'mxwmi98e9i0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'xakir3802kdk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'fn4nunfnf83q',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'aaakea8490',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'jweofjcirjjoi90',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'adoiejvn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'jncuorj9320ef',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '498tjfgjos94',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'oi6jfir843e9i',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '48tu4ifkm9w0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'eji93dkm20',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'eacoem4492dko',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'aklma9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'jfjfje9t4',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'foifj9k05l0tk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'f4u940kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 's8duh33k3hb',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '56h6g4g353g',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'gj450k059kj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'gr49iv9t20cok',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },

                {
                    HITId: 'fj40tgk9jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'hf49tgj5850p',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'jf04jf499',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'fkek388fu8ugj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: '3rf448j84jg8',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: '4f48j8g0gk',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'f4jfjg9jg949',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'skrdls3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'mxwmi989i0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'xakir302kdk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'fn4nnfnf83q',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'aaakea890',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'jweofcirjjoi90',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'adoievn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'jncurj9320ef',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '498tfgjos94',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'oi6jir843e9i',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '48t4ifkm9w0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'eji3dkm20',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'eacom4492dko',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'akma9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'jfjfe9t4',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'foif9k05l0tk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'f4u90kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '8duh33k3hb',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '6h6g4g353g',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'g450k059kj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'g49iv9t20cok',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },

                {
                    HITId: 'j40tgk9jy',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'f49tgj5850p',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'f04jf499',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'kek388fu8ugj',
                    workerId: 'hi4984u3fj903',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'rf448j84jg8',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: '448j8g0gk',
                    workerId: 'hi4984u3fj903',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'fjfjg9jg949',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'krdls3jr940',
                    workerId: 'hi4984u3fj903',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '30/30',
                },
                {
                    HITId: 'mwmi989i0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'xair302kdk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'f4nnfnf83q',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'aakea890',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'weofcirjjoi90',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'doievn38h0w',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'jcurj9320ef',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '48tfgjos94',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'o6jir843e9i',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Rejected',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '4t4ifkm9w0',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'i3dkm20',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'ecom4492dko',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'ama9k4990',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'jffe9t4',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Approved',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: 'fif9k05l0tk',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
                {
                    HITId: '4u90kg5',
                    workerId: 'h5uh1ffn3o45',
                    status: 'Pending',
                    date: '27/08/2020',
                    approvalRate: '48/50',
                },
            ],
        }
    },
}
</script>

<style></style>
