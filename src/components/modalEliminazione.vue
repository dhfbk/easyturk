<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-1/2">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <div class="text-gray-900 font-bold text-lg">Conferma azione</div>
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
                        class="py-2"
                    >Sicuro di voler eliminare il progetto? Questa azione è irreversibile.</div>

                    <div class="ml-auto">
                        <button
                            class="transition duration-150 ease-in-out bg-primary hover:bg-orange-600 text-black hover:text-white font-bold py-2 px-4 rounded focus:outline-none"
                            @click="toggleModal()"
                        >Elimina</button>
                        <button
                            class="transition duration-150 ease-in-out border border-solid border-gray-400 hover:bg-gray-400 focus:outline-none ml-2 bg-transparent text-black font-semibold py-2 px-4 rounded"
                            @click="toggleModal()"
                        >Annulla</button>
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
    created() {
        console.log(this.id)
    },
    methods: {
        toggleModal() {
            this.$emit('toggleModal', 'elim')
        },
        delete() {
            //var self = this
            axios({
                url: 'https://web.apnetwork.it/mturk/?action=deleteProject',
                method: 'post',
                data: {
                    id: this.id,
                },
            }).then(res => {
                console.log(res.data.result)
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
