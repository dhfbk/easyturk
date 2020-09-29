<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-3/5">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <div class="text-gray-900 font-bold text-lg">Set layout for the project</div>
                        <svg
                            class="ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 18 18"
                            @click="toggleModal('close')"
                        >
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                            />
                        </svg>
                    </div>
                    <div class="py-2">*do things*</div>
                    <div class="flex flex-row items-center">
                        <select
                            class="appearance-none h-full rounded border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            name="sortBy"
                            id="sortBy"
                        >
                            <option v-for="item in splitFields" :key="item.value" :value="item.value">
                                {{ item }}
                            </option>
                        </select>
                        <span
                            class="bg-white border-2 mx-4 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
                            @click="selected = !selected"
                        >
                            <input type="checkbox" class="opacity-0 absolute" />
                            <svg
                                :class="selected ? '' : 'hidden'"
                                class="fill-current w-4 h-4 text-secondary pointer-events-none"
                                viewBox="0 0 20 20"
                            >
                                <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                            </svg>
                        </span>
                        <input
                            class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="nome"
                            type="text"
                            placeholder="Text"
                            maxlength="255"
                            v-if="selected"
                        />
                        <select
                            v-else
                            class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="selectorTempoMax"
                        >
                            <option selected value="days">From project</option>
                            <option value="hours">From CSV</option>
                        </select>
                        <span class="flex flex-row mx-2 text-gray-800">
                            <button class="p-2 ripple rounded-full focus:outline-none hover:bg-gray-300">
                                <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="fill-current">
                                    <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                                </svg>
                            </button>
                            <button class="p-2 ripple rounded-full focus:outline-none hover:bg-gray-300">
                                <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="fill-current">
                                    <path
                                        fill="currentColor"
                                        d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                                    />
                                </svg>
                            </button>
                        </span>
                    </div>

                    <div class="ml-auto flex flex-col sm:flex-row">
                        <button
                            class="ripple flex flex-row transition duration-150 ease-in-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
                        >
                            <svg
                                :class="loading ? 'animate-spin mr-1' : 'hidden'"
                                style="width:24px;height:24px"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" /></svg
                            >Proceed
                        </button>
                        <button
                            class="ripple transition duration-150 ease-in-out hover:bg-gray-300 focus:outline-none mt-2 sm:mt-0 sm:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
                            @click="toggleModal('close')"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'modalLayout',
    props: { project: Array },
    data() {
        return {
            loading: false,
            selected: false,
        }
    },
    methods: {
        toggleModal(mode) {
            if (mode == 'close') {
                this.$emit('layoutModal', ['layout', ''])
            }
        },
    },
    created() {
        console.log(this.project[0])
        console.log(this.splitFields)
    },
    computed: {
        splitFields: function() {
            return this.project[0].layout_fields.replace(/\s/g, '').split(',')
        },
    },
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s !important;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
