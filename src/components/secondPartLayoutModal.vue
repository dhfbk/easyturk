<template>
    <div>
        <div v-if="isGold == 0">
            <p class="text-teal-500 text-md text-center pt-2">Golden standard CSV hasn't been uploaded</p>
        </div>
        <div
            class="flex flex-col md:flex-row items-center content-center py-2"
            v-for="second in secondPartData"
            :key="second.id"
            @change="prova()"
        >
            <span>If</span>
            <div
                class="flex flex-col xs2:flex-row md:flex-col w-full content-center mx-2 items-center"
            >
                <div
                    class="flex flex-col xs2:flex-row md:flex-col w-full content-center items-center"
                >
                    <label
                        for="value"
                        class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0"
                    >Value:</label>
                    <input
                        :class="isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mt-2 md:mt-0 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        name="value"
                        type="text"
                        placeholder="Var name"
                        maxlength="255"
                        :disabled="isGold == 0"
                        v-model="second.varName"
                    />
                </div>
            </div>
            <span>=</span>

            <div
                class="flex flex-col xs2:flex-row md:flex-col w-full content-center mx-2 items-center"
            >
                <label
                    for="value"
                    class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0"
                >Value:</label>
                <input
                    :class="isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mt-2 md:mt-0 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    name="value"
                    type="text"
                    placeholder="Var value"
                    maxlength="255"
                    :disabled="isGold == 0"
                    v-model="second.varValue"
                />
            </div>
            <span>then</span>

            <div
                class="flex flex-col xs2:flex-row md:flex-col w-full content-center mx-2 items-center"
            >
                <label
                    for="value"
                    class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0"
                >Value:</label>
                <input
                    :class="isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mt-2 md:mt-0 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    name="value"
                    type="text"
                    placeholder="Var name"
                    maxlength="255"
                    :disabled="isGold == 0"
                    v-model="second.varNameTo"
                />
            </div>
            <span>=</span>

            <div
                class="flex flex-col xs2:flex-row md:flex-col w-full content-center mx-2 items-center"
            >
                <label
                    for="value"
                    class="md:hidden font-semibold text-sm w-auto mr-2 md:mr-0"
                >Value:</label>
                <input
                    :class="isGold == 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 mt-2 md:mt-0 transition duration-150 focus:outline-none focus:border-gray-500 hover:border-gray-500"
                    name="value"
                    type="text"
                    placeholder="Var value"
                    maxlength="255"
                    :disabled="isGold == 0"
                    v-model="second.varValueTo"
                />
            </div>

            <span class="flex flex-row mx-2 text-gray-800 mt-2 md:mt-0">
                <button
                    class="p-2 ripple rounded-full focus:outline-none hover:bg-gray-300"
                    @click="newElement('second')"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="fill-current">
                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                </button>
                <button
                    class="p-2 ripple rounded-full focus:outline-none hover:bg-gray-300"
                    @click="removeElement('second', second.id)"
                    :class="second.id != 0 ? '' : 'hidden md:block md:invisible'"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="fill-current">
                        <path
                            fill="currentColor"
                            d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                        />
                    </svg>
                </button>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        secondPartData: Array,
        isGold: Number,
    },
    data() {
        return {
            dataArr: [],
        }
    },
    created() {
        this.dataArr = this.secondPartData
    },
    methods: {
        newElement(arr) {
            this.$emit('newElement', arr)
        },
        removeElement(arr, id) {
            this.$emit('removeElement', [arr, id])
        },
        prova() {
            this.$emit('updateArr', this.dataArr)
        },
    },
    watch: {
        secondPartData: function() {
            this.dataArr = this.secondPartData
        },
    },
}
</script>

<style></style>
