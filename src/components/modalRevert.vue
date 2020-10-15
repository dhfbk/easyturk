<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
            @click="toggleModal('close')"
        >
            <div class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto" @click.stop>
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <div class="text-gray-900 font-bold text-lg text-primary">Confirm action</div>
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
                        Are you sure you want to revert your changes to the previous state?
                    </div>

                    <div class="ml-auto flex flex-col xs2:flex-row">
                        <button
                            class="ripple flex flex-row transition duration-150 ease-in-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
                            @click="revertProject()"
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
import axios from 'axios'

export default {
    name: 'modalRevert',
    props: { id: String, toStatus: Number },
    data() {
        return {
            loading: false,
        }
    },
    mounted() {
        window.addEventListener('keydown', this.keyboardEvent)
    },
    methods: {
        keyboardEvent(event) {
            if (event.code == 'Escape') {
                this.toggleModal('close')
            } else if (event.code == 'Enter') {
                this.revertProject()
            }
        },
        toggleModal(mode) {
            if (mode == 'close') {
                this.$emit('revertModal')
            } else {
                this.$emit('revertModal', 'Error. Try again')
            }
        },
        revertProject() {
            this.loading = true
            axios({
                method: 'post',
                url: this.APIURL + '?action=updateProjectStatus&id=' + this.id,
                data: {
                    toStatus: this.toStatus,
                },
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            })
                .then(res => {
                    this.loading = false
                    if (res.data.result == 'ERR') {
                        this.$emit('reverted', 'Error: ' + res.data.error)
                    } else {
                        this.$emit('reverted', 'Project succesfully reverted.')
                        this.toggleModal('close')
                    }
                    console.log(res.data.result)
                })
                .catch(() => {
                    this.$emit('error', 'Error: server unreacheable')
                    this.loading = false
                })
        },
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.keyboardEvent)
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
