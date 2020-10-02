<template>
    <div class="container mx-auto">
        <div class="flex flex-row justify-end mb-2">
            <p class="text-md my-auto mr-1">Status:</p>
            <div class="relative">
                <select
                    class="appearance-none h-full rounded border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    name="sortBy"
                    id="sortBy"
                    @change="sortBy(sortType)"
                    v-model="sortType"
                >
                    <option v-for="item in sortOptions" :key="item.value" :value="item.value">
                        {{
                        item.text
                        }}
                    </option>
                </select>
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                >
                    <svg
                        class="fill-current h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                        />
                    </svg>
                </div>
            </div>
        </div>
        <table class="w-full shadow-lg rounded">
            <thead>
                <tr class="text-center bg-primary border-b border-gray-300 uppercase tracking-tight">
                    <th class="py-2 px-2 text-sm text-white">HIT ID</th>
                    <th class="py-2 px-2 text-sm text-white">Status</th>
                    <th class="py-2 px-2 hidden sm:table-cell text-sm text-white">Creation</th>
                    <th class="py-2 px-2 hidden md:table-cell text-sm text-white">N. of assignments</th>
                    <th class="py-2 px-2 hidden md:table-cell text-sm text-white">Submitted</th>
                    <th class="py-2 px-2 hidden lg:table-cell text-sm text-white">Approved</th>
                    <th class="py-2 px-2 hidden lg:table-cell text-sm text-white">Rejected</th>
                </tr>
            </thead>
            <tbody class="bg-white text-center">
                <tr
                    class="border-b border-gray-300 hover:bg-gray-100"
                    v-for="hit in hitDaMostrare"
                    :key="hit.HITId"
                >
                    <td class="table-cell py-2 border-r border-gray-300">
                        <router-link to="/hit">
                            <p class="text-sm text-gray-700 font-medium">{{ hit.HITId }}</p>
                        </router-link>
                    </td>
                    <td
                        class="table-cell py-2 border-r border-gray-300 whitespace-no-wrap text-blue-900 text-sm leading-5"
                    >
                        <span class="relative inline-block px-3 py-1 italic text-green-900">
                            <span
                                aria-hidden
                                v-if="hit.HITStatus == 'Reviewable'"
                                class="absolute inset-0 bg-green-200 opacity-50 rounded-full"
                            ></span>
                            <span
                                aria-hidden
                                v-if="hit.HITStatus == 'Assignable'"
                                class="absolute inset-0 bg-yellow-400 opacity-50 rounded-full"
                            ></span>
                            <span
                                aria-hidden
                                v-if="hit.HITStatus == 'Disposed'"
                                class="absolute inset-0 bg-red-400 opacity-50 rounded-full"
                            ></span>
                            <span class="relative text-xs">{{ hit.HITStatus }}</span>
                        </span>
                    </td>
                    <td class="hidden sm:table-cell py-2 border-r border-gray-300">
                        <p class="text-sm text-gray-700 font-medium">{{ hit.CreationTime }}</p>
                    </td>
                    <td class="hidden md:table-cell py-2 border-r border-gray-300">
                        <p class="text-sm text-gray-700 font-medium">{{ hit.MaxAssignments }}</p>
                    </td>
                    <td class="hidden md:table-cell py-2 border-r border-gray-300">
                        <p
                            class="text-sm text-gray-700 font-medium"
                        >{{ hit.NumberOfAssignmentsSubmitted }}</p>
                    </td>
                    <td class="hidden lg:table-cell py-2 border-r border-gray-300">
                        <p
                            class="text-sm text-gray-700 font-medium"
                        >{{ hit.NumberOfAssignmentsApproved }}</p>
                    </td>
                    <td class="hidden lg:table-cell py-2">
                        <p
                            class="text-sm text-gray-700 font-medium"
                        >{{ hit.NumberOfAssignmentsRejected }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: 'tableHIT',
    props: {
        datiHIT: Array,
    },
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
        }
    },
    created() {
        this.filtraHIT()
    },
    methods: {
        sortBy(mode) {
            if (this.filtri.stato != mode) {
                this.filtri.stato = mode
                this.filtraHIT()
            }
        },
        // mostra solo le HIT di una categoria (quella passata nell funzione filtraHIT)
        filtraHIT() {
            if (this.filtri.stato == 'all') {
                this.hitDaMostrare = JSON.parse(JSON.stringify(this.datiHIT))
                console.log('BIG')
            } else {
                this.hitDaMostrare = JSON.parse(JSON.stringify(this.datiHIT))
                let i = 0
                while (this.hitDaMostrare[i] != undefined) {
                    if (this.hitDaMostrare[i].HITStatus != this.filtri.stato) {
                        this.hitDaMostrare.splice(i, 1)
                        i--
                    }
                    i++
                }
            }
        },
    },
}
</script>

<style></style>
