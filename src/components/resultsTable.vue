<template>
    <table class="w-full shadow-lg rounded">
        <thead>
            <tr class="text-center bg-primary border-b border-grey uppercase">
                <th class="px-2 py-2" @click="$emit('selectAll')">
                    <span
                        class="bg-white border-2 mx-auto rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
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
                <th class="text-sm text-white">HIT ID</th>
                <th class="text-sm text-white">Worker ID</th>
                <th class="hidden sm:table-cell text-sm text-white">
                    Lifetime Approval Rate
                </th>
                <th class="hidden sm:table-cell text-sm text-white">Date</th>
                <th class="hidden sm:table-cell text-sm text-white" @click="$emit('sort')">
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
        <tbody class="text-center">
            <tr v-for="i in current" :key="i.HITId" class="border-b hover:bg-gray-100">
                <td
                    class="py-2 border-r"
                    @click="i.selected = !i.selected"
                    v-if="i.status == 'Submitted'"
                >
                    <span
                        class="bg-white border-2 mx-auto rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
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
                <td class="py-2 border-r" v-else></td>
                <td class="border-r py-2 text-sm text-gray-700 font-medium">
                    {{ i.HITId }}
                </td>
                <td class="border-r py-2 text-sm text-gray-700 font-medium">
                    {{ i.workerId }}
                </td>
                <td class="border-r py-2 text-sm text-gray-700 font-medium">
                    {{ i.approvalRate }}
                </td>
                <td class="border-r py-2 text-sm text-gray-700 font-medium">
                    {{ i.date }}
                </td>
                <td class="py-2 text-sm text-gray-700 font-medium">{{ i.status }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        current: Array,
    },
    data() {
        return {
            all: false,
        }
    },
}
</script>

<style></style>
