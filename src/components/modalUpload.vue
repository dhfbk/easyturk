<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-auto mx-2 lg:w-1/2">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <h2 class="text-gray-900 font-bold text-lg">Choose the file to upload</h2>
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
                    <p class="py-2" v-if="type == 'std'">File upload for the base csv document</p>
                    <p class="py-2" v-else>File upload for the golden standard csv document</p>
                    <h2 class="font-light">Titles:</h2>
                    <label class="inline-flex items-center mt-2">
                        <input
                            type="radio"
                            id="auto"
                            :value="1"
                            class="form-radio"
                            checked
                            v-model="picked"
                        />
                        <span class="ml-2 text-gray-700">Auto-select titles</span>
                    </label>
                    <label class="inline-flex items-center mt-2">
                        <input
                            type="radio"
                            id="custom"
                            :value="0"
                            class="form-radio"
                            v-model="picked"
                        />
                        <span class="ml-2 text-gray-700"
                            >Use custom titles (separated by comma)</span
                        >
                    </label>

                    <input
                        type="text"
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mt-2 mb-3 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        v-model="customTitles"
                        :class="picked == 0 ? '' : 'cursor-not-allowed bg-gray-300'"
                        :disabled="picked == 1"
                        placeholder="Titles"
                    />

                    <div class="flex customFlex my-1">
                        <label class="inline-flex items-center customWidth">
                            <span class="font-light mr-2">Separator:</span>

                            <select
                                class="block appearance-none my-2 bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                                id="separator"
                                v-model="separated"
                            >
                                <option selected value="comma">Comma</option>
                                <option value="tab">Tab</option>
                                <option value="semicolon">Semicolon</option>
                                <!--<option value="space">Space</option>-->
                            </select>
                        </label>
                        <label class="inline-flex items-center customWidth">
                            <span class="font-light mr-2">String delimiter:</span>

                            <select
                                class="block appearance-none my-2 bg-gray-100 text-gray-700 border border-gray-200 py-2 px-4 pr-8 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                                id="separator"
                                v-model="delimiter"
                            >
                                <option selected value="double">"</option>
                                <option value="single">'</option>
                                <!--<option value="none">None</option>-->
                            </select>
                        </label>
                    </div>
                    <hr />

                    <div class="py-6 text-center">
                        <label
                            for="file"
                            :class="
                                inputLocked
                                    ? 'bg-gray-600 cursor-not-allowed'
                                    : 'bg-secondary cursor-pointer hover:bg-blue-700'
                            "
                            class="inline-block text-white rounded-md px-4 py-2"
                            >Choose file</label
                        >
                        <input
                            id="file"
                            name="upload_csv"
                            type="file"
                            ref="file"
                            accept=".csv, text/csv"
                            style="display:none;"
                            :disabled="inputLocked"
                            @change="displayFile"
                        />
                        <transition name="fade" mode="out-in" appear>
                            <div class="w-1/2 mt-2 mx-auto text-left" v-if="file != ''">
                                <div
                                    class="flex items-center bg-gray-200 pl-2 pr-4 py-2 rounded-lg my-1"
                                >
                                    <svg viewBox="0 0 24 24" class="w-12 h-10">
                                        <path
                                            d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M15.8,20H14L12,16.6L10,20H8.2L11.1,15.5L8.2,11H10L12,14.4L14,11H15.8L12.9,15.5L15.8,20M13,9V3.5L18.5,9H13Z"
                                        />
                                    </svg>
                                    <div class="flex-1 px-3">
                                        <span class="text-gray-800 overflow-hidden">
                                            {{ file.name }}
                                        </span>
                                    </div>
                                    <button
                                        @click="emptyFile"
                                        class="cursor-pointer w-8 h-8 text-center text-xl transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-400 rounded-full focus:outline-none"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </transition>
                    </div>
                    <div class="w-full mb-2">
                        <progress
                            class="w-full"
                            max="100"
                            :value.prop="uploadPercentage"
                        ></progress>
                    </div>
                    <div class="ml-auto">
                        <button
                            class="transition duration-150 ease-in-out bg-primary hover:bg-primaryDark text-gray-100 py-2 px-4 rounded focus:outline-none"
                            @click="uploadFile()"
                        >
                            Upload
                        </button>
                        <button
                            class="transition duration-150 ease-in-out border border-solid border-gray-400 hover:bg-gray-200 focus:outline-none ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
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
export default {
    name: 'modalUpload',
    props: {
        type: String,
        id: String,
    },
    data() {
        return {
            file: '',
            inputLocked: false,
            picked: 1,
            customTitles: '',
            separated: 'comma',
            delimiter: 'double',
            uploadPercentage: 0,
        }
    },
    methods: {
        toggleModal() {
            console.log(this.type)
            if (this.type == 'std') {
                this.$emit('uploadModal', ['std', ''])
            } else {
                this.$emit('uploadModal', ['gld', ''])
            }
        },
        displayFile() {
            this.file = this.$refs.file.files[0]
            this.inputLocked = true
        },
        emptyFile() {
            document.getElementById('file').value = ''
            this.file = ''
            this.inputLocked = false
            console.log(document.getElementById('file').files[0])
        },
        uploadFile() {
            if (this.file == '') {
                console.log('nessun file da caricare')
            } else {
                let formData = new FormData()
                formData.append('csvFile', this.file)
                formData.append('id', this.id)
                formData.append('enclosure', this.delimiter)
                formData.append('char', this.separated)
                formData.append('fieldsTitlesInFirstLine', this.picked)
                if (this.type == 'gld') {
                    formData.append('isGold', 1)
                } else {
                    formData.append('isGold', 0)
                }
                formData.append('fieldTitles', this.customTitles)
                axios
                    .post(this.APIURL + '?action=uploadFile', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                        onUploadProgress: function(progressEvent) {
                            this.uploadPercentage = parseInt(
                                Math.round((progressEvent.loaded / progressEvent.total) * 100)
                            )
                        }.bind(this),
                    })
                    .then(() => {
                        this.$emit('uploaded', 'Upload completed')
                        this.toggleModal()
                    })
                    .catch(() => {
                        this.$emit('uploaded', 'Error. Try again')
                        this.toggleModal()
                    })
            }
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
.customFlex {
    flex-direction: column;
}
.customWidth {
    width: fit-content;
}
@media screen and (min-width: 512px) {
    .customFlex {
        flex-direction: row;
    }

    .customWidth {
        width: 50%;
    }
}
.form-radio {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    -webkit-print-color-adjust: exact;
    color-adjust: exact;
    display: inline-block;
    vertical-align: middle;
    background-origin: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    flex-shrink: 0;
    border-radius: 100%;
    height: 1em;
    width: 1em;
    color: #0068b4;
    background-color: #fff;
    border-color: #003e84;
    border-width: 1px;
}

.form-radio:checked {
    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
    border-color: transparent;
    background-color: currentColor;
    background-size: 100% 100%;
    background-position: center;
    background-repeat: no-repeat;
}

@media not print {
    .form-radio::-ms-check {
        border-width: 1px;
        color: transparent;
        background: inherit;
        border-color: inherit;
        border-radius: inherit;
    }
}

.form-radio:focus {
    outline: none;
}
</style>
