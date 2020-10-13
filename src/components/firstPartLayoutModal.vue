<template>
    <div>
        <div
            class="flex flex-col md:flex-row items-center md:items-end content-center mb-1"
            v-for="first in dataArr"
            :key="first.id"
            @change="update()"
            :class="first.id != 0 ? 'mt-3' : ''"
        >
            <div
                class="flex flex-col xs2:grid xs2:grid-cols-8 xs2:gap-10 md:gap-0 md:flex md:flex-col w-full content-center items-center mt-2 md:mt-0"
            >
                <label for="sortBy" class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0">Field:</label>
                <input
                    class="xs2:col-span-7 md:col-auto appearance-none py-2 block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded px-4 transition duration-150 focus:outline-none"
                    name="sortBy"
                    type="text"
                    placeholder="Text"
                    maxlength="255"
                    disabled
                    v-model="first.field"
                />
            </div>
            <span class="tooltip relative">
                <span
                    class="bg-white border-2 mx-4 mt-2 md:mt-0 md:mb-2 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
                    @click="first.isHandWritten = !first.isHandWritten"
                >
                    <input type="checkbox" class="opacity-0 absolute" v-model="first.isHandWritten" />
                    <svg
                        :class="first.isHandWritten ? '' : 'hidden'"
                        class="fill-current w-4 h-4 text-secondary pointer-events-none"
                        viewBox="0 0 20 20"
                    >
                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                    </svg>
                </span>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light ml-1"
                    >Handwritten value</span
                >
            </span>

            <div
                class="flex flex-col xs2:grid xs2:grid-cols-8 xs2:gap-10 md:gap-0 md:flex md:flex-col mt-2 md:mt-0 w-full content-center items-center"
                v-if="first.isHandWritten"
            >
                <label for="value" class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0">Value:</label>
                <input
                    class="xs2:col-span-7 md:col-auto appearance-none py-2 block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded px-4 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="value"
                    name="value"
                    type="text"
                    placeholder="Text"
                    maxlength="255"
                    v-model="first.customValue"
                />
            </div>
            <div
                class="flex flex-col xs2:grid xs2:grid-cols-8 xs2:gap-10 md:gap-0 md:flex md:flex-col mt-2 md:mt-0 w-full content-center items-center"
                v-else
            >
                <label for="selectFrom" class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0">Value:</label>
                <div class="xs2:col-span-7 md:col-auto relative w-full block md:mt-0">
                    <select
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 pr-4 pl-2 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="selectFrom"
                        name="selectFrom"
                        v-model="first.valueFrom"
                    >
                        <option value="" disabled selected hidden>Choose value...</option>
                        <optgroup label="From project" v-if="!first.isFromCsv">
                            <option value="_name">Name</option>
                            <option value="_title">Title</option>
                            <option value="_description">Description</option>
                            <option value="_keywords">Keywords</option>
                        </optgroup>
                        <optgroup
                            label="From CSV"
                            v-if="
                                (first.isFromCsv && loadingCsv) ||
                                    (!loadingCsv && csvValues.length > 0 && first.isFromCsv)
                            "
                        >
                            <option value="loading" v-if="loadingCsv">Loading...</option>
                            <option v-for="field in csvValues" :key="field" :value="field">{{ field }}</option>
                        </optgroup>
                    </select>
                    <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'firstPart',
    props: {
        firstPartData: Array,
        csvValues: Array,
        loadingCsv: Boolean,
    },
    data() {
        return {
            dataArr: [],
        }
    },
    created() {
        this.dataArr = this.firstPartData
    },
    methods: {
        update() {
            var send = ['first', this.dataArr]
            this.$emit('updateArr', send)
        },
    },
    watch: {
        firstPartData: function() {
            this.dataArr = this.firstPartData
        },
    },
}
</script>

<style scoped>
.tooltip .tooltip-text {
    visibility: hidden;
    text-align: center;
    padding: 2px 6px;
    z-index: 100;
    left: 0;
    transition: opacity 0.3s ease-in-out;
    opacity: 0;
    transition-delay: 0.15s;
}
.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 85%;
}
</style>
