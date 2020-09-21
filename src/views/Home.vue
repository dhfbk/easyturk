<template>
    <div class="home mx-8 mt-4 sm:mt-6 md:mx-16 pb-6">
        <modalEliminazione v-if="modalElim" @toggleModal="toggleModal" :id="modalId" />
        <modalUpload v-if="modalStd" @upload="toggleModal" :type="'std'" />
        <modalUpload v-if="modalGld" @upload="toggleModal" :type="'gld'" />
        <div class="mb-6">
            <div class="flex content-center flex-col sm:flex-row">
                <svg style="width:24px;" viewBox="0 0 24 24">
                    <path
                        d="M17,17H15V13H17M13,17H11V7H13M9,17H7V10H9M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z"
                    />
                </svg>
                <h2 class="text-lg font-semibold ml-1 tracking-tight text-gray-800">Progetti</h2>
            </div>
            <div class="my-1 mx-2 p-2 flex items-center flex-wrap">
                <div class="hidden w-3/5 md:flex text-center items-center flex-wrap text-lg">
                    <span class="w-1/3 font-light">Project Id</span>
                    <span class="w-1/3 font-light">Title</span>
                    <span class="w-1/3 font-light">Date</span>
                </div>
            </div>
            <div v-for="i in projects" :key="i.id" class="w-full mx-auto relative">
                <projectListItem :projectData="i" @deleteThis="toggleModal" @upload="toggleModal" />
            </div>
        </div>
        <div
            class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 justify-center"
        >
            <div class="rounded shadow-md m-2 p-4 col-span-3 row-span-3 bg-white">
                <div class="flex content-center flex-col sm:flex-row">
                    <svg style="width:24px;" viewBox="0 0 24 24">
                        <path
                            d="M18,19H6V17.6C6,15.6 10,14.5 12,14.5C14,14.5 18,15.6 18,17.6M12,7A3,3 0 0,1 15,10A3,3 0 0,1 12,13A3,3 0 0,1 9,10A3,3 0 0,1 12,7M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z"
                        />
                    </svg>
                    <h2 class="text-lg font-semibold m-1 tracking-tight text-gray-800">Workers</h2>
                </div>
                <tabellaWorker />
            </div>
            <div class="rounded shadow-md m-2 p-2 flex flex-col justify-around bg-white">
                <div class="flex content-center flex-col sm:flex-row">
                    <svg style="width:24px" viewBox="0 0 24 24">
                        <path
                            d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z"
                        />
                    </svg>
                    <h2 class="font-semibold text-lg m-1 tracking-tight w-10/12 text-gray-800">
                        Saldo account
                    </h2>
                </div>
                <p class="text-black font-light text-5xl m-1 text-center">54$</p>
            </div>
            <div class="rounded shadow-md m-2 p-2 flex flex-col justify-around bg-white">
                <div class="flex content-center flex-col sm:flex-row">
                    <svg style="width:24px;" viewBox="0 0 24 24">
                        <path
                            d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"
                        />
                    </svg>
                    <h2 class="font-semibold text-lg m-1 tracking-tight text-gray-800">
                        Reviewable results
                    </h2>
                </div>
                <p class="text-black font-light text-5xl m-1 text-center">93</p>
            </div>
        </div>
    </div>
</template>

<script>
import projectListItem from '../components/projectListItem.vue'
import tabellaWorker from '../components/tabellaWorker.vue'
import modalEliminazione from '../components/modalEliminazione.vue'
import modalUpload from '../components/modalUpload.vue'
import axios from 'axios'
//let $ = require('jquery')

export default {
    name: 'Home',
    components: {
        projectListItem,
        tabellaWorker,
        modalEliminazione,
        modalUpload,
    },
    data() {
        return {
            projects: null,
            modalId: '',
            // projects: [
            //     {
            //         id: 'URJYRLU1PP',
            //         title: 'Offensive Tweet Recognition',
            //         date: '27/08/2018',
            //         hasGold: true,
            //         hasData: true,
            //         totalHITs: 350,
            //         pendingReviewHITs: 100,
            //         completedHITs: 200,
            //         inProgressHITs: 50,
            //     },
            //     {
            //         id: 'G7Q5EOBNDG',
            //         title: 'Image Tagging',
            //         date: '19/06/2018',
            //         hasGold: false,
            //         hasData: true,
            //         totalHITs: 200,
            //         pendingReviewHITs: 62,
            //         completedHITs: 38,
            //         inProgressHITs: 100,
            //     },
            //     {
            //         id: 'YQOKWUAP6A',
            //         title: 'Soccer Events Recognition',
            //         date: '04/12/2019',
            //         hasGold: false,
            //         hasData: true,
            //         totalHITs: 400,
            //         pendingReviewHITs: 15,
            //         completedHITs: 360,
            //         inProgressHITs: 25,
            //     },
            //     {
            //         id: '8GIS78FLI2',
            //         title: 'Survey on Sports',
            //         date: '30/05/2020',
            //         hasGold: false,
            //         hasData: false,
            //         totalHITs: 250,
            //         pendingReviewHITs: 0,
            //         completedHITs: 250,
            //         inProgressHITs: 0,
            //     },
            // ],
            modalElim: false,
            modalStd: false,
            modalGld: false,
        }
    },
    created() {
        axios({
            url: 'https://web.apnetwork.it/mturk/?action=listProjects',
            method: 'get',
        }).then(res => {
            this.projects = res.data.values
        })
        //var self = this
        // $.ajax({
        //     url: 'https://web.apnetwork.it/mturk/?action=listProjects',
        //     method: 'get',
        //     success: function(data) {
        //         // console.log(data)
        //         self.projects = data.values
        //     },
        //     error: function(data) {
        //         console.log(data)
        //     },
        // })
    },
    methods: {
        //metodo che mostra o nasconde il dialog
        toggleModal(arr) {
            if (arr[0] == 'elim') {
                this.modalElim = !this.modalElim
            } else if (arr[0] == 'std') {
                this.modalStd = !this.modalStd
            } else {
                this.modalGld = !this.modalGld
            }
            //console.log(arr[1])
            this.modalId = arr[1]
            if (arr[0] == 'elim' && this.modalId != '') {
                this.projects = this.projects.filter(function(el) {
                    return el.id != this.modalId
                })
            }
        },
    },
}
</script>

<style scoped>
path {
    fill: rgba(45, 55, 72, 1);
}
</style>
