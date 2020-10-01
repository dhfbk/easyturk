<template>
    <transition name="fade" mode="out-in" :duration="{ enter: 500, leave: 500 }" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 z-30 bg-opacity-25"
        >
            <div class="bg-white rounded-lg w-5/6 max-h-80 overflow-y-auto">
                <div class="flex flex-col p-4">
                    <div class="flex w-full mb-2">
                        <div class="text-gray-900 font-bold text-lg">Set layout for the project</div>
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
                    <div class="hidden md:grid grid-cols-4">
                        <p class="font-semibold text-sm w-auto">Field:</p>
                        <p></p>
                        <p class="font-semibold text-sm w-auto -ml-6">Value:</p>
                        <p></p>
                    </div>
                    <firstPart
                        :firstPartData="firstPartData"
                        :splitFields="splitFields"
                        :csvValues="csvValues"
                        :loadingCsv="loadingCsv"
                        @newElement="newElement"
                        @removeElement="removeElement"
                        @updateArr="updateArr('first')"
                    />
                    <hr class="mb-1 mt-1 md:mt-2" />
                    <p class="font-semibold text-sm w-auto">How to convert answers:</p>
                    <secondPart
                        :secondPartData="secondPartData"
                        @newElement="newElement"
                        @removeElement="removeElement"
                        @updateArr="updateArr('second')"
                    />
                    <hr class="mb-1 mt-1 md:mt-2" />
                    <thirdPart
                        :thirdPartData="thirdPartData"
                        @updateArr="updateArr('third')"
                        :isGold="parseInt(project[0].count_gold)"
                    />

                    <div class="ml-auto flex flex-col xs2:flex-row mt-2">
                        <button
                            class="ripple flex flex-row transition duration-150 ease-in-out bg-primary hover:bg-blue-600 text-gray-100 py-2 px-4 rounded focus:outline-none"
                        >
                            <svg
                                :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
                                style="width:24px;height:24px"
                                viewBox="0 0 24 24"
                            >
                                <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                            </svg>Proceed
                        </button>
                        <button
                            class="ripple transition duration-150 ease-in-out hover:bg-gray-300 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
                            @click="toggleModal('close')"
                        >Cancel</button>
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
export default {
    name: 'modalLayout',
    props: { project: Array },
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
            firstPartData: [
                {
                    id: 0,
                    field: '',
                    isHandWritten: false,
                    customValue: '',
                    valueFrom: '',
                },
            ],
            secondPartData: [
                {
                    id: 0,
                    varName: '',
                    varValue: '',
                    varNameTo: '',
                    varValueTo: '',
                },
            ],
            thirdPartData: {
                assignNumber: this.project[0].workers,
                watDo: '',
            },
        }
    },
    methods: {
        getCsvFields() {
            var url = this.APIURL + '?action=getData&id=' + this.project[0].id + '&howMany=1&page=1&isGold=0'
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
        toggleModal(mode) {
            if (mode == 'close') {
                this.$emit('layoutModal', ['layout', ''])
            }
        },
        newElement(arr) {
            if (arr == 'first') {
                let tmp = {
                    id: this.firstPartData[this.firstPartData.length - 1].id + 1,
                    field: '',
                    isHandWritten: false,
                    customValue: '',
                    valueFrom: 'prj',
                }
                this.firstPartData.push(tmp)
            } else if (arr == 'second') {
                let tmp = {
                    id: this.secondPartData[this.secondPartData.length - 1].id + 1,
                    varName: '',
                    varValue: '',
                    varNameTo: '',
                    varValueTo: '',
                }
                this.secondPartData.push(tmp)
            }
        },
        removeElement(arr) {
            if (arr[0] == 'first') {
                this.firstPartData = this.firstPartData.filter(function(obj) {
                    return obj.id !== arr[1]
                })
            } else if (arr[0] == 'second') {
                this.secondPartData = this.secondPartData.filter(function(obj) {
                    return obj.id !== arr[1]
                })
            }
        },

        updateArr(arr, type) {
            if (type == 'first') {
                this.firstPartData = arr
            } else if (type == 'second') {
                this.secondPartData = arr
            } else if (type == 'third') {
                this.thirdPartData = arr
            }
        },
    },
    created() {
        this.getCsvFields()
    },
    computed: {
        splitFields: function() {
            return this.project[0].layout_fields.replace(/\s/g, '').split(',')
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
option {
    font-family: 'Work Sans', sans-serif !important;
}
</style>
