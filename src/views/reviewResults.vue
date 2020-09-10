<template>
    <div class="md:w-5/6 bg-white shadow-lg rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-4 mx-auto">
        <p class="text-2xl mb-4 text-orange-400">Review results</p>

        <p class="text-lg mb-4">Filter</p>

        <table class="table-auto">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" v-model="all" />
                    </th>
                    <th class="px-4 py-2">HIT ID</th>
                    <th class="px-4 py-2">Worker ID</th>
                    <th class="px-4 py-2">Lifetime Approval Rate</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="i in results" :key="i.HITId">
                    <td><input type="checkbox" v-if="i.status == 'Pending'" :value="all" /></td>
                    <td class="border px-4 py-2">{{ i.HITId }}</td>
                    <td class="border px-4 py-2">{{ i.workerId }}</td>
                    <td class="border px-4 py-2">{{ i.approvalRate }}</td>
                    <td class="border px-4 py-2">{{ i.date }}</td>
                    <td class="border px-4 py-2">{{ i.status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            all: false,
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
            ],
        }
    },
}
</script>

<style></style>
