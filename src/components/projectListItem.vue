<template>
    <!-- <div class="transition duration-300 ease-in-out"> -->
    <div class="rounded shadow-lg m-1 p-4 flex items-center flex-wrap">
        <span class="w-1/6">{{ projectData.id }}</span>
        <span class="w-1/4">{{ projectData.title }}</span>
        <span class="">{{ projectData.date }}</span>
        <span class="flex-grow flex justify-end w-1/3">
            <button class="bg-orange-500 hover:bg-orange-700 text-white  py-2 px-4 rounded m-1">
                Publish Batch
            </button>
            <button class="bg-gray-300 hover:bg-gray-400 py-2 px-4 rounded m-1">
                Edit
            </button>
            <button class="bg-gray-300 hover:bg-gray-400 py-2 px-4 rounded m-1">
                Upload Data
            </button>
            <button class="bg-gray-300 hover:bg-gray-400 py-2 px-4 rounded m-1">
                Upload Gold
            </button>
            <button class="bg-red-600 hover:bg-red-700 text-white  py-2 px-4 rounded m-1">
                Delete
            </button>
            <button
                class="bg-white hover:bg-gray-300 py-2 px-2 m-1 rounded"
                @click="isOpen = !isOpen"
            >
                <svg
                    class="transition duration-300 ease-in-out "
                    :class="{ 'transform  rotate-180': isOpen }"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="black"
                    width="24px"
                    height="24px"
                >
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z" />
                </svg>
            </button>
        </span>
        <div class="w-full h-40 bg-orange-400" v-if="isOpen"></div>
    </div>

    <!-- </div> -->
</template>

<script>
export default {
    props: {
        projectData: Object,
    },
    data() {
        return {
            isOpen: false,
        }
    },
}
</script>

<style scoped>
.height {
    height: 200px;
}
</style>
