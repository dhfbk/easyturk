<template>
    <div class="rounded shadow-lg m-1 p-4 flex items-center ">
        <span class="w-1/6">{{ projectData.id }}</span>
        <span class="w-1/4">{{ projectData.title }}</span>
        <span class="">{{ projectData.date }}</span>
        <span class="flex-grow flex justify-end">
            <button class="bg-orange-500 hover:bg-orange-700 text-white  py-2 px-4 rounded m-1">
                Publish Batch
            </button>
            <button class="bg-gray-300 hover:bg-gray-400   py-2 px-4 rounded m-1">
                Edit
            </button>
            <button class="bg-gray-300 hover:bg-gray-400   py-2 px-4 rounded m-1">
                Upload Data
            </button>
            <button class="bg-gray-300 hover:bg-gray-400   py-2 px-4 rounded m-1">
                Upload Gold
            </button>
            <button class="bg-red-600 hover:bg-red-700 text-white  py-2 px-4 rounded m-1">
                Delete
            </button>
        </span>
    </div>
</template>

<script>
export default {
    props: {
        projectData: Object,
    },
}
</script>

<style></style>
