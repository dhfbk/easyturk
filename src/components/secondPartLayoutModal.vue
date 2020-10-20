<template>
    <div class="lg:mt-2">
        <div
            class="flex flex-col md:flex-row items-center content-center mb-1 text-sm"
            v-for="second in secondPartData"
            :key="second.id"
            @change="prova()"
        >
            <span>If</span>
            <div class="flex flex-col xs2:flex-row content-center items-center">
                <div class="flex flex-col xs2:flex-row md:flex-col w-full content-center mx-2 items-center">
                    <input
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 md:mt-0 transition duration-150 ease-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        name="value"
                        type="text"
                        placeholder="Var name"
                        maxlength="255"
                        v-model="second.varName"
                    />
                </div>
                <span>=</span>

                <div class="flex flex-col xs2:flex-row md:flex-col w-full content-center mx-2 items-center">
                    <input
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 md:mt-0 transition duration-150 ease-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        name="value"
                        type="text"
                        placeholder="Var value"
                        maxlength="255"
                        v-model="second.varValue"
                    />
                </div>
            </div>

            <span>then</span>

            <div class="flex flex-col xs2:flex-row content-center items-center">
                <div class="flex flex-col xs2:flex-row md:flex-col w-full content-center mx-2 items-center">
                    <input
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 md:mt-0 transition duration-150 ease-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        name="value"
                        type="text"
                        placeholder="Var name"
                        maxlength="255"
                        v-model="second.varNameTo"
                    />
                </div>
                <span>=</span>

                <div class="flex flex-col xs2:flex-row md:flex-col w-full content-center mx-2 items-center">
                    <input
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-2 px-4 md:mt-0 transition duration-150 ease-out focus:outline-none focus:border-gray-500 hover:border-gray-500"
                        name="value"
                        type="text"
                        placeholder="Var value"
                        maxlength="255"
                        v-model="second.varValueTo"
                    />
                </div>
            </div>

            <span class="flex flex-row mx-2 text-gray-800 mt-2 md:mt-0">
                <button
                    class="p-2 ripple rounded-full transition duration-100 ease-out focus:outline-none hover:bg-gray-300"
                    @click="newElement('second')"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" class="fill-current">
                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                    </svg>
                </button>
                <button
                    class="p-2 ripple rounded-full focus:outline-none transition duration-100 ease-out hover:bg-gray-300"
                    @click="removeElement('second', second.id)"
                    :class="secondPartData.length != 1 ? '' : 'hidden md:block md:invisible'"
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
            var send = ['second', this.dataArr]
            this.$emit('updateArr', send)
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
