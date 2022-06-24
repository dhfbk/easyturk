<template>
    <div @change="update">
        <div v-if="isGold == 0">
            <p class="text-teal-500 text-md text-center">Golden standard CSV hasn't been uploaded</p>
        </div>
        <div class="flex flex-col md:flex-row items-center md:items-end content-center text-sm">
            <div class="relative w-full block md:mt-0">
                <span class="flex flex-row items-center my-2">
                    <span
                        class="
                            bg-white
                            border-2
                            md:mt-0
                            rounded
                            border-gray-400
                            w-5
                            h-5
                            flex flex-shrink-0
                            focus-within:border-blue-500
                        "
                        :class="isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : ''"
                    >
                        <input
                            type="checkbox"
                            id="reject"
                            class="opacity-0 absolute w-4 h-4"
                            @click="reject = !reject"
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
                    <label class="ml-2 font-semibold tracking-wide" for="reject">
                        Reject if gold is wrong and extend HIT to
                        <input
                            :class="
                                !reject || isGold == 0
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            :disabled="!reject || isGold == 0"
                            class="
                                inline-input
                                appearance-none
                                w-full
                                border border-gray-200
                                rounded
                                py-2
                                pr-4
                                pl-2
                                transition
                                duration-150
                                ease-out
                                focus:outline-none focus:border-gray-500
                                hover:border-gray-500
                            "
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
                        assignments (min {{ min }}). Reason of reject:
                    </label>
                </span>
                <span class="flex md:flex-row flex-col my-2">
                    <div class="flex flex-col content-center items-center mt-1 md:mt-0 md:mr-2 w-full pl-10">
                        <input
                            type="text"
                            :class="[
                                !reject || isGold == 0
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700',
                                v.thirdPartData.rejectReason.$error ? 'shadowRed' : '',
                            ]"
                            placeholder="Reason for rejection"
                            class="
                                appearance-none
                                block
                                w-full
                                border border-gray-200
                                rounded
                                py-2
                                px-4
                                md:mt-0
                                transition
                                duration-150
                                ease-out
                                focus:outline-none focus:border-gray-500
                                hover:border-gray-500
                            "
                            :disabled="!reject || isGold == 0"
                            id="reject"
                            v-model.trim="v.thirdPartData.rejectReason.$model"
                            required
                        />
                    </div>
                </span>
                <span class="flex flex-row items-center my-2">
                    <span
                        class="
                            bg-white
                            border-2
                            md:mt-0
                            rounded
                            border-gray-400
                            w-5
                            h-5
                            flex flex-shrink-0
                            focus-within:border-blue-500
                        "
                        :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
                    >
                        <input
                            type="checkbox"
                            id="accept"
                            class="opacity-0 absolute w-4 h-4"
                            @click="accept = !accept"
                            v-model="accept"
                            :class="isGold == 0 ? 'cursor-not-allowed' : ''"
                            :disabled="isGold == 0"
                        />
                        <svg
                            :class="accept ? '' : 'hidden'"
                            class="fill-current w-4 h-4 text-secondary pointer-events-none"
                            viewBox="0 0 20 20"
                        >
                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                        </svg>
                    </span>
                    <label class="ml-2 font-semibold tracking-wide" for="accept">Accept if gold is right</label>
                </span>

                <span class="flex flex-row items-center my-2">
                    <span
                        class="
                            bg-white
                            border-2
                            md:mt-0
                            rounded
                            border-gray-400
                            w-5
                            h-5
                            flex flex-shrink-0
                            focus-within:border-blue-500
                        "
                        :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
                    >
                        <input
                            type="checkbox"
                            id="block_worker_fast"
                            class="opacity-0 absolute w-4 h-4"
                            @click="block_worker_fast = !block_worker_fast"
                            v-model="block_worker_fast"
                            :class="isGold == 0 ? 'cursor-not-allowed' : ''"
                            :disabled="isGold == 0"
                        />
                        <svg
                            :class="block_worker_fast ? '' : 'hidden'"
                            class="fill-current w-4 h-4 text-secondary pointer-events-none"
                            viewBox="0 0 20 20"
                        >
                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                        </svg>
                    </span>
                    <label class="ml-2 font-semibold tracking-wide" for="block_worker_fast"
                        >Block the worker if he/she spends less than
                        <input
                            :class="
                                !block_worker_fast || isGold == 0
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            :disabled="!block_worker_fast || isGold == 0"
                            class="
                                inline-input
                                appearance-none
                                border border-gray-200
                                rounded
                                py-2
                                pr-4
                                pl-2
                                transition
                                duration-150
                                ease-out
                                focus:outline-none focus:border-gray-500
                                hover:border-gray-500
                            "
                            id="rejectTime"
                            name="rejectTime"
                            type="number"
                            min="20"
                            step="10"
                            placeholder="1"
                            required
                            v-model="rejectTime"
                        />
                        seconds on a HIT.
                    </label>
                </span>

                <span class="flex flex-row items-center my-2">
                    <span
                        class="
                            bg-white
                            border-2
                            md:mt-0
                            rounded
                            border-gray-400
                            w-5
                            h-5
                            flex flex-shrink-0
                            focus-within:border-blue-500
                        "
                        :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
                    >
                        <input
                            type="checkbox"
                            id="block_worker_bad"
                            class="opacity-0 absolute w-4 h-4"
                            @click="block_worker_bad = !block_worker_bad"
                            v-model="block_worker_bad"
                            :class="isGold == 0 ? 'cursor-not-allowed' : ''"
                            :disabled="isGold == 0"
                        />
                        <svg
                            :class="block_worker_bad ? '' : 'hidden'"
                            class="fill-current w-4 h-4 text-secondary pointer-events-none"
                            viewBox="0 0 20 20"
                        >
                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                        </svg>
                    </span>
                    <label class="ml-2 font-semibold tracking-wide" for="block_worker_bad">
                        Block the worker if he/she misclassifies the gold
                        <input
                            :class="
                                !block_worker_bad || isGold == 0
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            :disabled="!block_worker_bad || isGold == 0"
                            class="
                                inline-input
                                appearance-none
                                border border-gray-200
                                rounded
                                py-2
                                pr-4
                                pl-2
                                transition
                                duration-150
                                ease-out
                                focus:outline-none focus:border-gray-500
                                hover:border-gray-500
                            "
                            id="missNumber"
                            name="missNumber"
                            type="number"
                            min="1"
                            step="1"
                            placeholder="1"
                            required
                            v-model="missNumber"
                        />
                        times on
                        <input
                            :class="
                                !block_worker_bad || isGold == 0
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            :disabled="!block_worker_bad || isGold == 0"
                            class="
                                inline-input
                                appearance-none
                                border border-gray-200
                                rounded
                                py-2
                                pr-4
                                pl-2
                                transition
                                duration-150
                                ease-out
                                focus:outline-none focus:border-gray-500
                                hover:border-gray-500
                            "
                            id="missNumberTotal"
                            name="missNumberTotal"
                            type="number"
                            min="1"
                            step="1"
                            placeholder="1"
                            required
                            v-model="missNumberTotal"
                        />
                        consecutive HITs.
                    </label>
                </span>

                <span class="flex flex-row items-center my-2">
                    <span
                        class="
                            bg-white
                            border-2
                            md:mt-0
                            rounded
                            border-gray-400
                            w-5
                            h-5
                            flex flex-shrink-0
                            focus-within:border-blue-500
                        "
                        :class="[isGold == 0 ? 'bg-gray-400 cursor-not-allowed' : '']"
                    >
                        <input
                            type="checkbox"
                            id="reject_old"
                            class="opacity-0 absolute w-4 h-4"
                            @click="reject_old = !reject_old"
                            v-model="reject_old"
                            :class="isGold == 0 ? 'cursor-not-allowed' : ''"
                            :disabled="isGold == 0"
                        />
                        <svg
                            :class="reject_old ? '' : 'hidden'"
                            class="fill-current w-4 h-4 text-secondary pointer-events-none"
                            viewBox="0 0 20 20"
                        >
                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                        </svg>
                    </span>
                    <label class="ml-2 font-semibold tracking-wide" for="reject_old"
                        >Reject pending assignments from blocked users</label
                    >
                </span>
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
            assignNumber: 0,

            block_worker_fast: false,
            rejectTime: 30,

            block_worker_bad: false,
            missNumberTotal: 10,
            missNumber: 3,

            reject_old: false,

            dataReturn: {},

            min: 0,
        }
    },
    created() {
        this.min = this.thirdPartData.assignNumber
        this.assignNumber = this.thirdPartData.assignNumber

        // Leave here to update the values in modalLayout
        this.update()
    },
    methods: {
        boolToInt(i) {
            return i == false ? 0 : 1
        },
        update() {
            this.dataReturn['rejectIfGoldWrong'] = this.boolToInt(this.reject)
            this.dataReturn['acceptIfGoldRight'] = this.boolToInt(this.accept)
            this.dataReturn['rejectReason'] = this.v.thirdPartData.rejectReason.$model
            this.dataReturn['assignNumber'] = this.assignNumber

            this.dataReturn['block_worker_fast'] = this.boolToInt(this.block_worker_fast)
            this.dataReturn['rejectTime'] = this.rejectTime

            this.dataReturn['block_worker_bad'] = this.boolToInt(this.block_worker_bad)
            this.dataReturn['missNumberTotal'] = this.missNumberTotal
            this.dataReturn['missNumber'] = this.missNumber

            this.dataReturn['reject_old'] = this.boolToInt(this.reject_old)

            var send = ['third', this.dataReturn]

            this.$emit('updateArr', send)
        },
    },
}
</script>

<style scoped>
.check {
    height: 20px;
    width: 20px;
}

input.inline-input {
    width: 75px;
}
</style>
