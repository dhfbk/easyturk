<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 mx-2 overflow-y-auto">
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <h2 class="text-gray-900 font-bold text-lg">HIT settings</h2>
                        <svg
                            class="ml-auto fill-current text-gray-700 hover:bg-gray-300 rounded w-6 h-6 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 18 18"
                            @click="toggleModal('close')"
                        >
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                            />
                        </svg>
                    </div>
                    <div v-if="baseDataStatus == 0">
                        <p class="text-red-500 text-md py-2">Base CSV hasn't been uploaded</p>
                    </div>
                    <div v-if="goldDataStatus == 0">
                        <p class="text-teal-500 text-md py-2">Golden standard CSV hasn't been uploaded</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center content-center w-full py-2">
                        <h2 class="block tracking-wide text-gray-900 text-md font-bold mr-2">Shuffle base CSV data:</h2>
                        <label class="inline-flex content-center items-center mr-0 sm:mr-2">
                            <input
                                type="radio"
                                id="auto"
                                :value="1"
                                class="form-radio"
                                checked
                                :disabled="baseDataStatus == 0"
                                :class="{ 'cursor-not-allowed': baseDataStatus == 0 }"
                                v-model="shuffleBase"
                            />
                            <span class="ml-2 text-gray-700">Yes</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input
                                type="radio"
                                id="custom"
                                :value="0"
                                class="form-radio"
                                :disabled="baseDataStatus == 0"
                                :class="{ 'cursor-not-allowed': baseDataStatus == 0 }"
                                v-model="shuffleBase"
                            />
                            <span class="ml-2 text-gray-700">No</span>
                        </label>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center content-center w-full py-2">
                        <label class="block tracking-wide text-gray-900 text-md font-bold mb-2 mr-2" for="gold"
                            >Golden data per HIT:</label
                        >
                        <input
                            :class="[
                                goldDataStatus == 0 || baseDataStatus == 0
                                    ? 'cursor-not-allowed bg-gray-400 text-gray-800 '
                                    : ' bg-gray-100 text-gray-700 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500',
                                $v.goldNum.$error ? 'shadowRed' : '',
                            ]"
                            class="appearance-none block w-full sm:max-w-xs border border-gray-200 rounded py-2 px-4"
                            id="gold"
                            type="number"
                            min="1"
                            step="1"
                            placeholder="1"
                            :disabled="goldDataStatus == 0 || baseDataStatus == 0"
                            v-model.trim="$v.goldNum.$model"
                            required
                        />
                    </div>
                    <div class="flex flex-col sm:flex-row items-center content-center w-full py-2">
                        <h2 class="block tracking-wide text-gray-900 text-md font-bold mr-2">Shuffle gold CSV data:</h2>
                        <label class="inline-flex content-center items-center mr-0 sm:mr-2">
                            <input
                                type="radio"
                                id="auto"
                                :value="1"
                                class="form-radio"
                                checked
                                :disabled="goldDataStatus == 0 || baseDataStatus == 0"
                                :class="{ 'cursor-not-allowed': goldDataStatus == 0 || baseDataStatus == 0 }"
                                v-model="shuffleGold"
                            />
                            <span class="ml-2 text-gray-700">Yes</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input
                                type="radio"
                                id="custom"
                                :value="0"
                                class="form-radio"
                                :disabled="goldDataStatus == 0 || baseDataStatus == 0"
                                :class="{ 'cursor-not-allowed': goldDataStatus == 0 || baseDataStatus == 0 }"
                                v-model="shuffleGold"
                            />
                            <span class="ml-2 text-gray-700">No</span>
                        </label>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center content-center w-full py-2 mb-2">
                        <label class="block tracking-wide text-gray-900 text-md font-bold mb-2 mr-2" for="action"
                            >Action for the leftover records:</label
                        >
                        <div class="relative mt-1 sm:mt-0 sm:ml-2">
                            <select
                                :class="[
                                    goldDataStatus == 0 || baseDataStatus == 0
                                        ? 'cursor-not-allowed bg-gray-400 text-gray-800'
                                        : 'bg-gray-100 text-gray-700 transition duration-150 ease-in-out focus:outline-none focus:border-gray-500 hover:border-gray-500',
                                    $v.leftover.$error ? 'shadowRed' : '',
                                ]"
                                class="block border border-gray-200 appearance-none w-full py-2 pl-2 pr-8 rounded"
                                id="action"
                                name="action"
                                v-model.trim="$v.leftover.$model"
                                required
                                :disabled="goldDataStatus == 0 || baseDataStatus == 0"
                            >
                                <option value="" disabled selected hidden>Choose action...</option>
                                <option value="no_use">Don't use</option>
                                <option value="reuse">Fill last HIT with previous training</option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p>With these settings you'll have {{ hitInfo }} HITs</p>
                    <div class="pt-2 pb-1">
                        <p class="font-light italic text-center">
                            You'll be able to revert these changes and re-upload your files
                        </p>
                    </div>
                    <div class="ml-auto flex flex-col xs2:flex-row justify-end flex-wrap">
                        <button
                            class="ripple flex flex-row transition duration-150 ease-in-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
                            @click="confirm()"
                            :disabled="baseDataStatus == 0"
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
                            class="ripple transition duration-150 ease-in-out mt-2 xs2:mt-0 xs2:ml-2 hover:bg-gray-300 focus:outline-none bg-transparent text-gray-800 py-2 px-4 rounded"
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
const { required } = require('vuelidate/lib/validators')
const notEmpty = value => value != ''

export default {
    name: 'modalHIT',
    props: {
        id: String,
        baseDataStatus: Number,
        goldDataStatus: Number,
        params: Number,
    },
    data() {
        return {
            goldNum: this.$store.state.defaults.gold_data_per_hit,
            leftover: '',
            shuffleBase: 1,
            shuffleGold: 1,
            loading: false,
            hitInfo: 0,
        }
    },
    validations() {
        return {
            goldNum: {
                required,
            },
            leftover: {
                notEmpty,
            },
        }
    },
    created() {
        this.hitInfo = Math.floor(this.baseDataStatus / this.params - this.goldDataStatus)
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
        toggleModal(mode) {
            //cambiare per inserimento id nella posizione 1 dell'array
            if (mode == 'close') {
                this.$emit('hitModal', [])
            } else {
                this.$emit('hitCreated', 'Error: API call failed')
            }
        },
        confirm() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                this.loading = true
                var url = this.APIURL + '?action=updateProjectStatus&id=' + this.id
                var deleteExceedingValues = 0
                if (this.leftover == 'no_use') {
                    deleteExceedingValues = 1
                }
                axios({
                    method: 'post',
                    url: url,
                    data: {
                        toStatus: 1,
                        goldSize: this.goldNum,
                        deleteExceedingValues: deleteExceedingValues,
                        shuffleData: this.shuffleBase,
                        shuffleGold: this.shuffleGold,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                })
                    .then(response => {
                        console.log(response.data)
                        if (response.data.result == 'ERR') {
                            this.$emit('hitCreated', 'Error: ' + response.data.error)
                        } else {
                            this.$emit('hitCreated', 'HIT setup completed')
                            this.toggleModal('close')
                        }
                        this.loading = false
                    })
                    .catch(() => {
                        this.toggleModal('error')
                        this.loading = false
                    })
            }
        },
    },
    watch: {
        leftover() {
            if (this.leftover == 'no_use') {
                this.hitInfo = Math.floor(this.baseDataStatus / this.params - this.goldDataStatus)
            } else {
                this.hitInfo = Math.round(this.baseDataStatus / this.params - this.goldDataStatus)
            }
        },
    },
    beforeDestroy() {
        window.removeEventListener('keyup', this.esc)
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
.form-radio:disabled {
    border-color: transparent;
    background-color: grey;
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
