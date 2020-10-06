<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto">
                <div class="flex flex-col p-4">
                    <div class="flex w-full mb-2">
                        <div class="text-gray-900 font-bold text-lg">Final settings</div>
                        <svg
                            class="ml-auto fill-current text-gray-700 w-6 h-6 rounded ripple hover:bg-gray-300 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 18 18"
                            @click="toggleModal('close')"
                        >
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                            />
                        </svg>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center content-center w-full px-3 py-2">
                        <label class="block tracking-wide text-gray-900 text-md font-bold mb-2 mr-2" for="hitNum"
                            >How many HITs do you want to launch?</label
                        >
                        <input
                            class="appearance-none block w-full sm:max-w-xs border border-gray-200 rounded py-2 px-4"
                            id="hitNum"
                            type="number"
                            min="1"
                            step="1"
                            placeholder="100"
                            v-model.trim="$v.hitNum.$model"
                            required
                        />
                    </div>

                    <div class="ml-auto flex flex-col xs2:flex-row mt-2">
                        <button
                            class="ripple flex flex-row transition duration-150 ease-in-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
                            @click="submit()"
                        >
                            <svg
                                :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
                                style="width:24px;height:24px"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" /></svg
                            >Proceed
                        </button>
                        <button
                            class="ripple transition duration-150 ease-in-out hover:bg-gray-300 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
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
const { required } = require('vuelidate/lib/validators')
export default {
    name: 'modalLaunch',
    data() {
        return {
            hitNum: 0,
            loading: false
        }
    },
    validations() {
        return {
            hitNum: {
                required,
            },
        }
    },
    methods: {
        toggleModal(mode) {
            if (mode == 'close') {
                this.$emit('launchModal')
            } else {
                this.$emit('launchModal', 'Error')
            }
        },
    },
}
</script>