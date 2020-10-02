<template>
    <div v-if="type == 'homePrj'" class="rounded shadow-md my-2 mx-2 bg-white rounded-md p-4 w-auto">
        <div class="animate-pulse flex flex-col xs2:flex-row justify-center content-center items-center">
            <div class="h-2 w-full bg-gray-500 rounded"></div>
            <div class="xs2:hidden mt-6 h-2 w-3/4 bg-gray-500 rounded"></div>
            <div class="flex flex-row justify-end w-full sm:w-auto mt-6 xs2:mt-0 sm:ml-4">
                <div class="hidden sm:block rounded bg-gray-500 h-10 w-16 mr-2"></div>
                <div class="hidden sm:block rounded bg-gray-500 h-10 w-16 mr-2"></div>
                <div class="hidden md:block rounded bg-gray-500 h-10 w-16 mr-2"></div>
                <div class="rounded bg-gray-500 h-10 w-16 mr-2"></div>
                <div class="rounded bg-gray-500 h-10 w-16"></div>
            </div>
        </div>
    </div>
    <div v-else-if="type == 'cardInfoVisualizza'" class="rounded shadow-md my-2 mx-2 bg-white rounded-md p-4 w-full">
        <div class="animate-pulse flex flex-col">
            <div class="h-2 w-full bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-3/4 bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-1/2 bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-full bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-3/4 bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-5/6 bg-gray-500 rounded"></div>
        </div>
    </div>
    <div
        v-else-if="type == 'cardAnalyticsVisualizza'"
        class="rounded shadow-md my-2 mx-2 bg-white rounded-md p-4 w-full"
    >
        <div class="animate-pulse flex flex-col">
            <div class="h-2 w-1/2 bg-gray-500 rounded"></div>
            <div class="flex flex-row justify-evenly w-full mt-6 flex-wrap">
                <div class="rounded-full bg-gray-500 h-24 w-24 m-2" v-for="n in num" :key="n"></div>
            </div>
        </div>
    </div>
    <div v-else-if="type == 'csvData'" class="rounded shadow-md my-2 mx-2 bg-white rounded-md p-4 w-full">
        <div class="animate-pulse flex flex-col">
            <div class="h-4 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-full bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-5/6 bg-gray-400 rounded"></div>
            <div class="mt-4 h-3 w-3/4 bg-gray-400 rounded"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'loader',
    props: {
        type: String,
        num: Number,
    },
}
</script>

<style>
</style>
