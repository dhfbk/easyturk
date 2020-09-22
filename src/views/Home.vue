<template>
    <div class="home mx-8 sm:mt-2 md:mx-16 pb-6">
        <p class="text-3xl sm:text-5xl font-light mb-4">Welcome, {{ userInfo.common_name }}</p>
        <modalEliminazione
            v-if="modalElim"
            @snackbar="emitSnackbar"
            @toggleModal="toggleModal(['elim'])"
            :id="modalId"
        />
        <modalUpload v-if="modalStd" @upload="toggleModal(['std'])" :type="'std'" :id="modalId" />
        <modalUpload v-if="modalGld" @upload="toggleModal(['gld'])" :type="'gld'" :id="modalId" />
        <div class="mb-6">
            <div class="flex content-center flex-col sm:flex-row">
                <svg style="width:24px;" viewBox="0 0 24 24">
                    <path
                        d="M17,17H15V13H17M13,17H11V7H13M9,17H7V10H9M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z"
                    />
                </svg>
                <h2 class="text-lg font-semibold ml-1 tracking-tight text-gray-800">Projects</h2>
            </div>
            <div class="my-1 mx-2 p-2 flex items-center flex-wrap">
                <div class="hidden w-3/5 md:flex text-center items-center flex-wrap text-lg">
                    <span class="w-1/3 font-light">Project Id</span>
                    <span class="w-1/3 font-light">Title</span>
                    <span class="w-1/3 font-light">Date</span>
                </div>
            </div>
            <div class="w-full flex flex-col justify-center" v-if="loading">
                <loader :type="'homePrj'" v-for="n in 2" :key="n" />
            </div>
            <div v-for="i in projects" :key="i.id" class="w-full mx-auto relative" v-else>
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
                    <h2
                        class="font-semibold text-lg m-1 tracking-tight w-10/12 text-gray-800"
                    >Account balance</h2>
                </div>
                <svg
                    class="animate-spin mx-auto my-1"
                    style="width:4rem;height:4rem"
                    viewBox="0 0 24 24"
                    v-if="loading"
                >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                </svg>
                <p
                    class="text-black font-light text-4xl m-1 text-center"
                    v-else
                >${{ userInfo.balance }}</p>
            </div>
            <div class="rounded shadow-md m-2 p-2 flex flex-col justify-around bg-white">
                <div class="flex content-center flex-col sm:flex-row">
                    <svg style="width:24px;" viewBox="0 0 24 24">
                        <path
                            d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"
                        />
                    </svg>
                    <h2
                        class="font-semibold text-lg m-1 tracking-tight text-gray-800"
                    >Reviewable results</h2>
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
import loader from '../components/loader.vue'
import axios from 'axios'

export default {
    name: 'Home',
    components: {
        projectListItem,
        tabellaWorker,
        modalEliminazione,
        modalUpload,
        loader,
    },
    data() {
        return {
            projects: [],
            modalId: '',
            userInfo: [],
            modalElim: false,
            modalStd: false,
            modalGld: false,
            loading: true,
        }
    },

    created() {
        //this.getProjects()
        //this.getUserInfo()
        this.getData()
    },
    methods: {
        // getProjects() {
        //     axios({
        //         url: this.APIURL + '?action=listProjects',
        //         method: 'get',
        //     }).then(res => {
        //         this.projects = res.data.values
        //     })
        // },
        getData() {
            axios
                .all([
                    axios.get(this.APIURL + '?action=listProjects'),
                    axios.get(this.APIURL + '?action=getUserInfo'),
                ])
                .then(
                    axios.spread((...responses) => {
                        this.projects = responses[0].data.values
                        this.userInfo = responses[1].data.data
                        this.$emit('sandbox', this.userInfo.use_sandbox)
                        console.log(responses[0], responses[1])
                        this.loading = false
                    })
                )
                .catch(errors => {
                    console.log(errors)
                })
        },
        // getUserInfo() {
        //     axios({
        //         url: this.APIURL + '?action=getUserInfo',
        //         method: 'get',
        //     }).then(res => {
        //         this.userInfo = res.data.data
        //         this.$emit('sandbox', this.userInfo.use_sandbox)
        //     })
        // },
        emitSnackbar(arr) {
            this.$emit('snackbar', arr[0])
            this.toggleModal(['elim', arr[1]])
        },
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

            if (arr[0] == 'elim' && this.modalId != '' && arr[2] != 'first') {
                var self = this
                this.projects = this.projects.filter(function(el) {
                    return el.id != self.modalId
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
