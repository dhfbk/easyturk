<template>
    <div v-if="type == 'homePrj'" class="shadow-md my-2 mx-2 bg-white rounded-md p-4 w-auto">
        <div class="animate-pulse flex flex-col xs2:flex-row justify-center content-center items-center">
            <div class="h-2 w-full bg-gray-500 rounded"></div>
            <div class="xs2:hidden mt-6 h-2 w-3/4 bg-gray-500 rounded"></div>
            <div class="flex flex-row justify-end w-full sm:w-auto mt-6 xs2:mt-0 sm:ml-4">
                <div class="hidden sm:block rounded bg-gray-500 h-10 w-16 mr-2"></div>
                <div class="hidden sm:block rounded bg-gray-500 h-10 w-16 mr-2"></div>
                <div class="hidden md:block rounded bg-gray-500 h-10 w-16 mr-2"></div>
                <div class="hidden xs:block rounded bg-gray-500 h-10 w-16 mr-2"></div>
                <div class="rounded bg-gray-500 h-10 w-16"></div>
            </div>
        </div>
    </div>
    <div v-else-if="type == 'cardInfoVisualizza'" class="shadow-md my-2 bg-white rounded-md p-4 w-full">
        <div class="animate-pulse flex flex-col">
            <div class="h-2 w-full bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-3/4 bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-1/2 bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-full bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-3/4 bg-gray-500 rounded"></div>
            <div class="mt-2 h-2 w-5/6 bg-gray-500 rounded"></div>
        </div>
    </div>
    <div v-else-if="type == 'cardAnalyticsVisualizza'" class="shadow-md my-2 bg-white rounded-md p-4 w-full">
        <div class="animate-pulse flex flex-col">
            <div class="h-2 w-1/2 bg-gray-500 rounded"></div>
            <div class="flex flex-row justify-evenly w-full mt-6 flex-wrap">
                <div class="rounded-full bg-gray-500 h-24 w-24 m-2" v-for="n in num" :key="n"></div>
            </div>
        </div>
    </div>
    <div v-else-if="type == 'hitList'" class="my-2 p-4 w-full container mx-auto">
        <div class="animate-pulse">
            <div class="flex flex-wrap">
                <div v-for="n in 100" :key="n" class="h-5 w-5 m-1 bg-gray-500 rounded-full"></div>
            </div>
            <div class="mt-4 h-2 w-1/4 bg-gray-500 rounded"></div>
            <div
                class="flex content-center flex-col md:flex-row justify-around md:justify-between items-center mt-4 w-full p-2 h-24 border rounded p-2 bg-white"
            >
                <div class="mx-auto h-2 w-5/6 md:w-1/3 bg-gray-500 rounded"></div>
                <span class="w-px hidden md:block h-full bg-gray-300"></span>
                <div class="mx-auto h-2 w-5/6 md:w-1/3 bg-gray-500 rounded"></div>
            </div>
        </div>
    </div>
    <div v-else-if="type == 'progressVisualizza'" class="shadow-md my-2 bg-white rounded-md p-4 w-full">
        <div class="animate-pulse flex flex-col">
            <span class="h-2 w-16 bg-gray-500 rounded mt-4 mb-6"></span>
            <div class="h-8 w-11/12 mx-auto bg-gray-500 rounded"></div>
            <div class="flex flex-col xs2:flex-row justify-around w-full mt-6 flex-wrap px-4 pb-8">
                <span class="mr-4 flex content-center items-center mt-2 sm:mt-0">
                    <span class="h-4 w-8 bg-gray-500 rounded mr-2"></span>
                    <span class="h-2 w-16 bg-gray-500 rounded"></span>
                </span>
                <span class="mr-4 flex content-center items-center mt-2 sm:mt-0">
                    <span class="h-4 w-8 bg-gray-500 rounded mr-2"></span>
                    <span class="h-2 w-16 bg-gray-500 rounded"></span>
                </span>
                <span class="flex content-center items-center mt-2 sm:mt-0">
                    <span class="h-4 w-8 bg-gray-500 rounded mr-2"></span>
                    <span class="h-2 w-16 bg-gray-500 rounded"></span>
                </span>
            </div>
            <div class="h-2 w-full bg-gray-500 rounded mb-2"></div>
            <div class="h-2 w-full bg-gray-500 rounded"></div>
            <div class="flex justify-end w-full mt-4">
                <span class="h-8 w-16 bg-gray-500 rounded"></span>
            </div>
        </div>
    </div>
    <div v-else-if="type == 'csvData'">
        <div class="animate-pulse">
            <table class="shadow-md rounded w-full bg-white mx-auto">
                <thead>
                    <tr class="text-left text-white bg-primary border-b border-gray-300 uppercase">
                        <td class="p-1 px-2 border-r border-gray-300" v-for="i in 3" :key="i">
                            <div class="h-4 w-full"></div>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="n in 10" :key="n" class="border-b border-gray-300 hover:bg-gray-100">
                        <td class="p-1 px-2 border-r border-gray-300" v-for="i in 3" :key="i">
                            <div class="h-4 w-full bg-gray-400 rounded"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
