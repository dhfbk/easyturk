<template>
    <transition name="fade" mode="out-in" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 customZ"
            @click="toggleModal"
        >
            <div class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto" @click.stop>
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <div class="text-gray-900 font-bold text-lg text-primary">Confirm action</div>
                        <span class="ml-auto rounded hover:bg-gray-400 p-1" @click="toggleModal()">
                            <svg
                                class="m-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 18 18"
                            >
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                />
                            </svg>
                            <span class="sr-only">Close</span>
                        </span>
                    </div>
                    <div class="py-2">Are you sure you want to delete the project? This action is irreversible.</div>

                    <div class="ml-auto flex flex-col xs2:flex-row">
                        <button
                            class="ripple flex flex-row transition duration-100 ease-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
                            @click="deleteProject()"
                        >
                            <svg
                                :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
                                style="width:24px;height:24px"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                            </svg>
                            Delete
                        </button>
                        <button
                            class="ripple transition duration-100 ease-out hover:bg-gray-400 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
                            @click="toggleModal()"
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
    name: 'modalEliminazione',
    props: { id: String },
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
                this.deleteProject()
            }
        },
        toggleModal() {
            this.$emit('deleteModal', ['delete', ''])
        },
        deleteProject() {
            //var self = this
            this.loading = true
            axios({
                url: this.APIURL + '?action=deleteProject&id=' + this.id,
                method: 'get',
            })
                .then(res => {
                    console.log(res.data.result)
                    this.loading = false
                    if (res.data.result == 'ERR') {
                        this.$emit('deleted', 'Error: ' + res.data.error)
                    } else {
                        this.$emit('deleted', 'Project deleted')
                        this.toggleModal()
                    }
                    // this.$emit('snackbar', ['success', this.id, 'Progetto eliminato'])
                })
                .catch(err => {
                    console.log(err)
                    this.$emit('deleted', 'Error: server unreachable')
                    this.loading = false
                })
            /*
            $.ajax({
                url: 'https://web.apnetwork.it/mturk/?action=deleteProject',
                dataType: 'json',
                data: {
                    id: self.id,
                },
                method: 'post',
                success: function(data) {
                    console.log(data)
                },
            })
            */
        },
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.keyboardEvent)
    },
}
</script>

<style scoped>
.fade-enter-active {
    transition: opacity 0.25s ease-out !important;
}
.fade-leave-active {
    transition: opacity 0.2s ease-out !important;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
.customZ {
    z-index: 990;
}
</style>
