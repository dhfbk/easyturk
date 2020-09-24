<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-auto mx-2">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <h2 class="text-gray-900 font-bold text-lg">HIT settings</h2>
                        <svg
                            class="ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 18 18"
                            @click="toggleModal()"
                        >
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                            />
                        </svg>
                    </div>
                    <div
                        class="flex flex-col sm:flex-row items-center content-center w-full px-3 py-2"
                    >
                        <label
                            class="block tracking-wide text-gray-900 text-md font-bold mb-2 mr-2"
                            for="gold"
                        >Golden data per HIT</label>
                        <input
                            class="appearance-none block w-full sm:max-w-xs bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="gold"
                            type="number"
                            min="1"
                            step="1"
                            placeholder="1"
                            v-model.trim="$v.goldNum.$model"
                            required
                        />
                    </div>
                    <div
                        class="flex flex-col sm:flex-row items-center content-center w-full px-3 py-2 mb-2"
                    >
                        <label
                            class="block tracking-wide text-gray-900 text-md font-bold mb-2 mr-2"
                            for="gold"
                        >Action for the leftover HITs</label>
                        <div class="relative mt-1 sm:mt-0 sm:ml-2">
                            <select
                                class="block appearance-none w-full bg-gray-100 text-gray-700 border border-gray-200 py-2 pl-2 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                                id="gold"
                                v-model="leftover"
                            >
                                <option selected value="no_use">Don't use</option>
                                <option value="reuse">Reuse previous gold</option>
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
                    <div class="ml-auto flex flex-row justify-end flex-wrap">
                        <button
                            class="transition duration-150 mt-1 ease-in-out bg-primary hover:bg-primaryDark text-gray-100 py-2 px-4 rounded focus:outline-none"
                        >Launch project</button>
                        <button
                            class="transition duration-150 ease-in-out border mt-1 border-solid border-gray-400 hover:bg-gray-200 focus:outline-none ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
                            @click="toggleModal()"
                        >Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
const { required } = require('vuelidate/lib/validators')
export default {
    name: 'modalHIT',
    data() {
        return {
            goldNum: 1,
            leftover: 'no_use',
        }
    },
    validations() {
        return {
            goldNum: {
                required,
            },
        }
    },
    methods: {
        toggleModal() {
            this.$emit('uploadModal', ['hit', ''])
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
path {
    fill: rgba(45, 55, 72, 1);
}
.pin-r {
    right: 0;
}
.pin-y {
    top: 0;
    bottom: 0;
}
</style>