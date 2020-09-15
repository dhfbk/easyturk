<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-1/2">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <h2 class="text-gray-900 font-bold text-lg">Scegli il file da caricare</h2>
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
                    <h2
                        class="py-2"
                        v-if="type == 'std'"
                    >Upload del file csv contenente i dati standard da utilizzare</h2>
                    <h2
                        class="py-2"
                        v-else
                    >Upload del file csv contenente i dati per il golden standard</h2>
                    <div class="py-4 text-center">
                        <label
                            for="file-upload"
                            :class="inputLocked ? 'bg-gray-600 cursor-not-allowed' : 'bg-secondary cursor-pointer hover:bg-blue-700'"
                            class="inline-block text-white rounded-md px-4 py-2"
                        >Scegli file</label>
                        <input
                            id="file-upload"
                            name="upload_csv"
                            type="file"
                            accept=".csv, text/csv"
                            style="display:none;"
                            :disabled="inputLocked"
                            @change="displayFile"
                        />
                        <transition name="fade" mode="out-in" appear>
                            <div class="w-1/2 mt-2 mx-auto text-left" v-if="files.length > 0">
                                <div
                                    class="flex items-center bg-gray-200 pl-2 pr-4 py-2 rounded-lg my-1"
                                >
                                    <svg viewBox="0 0 24 24" class="w-12 h-10">
                                        <path
                                            d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M15.8,20H14L12,16.6L10,20H8.2L11.1,15.5L8.2,11H10L12,14.4L14,11H15.8L12.9,15.5L15.8,20M13,9V3.5L18.5,9H13Z"
                                        />
                                    </svg>
                                    <div class="flex-1 px-3">
                                        <span
                                            class="text-gray-800 overflow-hidden"
                                        >{{files[0].name}}</span>
                                    </div>
                                    <button
                                        @click="emptyFile"
                                        class="cursor-pointer w-8 h-8 text-center text-xl transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-400 rounded-full focus:outline-none"
                                    >&times;</button>
                                </div>
                            </div>
                        </transition>
                    </div>
                    <div class="ml-auto">
                        <button
                            class="transition duration-150 ease-in-out bg-primary hover:bg-orange-600 text-black hover:text-white font-bold py-2 px-4 rounded focus:outline-none"
                            @click="toggleModal()"
                        >Carica</button>
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
export default {
    name: 'modalUpload',
    props: {
        type: String,
    },
    data() {
        return {
            files: [],
            inputLocked: false,
        }
    },
    methods: {
        toggleModal() {
            if (this.type == 'std') {
                this.$emit('upload', 'std')
            } else {
                this.$emit('upload', 'gld')
            }
        },
        displayFile() {
            var file = document.getElementById('file-upload').files[0]
            this.files.push(file)
            this.inputLocked = true
        },
        emptyFile() {
            document.getElementById('file-upload').value = ''
            this.files.pop()
            this.inputLocked = false
            console.log(document.getElementById('file-upload').files[0])
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
</style>