<template>
    <div>
        <div
            class="flex flex-col md:flex-row items-center md:items-end content-center py-2"
            v-for="first in firstPartData"
            :key="first.id"
        >
            <div class="flex flex-col xs2:flex-row md:flex-col w-full content-center items-center">
                <label
                    for="sortBy"
                    class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0"
                >Field:</label>
                <div class="relative w-full md:block">
                    <select
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 pr-4 pl-2 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        name="sortBy"
                        id="sortBy"
                        v-model="first.field"
                    >
                        <option v-for="item in splitFields" :key="item" :value="item">{{ item }}</option>
                    </select>
                    <div
                        class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900"
                    >
                        <svg
                            class="h-4 w-4"
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
                >Handwritten value</span>
            </span>

            <div
                class="flex flex-col xs2:flex-row md:flex-col w-full content-center items-center"
                v-if="first.isHandWritten"
            >
                <label
                    for="value"
                    class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0"
                >Value:</label>
                <input
                    class="appearance-none overflow-scroll block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mt-2 md:mt-0 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="value"
                    name="value"
                    type="text"
                    placeholder="Text"
                    maxlength="255"
                    v-model="first.customValue"
                />
            </div>
            <div
                class="flex flex-col xs2:flex-row md:flex-col w-full content-center items-center"
                v-else
            >
                <label
                    for="selectFrom"
                    class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0"
                >Value:</label>
                <div class="relative w-full block mt-2 md:mt-0">
                    <select
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 pr-4 pl-2 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="selectFrom"
                        name="selectFrom"
                        v-model="first.valueFrom"
                    >
                        <option selected value="prj">From project</option>
                        <option value="csv">From CSV</option>
                    </select>
                    <div
                        class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900"
                    >
                        <svg
                            class="h-4 w-4"
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
            <span class="flex flex-row mx-2 text-gray-800 mt-2 md:mt-0">
                <button
                    class="p-2 ripple rounded-full focus:outline-none hover:bg-gray-300"
                    @click="newElement('first')"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="fill-current">
                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                </button>
                <button
                    class="p-2 ripple rounded-full focus:outline-none hover:bg-gray-300"
                    @click="removeElement('first', first.id)"
                    :class="first.id != 0 ? '' : 'hidden md:block md:invisible'"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="fill-current">
                        <path
                            fill="currentColor"
                            d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                        />
                    </svg>
                </button>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'firstPart',
    props: {
        firstPartData: Array,
        splitFields: Array,
    },
    methods: {
        newElement(arr) {
            this.$emit('newElement', arr)
        },
        removeElement(arr, id) {
            this.$emit('removeElement', [arr, id])
        },
    },
}
</script>

<style scoped>
.pin-r {
    right: 0;
}
.pin-y {
    top: 0;
    bottom: 0;
}
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