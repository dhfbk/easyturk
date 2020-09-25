<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-1/2">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <div class="text-gray-900 font-bold text-lg">Confirm action</div>
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
                    <div class="py-2">
                        Are you sure you want to revert your changes? You will have to reupload your files and set your
                        golden standard.
                    </div>

                    <div class="ml-auto flex flex-col sm:flex-row">
                        <button
                            class="ripple flex flex-row transition duration-150 ease-in-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
                            @click="revertProject()"
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
import axios from 'axios'
//let $ = require('jquery')

export default {
    name: 'modalRevert',
    props: { id: String },
    data() {
        return {
            loading: false,
        }
    },
    created() {
        console.log(this.id)
    },
    methods: {
        toggleModal(mode) {
            //cambiare per inserimento id nella posizione 1 dell'array
            if (mode == 'close') {
                this.$emit('uploadModal', ['revert', ''])
            } else if (mode == 'reverted') {
                this.$emit('uploadModal', ['revert', 'Reverted', true])
            } else {
                this.$emit('uploadModal', ['revert', 'Error', false])
            }
        },
        revertProject() {
            this.loading = true
            axios({
                method: 'post',
                url: this.APIURL + '?action=updateProjectStatus&id=' + this.id,
                data: {
                    toStatus: 0,
                },
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            })
                .then(() => {
                    this.loading = false
                    this.toggleModal('reverted')
                })
                .catch(() => {
                    this.toggleModal('error')
                })
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
