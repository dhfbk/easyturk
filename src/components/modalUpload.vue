<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 mx-2 overflow-y-auto">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <h2 class="text-gray-900 font-bold text-lg">Choose the file to upload</h2>
                        <span class="ml-auto rounded hover:bg-gray-300 p-1" @click="toggleModal()">
                            <svg
                                class="ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 18 18"
                            >
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                />
                            </svg>
                        </span>
                    </div>
                    <p class="py-2" v-if="type == 'std'">File upload for the base csv document</p>
                    <p class="py-2" v-else>File upload for the golden standard csv document</p>
                    <h2 class="font-light">Titles:</h2>
                    <label class="inline-flex items-center mt-2">
                        <input type="radio" id="auto" :value="1" class="form-radio" checked v-model="picked" />
                        <span class="ml-2 text-gray-700">Auto-select titles</span>
                    </label>
                    <label class="inline-flex items-center mt-2">
                        <input type="radio" id="custom" :value="0" class="form-radio" v-model="picked" />
                        <span class="ml-2 text-gray-700">Use custom titles (separated by comma)</span>
                    </label>

                    <input
                        type="text"
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mt-2 mb-3 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        v-model.trim="$v.customTitles.$model"
                        :class="[
                            picked == 0 ? '' : 'cursor-not-allowed bg-gray-300',
                            $v.customTitles.$error ? 'shadowRed' : '',
                        ]"
                        :disabled="picked == 1"
                        placeholder="Titles"
                    />

                    <div class="flex customFlex justify-between my-1">
                        <div class="flex flex-col xs2:flex-row items-center content-center firstMargin">
                            <label for="separator" class="font-light mr-2">Separator:</label>
                            <div class="relative mt-1 sm:mt-0 sm:ml-2">
                                <select
                                    class="block w-full appearance-none my-2 bg-gray-100 text-gray-700 border border-gray-200 py-2 pl-2 pr-12 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                                    id="separator"
                                    name="separator"
                                    v-model="separated"
                                >
                                    <option value="" disabled selected hidden>Choose separator...</option>
                                    <option value="comma">Comma</option>
                                    <option value="tab">Tab</option>
                                    <option value="semicolon">Semicolon</option>
                                    <!--<option value="space">Space</option>-->
                                </select>
                                <div
                                    class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900"
                                >
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col xs2:flex-row items-center content-center secondMargin">
                            <label for="delimiter" class="font-light mr-2">Delimiter:</label>
                            <div class="relative mt-1 sm:mt-0 sm:ml-2">
                                <select
                                    class="block w-full appearance-none my-2 bg-gray-100 text-gray-700 border border-gray-200 py-2 pl-2 pr-12 rounded transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                                    id="delimiter"
                                    name="delimiter"
                                    v-model="delimiter"
                                >
                                    <option value="" disabled selected hidden>Choose delimiter...</option>
                                    <option value="double">"</option>
                                    <option value="single">'</option>
                                    <!--<option value="none">None</option>-->
                                </select>
                                <div
                                    class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900"
                                >
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
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
                            accept=".csv, text/csv, .txt, .tsv"
                            style="display:none;"
                            :disabled="inputLocked"
                            @change="displayFile"
                        />
                        <transition name="fade" mode="out-in" appear>
                            <div class="w-1/2 mt-2 mx-auto text-left" v-if="file != ''">
                                <div class="flex items-center bg-gray-200 pl-2 pr-4 py-2 rounded-lg my-1">
                                    <svg viewBox="0 0 24 24" class="w-12 h-10">
                                        <path
                                            d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M15.8,20H14L12,16.6L10,20H8.2L11.1,15.5L8.2,11H10L12,14.4L14,11H15.8L12.9,15.5L15.8,20M13,9V3.5L18.5,9H13Z"
                                        />
                                    </svg>
                                    <div class="flex-1 px-3">
                                        <span class="text-gray-800 overflow-hidden">{{ file.name }}</span>
                                    </div>
                                    <button
                                        @click="emptyFile"
                                        class="ripple cursor-pointer w-8 h-8 text-center text-xl transition duration-150 ease-in-out hover:bg-gray-300 focus:bg-gray-400 rounded-full focus:outline-none"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </transition>
                    </div>
                    <div class="w-full mb-2">
                        <progress class="w-full" max="100" :value.prop="uploadPercentage"></progress>
                    </div>
                    <div class="ml-auto flex flex-col xs2:flex-row">
                        <button
                            class="ripple flex flex-row transition duration-150 ease-in-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
                            @click="uploadFile()"
                        >
                            <svg
                                :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
                                style="width:24px;height:24px"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" /></svg
                            >Upload
                        </button>
                        <button
                            class="ripple transition duration-150 ease-in-out hover:bg-gray-300 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
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
const { required } = require('vuelidate/lib/validators')
const notEmpty = value => value != ''

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
            separated: '',
            delimiter: '',
            uploadPercentage: 0,
            loading: false,
            middleCheck: false,
        }
    },
    validations() {
        return {
            customTitles: {
                required,
                notEmpty,
            },
        }
    },
    mounted() {
        window.addEventListener('keyup', this.esc)
    },
    methods: {
        esc(event) {
            if (event.keyCode === 27) {
                this.toggleModal('close')
            }
        },
        toggleModal() {
            if (this.type == 'std') {
                this.$emit('uploadModal', ['std', '', 'noSnack'])
            } else {
                this.$emit('uploadModal', ['gld', '', 'noSnack'])
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
            if ((this.picked == 0 && this.$v.customTitles.$invalid == false) || this.picked == 1) {
                if (this.file == '') {
                    console.log('nessun file da caricare')
                } else {
                    this.loading = true
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
                        .then(res => {
                            if (res.data.result != 'ERR') {
                                if (this.type == 'std') {
                                    this.$emit('uploaded', ['std', '', 'Upload completed'])
                                } else {
                                    this.$emit('uploaded', ['gld', '', 'Upload completed'])
                                }
                            } else {
                                if (this.type == 'std') {
                                    this.$emit('uploaded', ['std', '', 'Error. Try again' + res.data.error])
                                } else {
                                    this.$emit('uploaded', ['gld', '', 'Error. Try again' + res.data.error])
                                }
                            }
                            this.loading = false
                        })
                        .catch(() => {
                            if (this.type == 'std') {
                                this.$emit('uploaded', ['std', '', 'Error. Try again'])
                            } else {
                                this.$emit('uploaded', ['gld', '', 'Error. Try again'])
                            }
                            this.loading = false
                        })
                }
            } else {
                this.$v.$touch()
            }
        },
    },
    beforeDestroy() {
        window.removeEventListener('keyup', this.esc)
    },
}
</script>

<style scoped>
progress[value]::-webkit-progress-bar {
    height: 8px;
    background-color: #eee;
    border-radius: 2px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.25) inset;
    border-radius: 2px;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s !important;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
.customFlex {
    flex-direction: column;
}
@media screen and (min-width: 777px) {
    .customFlex {
        flex-direction: row;
    }
    .firstMargin {
        margin-right: 0.5rem;
    }
    .secondMargin {
        margin-left: 0.5rem;
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
