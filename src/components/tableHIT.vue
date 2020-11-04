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
        <table class="w-full shadow-lg rounded">
            <thead>
                <tr class="text-center bg-primary border-b border-gray-300 uppercase ">
                    <th class="p-1 text-sm text-white">HIT ID</th>
                    <th class="p-1 text-sm text-white">Status</th>
                    <th class="p-1 hidden sm:table-cell text-sm text-white">Approved</th>
                    <th class="p-1 hidden md:table-cell text-sm text-white">Rejected</th>
                    <th class="p-1 hidden md:table-cell text-sm text-white">Available</th>
                    <th class="p-1 hidden lg:table-cell text-sm text-white">Time remaining</th>
                </tr>
            </thead>
            <tbody class="bg-white text-center" v-for="(hit, id) in tmpData" :key="id">
                <tr class="border-b border-gray-300 hover:bg-gray-100" v-for="(c, i) in hit.count" :key="i">
                    <td class="table-cell py-1 border-r border-gray-300">
                        <p class="text-sm text-gray-700 font-medium">{{ i }}</p>
                    </td>

                    <td class="hidden sm:table-cell py-1 border-r border-gray-300">
                        <p class="text-sm text-gray-700 font-medium">
                            {{ hit.assignments_available == 0 ? 'Completed' : 'Assignable' }}
                        </p>
                    </td>
                    <td class="hidden md:table-cell py-1 border-r border-gray-300">
                        <p class="text-sm text-gray-700 font-medium">{{ hit.assignments_approved }}</p>
                    </td>
                    <td class="hidden md:table-cell py-1 border-r border-gray-300">
                        <p class="text-sm text-gray-700 font-medium">{{ hit.assignments_rejected }}</p>
                    </td>
                    <td class="hidden lg:table-cell py-1 border-r border-gray-300">
                        <p class="text-sm text-gray-700 font-medium">{{ hit.assignments_available }}</p>
                    </td>
                    <td class="hidden lg:table-cell py-1">
                        <p class="text-sm text-gray-700 font-medium">prova</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: 'tableHIT',
    // props: {
    //     tmpData: Array,
    // },
    data() {
        return {
            sortType: 'all',
            sortOptions: [
                { text: 'Tutte', value: 'all' },
                { text: 'Assignable', value: 'Assignable' },
                { text: 'Disposed', value: 'Disposed' },
                { text: 'Reviewable', value: 'Reviewable' },
            ],
            filtri: {
                stato: 'all',
                creazione: 'all',
            },
            hitDaMostrare: [],
            tmpData: [
                {
                    assignments_approved: '3',
                    assignments_available: '0',
                    assignments_completed: '3',
                    assignments_pending: '0',
                    assignments_rejected: '0',
                    count: 252,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '3',
                    assignments_available: '0',
                    assignments_completed: '4',
                    assignments_pending: '0',
                    assignments_rejected: '1',
                    count: 45,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '2',
                    assignments_available: '0',
                    assignments_completed: '4',
                    assignments_pending: '0',
                    assignments_rejected: '2',
                    count: 78,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '1',
                    assignments_available: '0',
                    assignments_completed: '4',
                    assignments_pending: '0',
                    assignments_rejected: '3',
                    count: 26,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '0',
                    assignments_available: '0',
                    assignments_completed: '4',
                    assignments_pending: '0',
                    assignments_rejected: '4',
                    count: 5,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '1',
                    assignments_available: '2',
                    assignments_completed: '2',
                    assignments_pending: '0',
                    assignments_rejected: '1',
                    count: 12,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '2',
                    assignments_available: '1',
                    assignments_completed: '3',
                    assignments_pending: '0',
                    assignments_rejected: '1',
                    count: 10,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '2',
                    assignments_available: '1',
                    assignments_completed: '2',
                    assignments_pending: '0',
                    assignments_rejected: '0',
                    count: 29,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '1',
                    assignments_available: '2',
                    assignments_completed: '1',
                    assignments_pending: '0',
                    assignments_rejected: '0',
                    count: 21,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '1',
                    assignments_available: '1',
                    assignments_completed: '3',
                    assignments_pending: '0',
                    assignments_rejected: '2',
                    count: 3,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '0',
                    assignments_available: '2',
                    assignments_completed: '2',
                    assignments_pending: '0',
                    assignments_rejected: '2',
                    count: 5,
                    max_assignments: '4',
                },
                {
                    assignments_approved: '0',
                    assignments_available: '1',
                    assignments_completed: '3',
                    assignments_pending: '0',
                    assignments_rejected: '3',
                    count: 9,
                    max_assignments: '4',
                },
            ],
        }
    },
    created() {
        // this.filtraHIT()
    },
    methods: {
        sortBy(mode) {
            if (this.filtri.stato != mode) {
                this.filtri.stato = mode
                this.filtraHIT()
            }
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
