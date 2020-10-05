<template>
    <div>
        <div class="flex flex-col items-center md:items-end content-center" @change="update()">
            <div class="flex flex-col w-full content-center items-center">
                <div v-if="isGold == 0">
                    <p class="text-teal-500 text-md">Golden standard CSV hasn't been uploaded</p>
                </div>
                <label for="whatDo" class="font-semibold text-sm md:mr-0 text-left w-full"
                    >What to do if gold is wrong:</label
                >
                <div class="relative w-full block md:mt-0">
                    <select
                        :class="
                            isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'
                        "
                        class="appearance-none block w-full border border-gray-200 rounded py-2 pr-4 pl-2 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        id="whatDo"
                        name="whatDo"
                        :disabled="isGold == 0"
                        v-model="dataObj.whatToDo"
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
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full content-center items-center mt-1">
                <label for="assNumber" class="font-semibold text-sm md:mr-0 text-left w-full"
                    >Maximum number of assignments (in the project it's {{ min }}):</label
                >
                <input
                    :class="
                        isGold == 0 || dataObj.whatToDo != 'accept'
                            ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                            : 'bg-gray-100 text-gray-700'
                    "
                    class="appearance-none block w-full border border-gray-200 rounded py-2 pr-4 pl-2 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    id="assNumber"
                    name="assNumber"
                    type="number"
                    :min="min"
                    max="5"
                    step="1"
                    placeholder="1"
                    :disabled="isGold == 0 || dataObj.whatToDo != 'accept'"
                    required
                    v-model="dataObj.assignNumber"
                />
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
    },
    data() {
        return {
            dataObj: {},
            min: 0,
        }
    },
    created() {
        this.dataObj = this.thirdPartData
        this.min = this.thirdPartData.assignNumber
    },
    methods: {
        update() {
            this.$emit('updateArr', this.dataObj)
        },
    },
}
</script>