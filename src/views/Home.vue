<template>
    <div class="home mx-8 my-4 sm:my-6 md:mx-16">
        <div class="mb-10">
            <h2 class="text-2xl">Progetti</h2>
            <div class="">
                <div class="w-full mx-auto">
                    <div class="rounded mx-5 my-2 flex items-center text-lg">
                        <span class="w-1/6">Project Id</span>
                        <span class="w-1/4">Title</span>
                        <span class="">Date</span>
                        <span class="flex-grow flex justify-end w-1/3"> </span>
                    </div>
                </div>
                <div v-for="i in projects" :key="i.id" class="w-full mx-auto">
                    <projectListItem :projectData="i" />
                </div>
            </div>
        </div>
        <div
            class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 justify-center"
        >
            <tabellaWorker />
            <div class="rounded shadow-lg m-4 p-2 flex flex-col justify-around">
                <div class="font-bold text-xl m-1">Saldo account</div>
                <p class="text-gray-700 text-4xl m-1">54$</p>
            </div>
            <div class="rounded shadow-lg m-4 p-2 flex flex-col justify-around">
                <div class="font-bold text-xl m-1">Reviewable results</div>
                <p class="text-gray-700 text-4xl m-1">93</p>
            </div>
        </div>
    </div>
</template>

<script>
// @ is an alias to /src
//import HelloWorld from "@/components/HelloWorld.vue";
import projectListItem from '../components/projectListItem.vue'
import tabellaWorker from '../components/tabellaWorker.vue'

export default {
    name: 'Home',
    components: {
        projectListItem,
        tabellaWorker,
    },
    data() {
        return {
            projects: [
                {
                    id: 'URJYRLU1PP',
                    title: 'Offensive Tweet Recognition',
                    date: '27/08/2018',
                    hasGold: true,
                    hasData: true,
                },
                {
                    id: 'G7Q5EOBNDG',
                    title: 'Image Tagging',
                    date: '19/06/2018',
                    hasGold: false,
                    hasData: true,
                },
                {
                    id: 'YQOKWUAP6A',
                    title: 'Soccer Events Recognition',
                    date: '04/12/2019',
                    hasGold: false,
                    hasData: true,
                },
                {
                    id: '8GIS78FLI2',
                    title: 'Survey on Sports',
                    date: '30/05/2020',
                    hasGold: false,
                    hasData: false,
                },
            ],
        }
    },
}
</script>
