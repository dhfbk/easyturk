<template>
    <div class="rounded shadow-lg m-4 p-4">
        <span>{{ projectData.id }}</span>
        <span>{{ projectData.title }}</span>
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
            Button
        </button>
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
            Button
        </button>
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
            Button
        </button>
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
            Button
        </button>
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
            Button
        </button>
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
