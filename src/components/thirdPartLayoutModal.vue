<template>
    <div>
        <div v-if="isGold == 0">
            <p class="text-teal-500 text-md text-center">Golden standard CSV hasn't been uploaded</p>
        </div>
        <div class="flex flex-col md:flex-row items-center md:items-end content-center" @change="update()">
            <div class="relative w-full block md:mt-0">
                <span class="flex flex-row items-center my-2">
                    <span class="mr-2 font-semibold tracking-wide">Reject if gold is wrong: </span>
                    <span
                        class="bg-white border-2 md:mt-0 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
                        @click="reject = !reject"
                        :class="isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : ''"
                    >
                        <input
                            type="checkbox"
                            class="opacity-0 absolute"
                            v-model="reject"
                            :disabled="isGold == 0"
                            :class="isGold == 0 ? 'cursor-not-allowed' : ''"
                        />
                        <svg
                            :class="reject ? '' : 'hidden'"
                            class="fill-current w-4 h-4 text-secondary pointer-events-none"
                            viewBox="0 0 20 20"
                        >
                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                        </svg>
                    </span>
                </span>
                <span class="flex md:flex-row flex-col my-2">
                    <div class="flex flex-col w-full content-center items-center mt-1 md:mt-0 md:mr-2">
                        <label for="assNumber" class="font-semibold md:mr-0 text-left w-full tracking-wide"
                            >Maximum number of assignments ({{ min }}):</label
                        >
                        <input
                            :class="[
                                !reject || isGold == 0
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700',
                                v.assignNumber.$error ? 'shadowRed' : '',
                            ]"
                            :disabled="!reject || isGold == 0"
                            class="appearance-none block w-full border border-gray-200 rounded py-2 pr-4 pl-2 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                            id="assNumber"
                            name="assNumber"
                            type="number"
                            :min="min"
                            max="5"
                            step="1"
                            placeholder="1"
                            required
                            v-model="assignNumber"
                        />
                    </div>
                    <span class="w-full md:ml-2">
                        <label class="font-semibold  md:mr-0 text-left w-full tracking-wide" for="descrizione"
                            >Reason for rejection:
                        </label>
                        <div
                            class="box border rounded bg-gray-100 transition duration-150 ease-in-out hover:border-gray-500"
                        >
                            <input
                                type="text"
                                placeholder="Reason for rejection"
                                class="w-full py-2 px-4 focus:outline-none resize-none"
                                :class="[
                                    !reject || isGold == 0
                                        ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                        : 'bg-gray-100 text-gray-700',
                                    v.assignNumber.$error ? 'shadowRed' : '',
                                ]"
                                :disabled="!reject || isGold == 0"
                                id="descrizione"
                                v-model="reason"
                                required
                            />
                        </div>
                    </span>
                </span>
                <span class="flex flex-row items-center my-2">
                    <span class="mr-2 font-semibold tracking-wide">Accept if gold is right:</span>
                    <span
                        class="bg-white border-2 md:mt-0 rounded border-gray-400 w-5 h-5 flex flex-shrink-0 focus-within:border-blue-500"
                        @click="accept = !accept"
                        :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
                    >
                        <input
                            type="checkbox"
                            class="opacity-0 absolute"
                            v-model="accept"
                            :class="isGold == 0 ? 'cursor-not-allowed' : ''"
                        />
                        <svg
                            :class="accept ? '' : 'hidden'"
                            class="fill-current w-4 h-4 text-secondary pointer-events-none"
                            viewBox="0 0 20 20"
                        >
                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                        </svg>
                    </span>
                </span>
                <!-- <select
                        :class="[
                            isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700',
                            v.whatToDo.$error ? 'shadowRed' : '',
                        ]"
                        class="appearance-none block w-full border border-gray-200 rounded py-2 pr-4 pl-2 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="whatDo"
                        name="whatDo"
                        :disabled="isGold == 0"
                        v-model="dataObj[1]"
                    >
                        <option value="" disabled selected hidden>Choose action...</option>
                        <option value="reject">Reject</option>
                        <option value="accept">Accept</option>
                        <option value="wait">Leave waiting</option>
                    </select>
                    <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>-->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'thirdPart',
    props: {
        thirdPartData: Object,
        isGold: Number,
        v: Object,
    },
    data() {
        return {
            reject: false,
            accept: false,
            reason: '',
            assignNumber: 0,
            data: {},
            dataObj: [],
            min: 0,
        }
    },
    created() {
        this.dataObj = this.thirdPartData
        this.min = this.thirdPartData.assignNumber
        this.assignNumber = this.dataObj.assignNumber
    },
    methods: {
        update() {
            this.reject == false ? (this.data.rejectIfGoldWrong = 0) : (this.data.rejectIfGoldWrong = 1)
            this.accept == false ? (this.data.acceptIfGoldRight = 0) : (this.data.acceptIfGoldRight = 1)
            this.data.reason = this.reason
            this.data.assignNumber = this.assignNumber
            console.log(this.data)
            this.$emit('updateArr', this.data)
        },
    },
}
</script>
