<template>
    <div class="home mx-8 my-4 sm:my-6 md:mx-16">
        <div class="mb-6">
            <h2 class="text-lg font-semibold ml-3 pl-4 tracking-tight">Progetti</h2>
            <div class="">
                <div class="hidden w-3/5 md:flex text-center items-center flex-wrap text-lg  p-2">
                    <span class="w-1/3 font-light">Project Id</span>
                    <span class="w-1/3 font-light">Title</span>
                    <span class="w-1/3 font-light">Date</span>
                </div>
            </div>
            <div v-for="i in projects" :key="i.id" class="w-full mx-auto relative">
                <projectListItem :projectData="i" />
            </div>
        </div>
        <div
            class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 justify-center"
        >
            <div class="rounded shadow-lg m-2 p-4 col-span-3 row-span-3">
                <h2 class="text-lg font-semibold m-1 tracking-tight">Workers</h2>
                <tabellaWorker />
            </div>
            <div class="rounded shadow-lg m-2 p-2 flex flex-col justify-around">
                <h2 class="font-semibold text-lg m-1 tracking-tight">Saldo account</h2>
                <p class="text-gray-700 text-5xl m-1 text-center">54$</p>
            </div>
            <div class="rounded shadow-lg m-2 p-2 flex flex-col justify-around">
                <h2 class="font-semibold text-lg m-1 tracking-tight">Reviewable results</h2>
                <p class="text-gray-700 text-5xl m-1 text-center">93</p>
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
                    totalHITs: 350,
                    pendingReviewHITs: 100,
                    completedHITs: 200,
                    inProgressHITs: 50,
                },
                {
                    id: 'G7Q5EOBNDG',
                    title: 'Image Tagging',
                    date: '19/06/2018',
                    hasGold: false,
                    hasData: true,
                    totalHITs: 200,
                    pendingReviewHITs: 62,
                    completedHITs: 38,
                    inProgressHITs: 100,
                },
                {
                    id: 'YQOKWUAP6A',
                    title: 'Soccer Events Recognition',
                    date: '04/12/2019',
                    hasGold: false,
                    hasData: true,
                    totalHITs: 400,
                    pendingReviewHITs: 15,
                    completedHITs: 360,
                    inProgressHITs: 25,
                },
                {
                    id: '8GIS78FLI2',
                    title: 'Survey on Sports',
                    date: '30/05/2020',
                    hasGold: false,
                    hasData: false,
                    totalHITs: 250,
                    pendingReviewHITs: 0,
                    completedHITs: 250,
                    inProgressHITs: 0,
                },
            ],
        }
    },
}
</script>
