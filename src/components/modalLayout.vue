<template>
    <transition name="fade" mode="out-in" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 customZ"
            @click="toggleModal"
        >
            <div class="bg-white rounded-lg w-5/6 max-w-3xl max-h-80 overflow-y-auto" @click.stop>
                <div class="flex flex-col p-4">
                    <div class="flex w-full mb-2">
                        <div class="text-gray-900 font-bold text-lg text-primary">Set layout for the project</div>
                        <svg
                            class="ml-auto fill-current text-gray-700 w-6 h-6 rounded ripple hover:bg-gray-300 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 18 18"
                            @click="toggleModal()"
                        >
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                            />
                        </svg>
                    </div>
                    <div class="hidden md:grid grid-cols-4">
                        <p class="font-semibold text-sm w-auto">Field:</p>
                        <p></p>
                        <p class="font-semibold text-sm w-auto ml-6">Value:</p>
                        <p></p>
                    </div>
                    <firstPart
                        :firstPartData="firstPartData"
                        :csvValues="csvValues"
                        :loadingCsv="loadingCsv"
                        @updateArr="updateArr"
                    />
                    <hr class="my-2 md:mt-2" />
                    <p class="font-semibold text-sm w-auto">How to convert answers:</p>
                    <secondPart
                        :secondPartData="secondPartData"
                        @newElement="newElement"
                        @removeElement="removeElement"
                        @updateArr="updateArr"
                    />
                    <hr class="my-2 md:mt-2" />
                    <thirdPart
                        :thirdPartData="thirdPartData"
                        @updateArr="updateArr"
                        :isGold="parseInt(project.count_gold)"
                        :v="$v"
                    />

                    <div class="ml-auto flex flex-col xs2:flex-row mt-2">
                        <button
                            class="ripple flex flex-row transition duration-100 ease-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
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
                            class="ripple transition duration-100 ease-out hover:bg-gray-300 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
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
import firstPart from '../components/firstPartLayoutModal.vue'
import secondPart from '../components/secondPartLayoutModal.vue'
import thirdPart from '../components/thirdPartLayoutModal.vue'
import axios from 'axios'
const { required, between } = require('vuelidate/lib/validators')
const notEmpty = value => value != ''

export default {
    name: 'modalLayout',
    props: { project: Object },
    components: {
        firstPart,
        secondPart,
        thirdPart,
    },
    data() {
        return {
            loading: false,
            loadingCsv: true,
            csvValues: [],
            firstPartData: [],
            secondPartData: [
                {
                    id: 0,
                    varName: '',
                    varValue: '',
                    varNameTo: '',
                    varValueTo: '',
                },
            ],
            assignNumber: this.project.workers,
            acceptIfGoldRight: 0,
            rejectIfGoldWrong: 0,
            rejectReason: this.$store.state.defaults.reject_reason,
            lastChar: /^[a-z|A-Z|0-9]+[^#]\s?#{1}$/,
        }
    },
    computed: {
        splitFields: function() {
            return this.project.layout_fields.replace(/\s/g, '').split(',')
        },
        thirdPartData() {
            return {
                assignNumber: this.assignNumber,
                acceptIfGoldRight: this.acceptIfGoldRight,
                rejectIfGoldWrong: this.rejectIfGoldWrong,
                rejectReason: this.rejectReason,
            }
        },
    },
    validations() {
        return {
            assignNumber: {
                required,
                between: between(this.project.workers, 5),
            },
            whatToDo: {
                notEmpty,
            },
            rejectReason: {
                required,
            },
        }
    },
    created() {
        this.getCsvFields()
        var tmpObj = {}
        var isFromCsv = false
        for (var i = 0; i < this.splitFields.length; i++) {
            if (this.lastChar.test(this.splitFields[i])) {
                isFromCsv = true
            }
            tmpObj = {
                id: i,
                field: this.splitFields[i],
                isHandWritten: false,
                customValue: '',
                valueFrom: '',
                isFromCsv: isFromCsv,
            }
            this.firstPartData.push(tmpObj)
        }
    },
    mounted() {
        window.addEventListener('keydown', this.keyboardEvent)
    },
    methods: {
        keyboardEvent(event) {
            if (event.code == 'Escape') {
                this.toggleModal()
            } else if (event.code == 'Enter') {
                this.submit()
            }
        },
        submit() {
            this.$v.$touch()
            if (!this.$v.$invalid || (this.rejectIfGoldWrong == 0 && this.$v.rejectReason.$invalid)) {
                this.loading = true
                var url = this.APIURL + '?action=updateProjectStatus'
                axios({
                    method: 'post',
                    url: url,
                    data: {
                        id: this.project.id,
                        toStatus: 2,
                        layoutData: this.firstPartData,
                        answerData: this.secondPartData,
                        assignNumber: this.assignNumber,
                        acceptIfGoldRight: this.acceptIfGoldRight,
                        rejectIfGoldWrong: this.rejectIfGoldWrong,
                        rejectReason: this.rejectReason,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                })
                    .then(response => {
                        this.loading = false
                        console.log(response.data)
                        if (response.data.result == 'ERR') {
                            this.$emit('snackbar', 'Error: ' + response.data.error)
                        } else {
                            this.toggleModal()
                            this.$emit('layoutSet', 'Layout successfully set.')
                        }
                    })
                    .catch(err => {
                        this.$emit('snackbar', 'Error: server unreacheable')
                        console.log(err)
                        this.loading = false
                    })
            }
        },
        getCsvFields() {
            var url = this.APIURL + '?action=getData&id=' + this.project.id + '&howMany=1&page=1&isGold=0'
            axios
                .get(url)
                .then(res => {
                    this.csvValues = res.data.fields
                    this.loadingCsv = false
                })
                .catch(err => {
                    console.log(err)
                })
        },
        toggleModal() {
            this.$emit('layoutModal', ['layout', ''])
        },
        newElement() {
            let tmp = {
                id: this.secondPartData[this.secondPartData.length - 1].id + 1,
                varName: '',
                varValue: '',
                varNameTo: '',
                varValueTo: '',
            }
            this.secondPartData.push(tmp)
        },
        removeElement(arr) {
            this.secondPartData = this.secondPartData.filter(function(obj) {
                return obj.id !== arr[1]
            })
        },

        updateArr(arr) {
            if (arr != undefined) {
                if (arr[0] == 'first') {
                    this.firstPartData = arr[1]
                } else if (arr[0] == 'second') {
                    this.secondPartData = arr[1]
                } else if (arr[0] == 'third') {
                    this.assignNumber = arr[1].assignNumber
                    this.acceptIfGoldRight = arr[1].acceptIfGoldRight
                    this.rejectIfGoldWrong = arr[1].rejectIfGoldWrong
                    this.rejectReason = arr[1].rejectReason
                }
            }
        },
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.keyboardEvent)
    },
}
</script>

<style scoped>
.fade-enter-active {
    transition: opacity 0.3s ease-out !important;
}
.fade-leave-active{
    transition: opacity 0.25s ease-out !important;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
option {
    font-family: 'Roboto', sans-serif !important;
}
.customZ {
    z-index: 990;
}
</style>
