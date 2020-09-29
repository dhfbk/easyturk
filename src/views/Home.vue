<template>
    <div class="home mx-8 sm:mt-2 md:mx-16 pb-6">
        <p class="text-3xl sm:text-5xl font-light mb-4">Welcome, {{ userInfo.common_name }}</p>
        <modalEliminazione
            v-if="modalElim"
            @deleteModal="deleteModal"
            @deleted="deleted"
            :id="modalId"
        />
        <modalUpload
            v-if="modalStd"
            @uploaded="uploaded"
            @uploadModal="uploadModal"
            :type="'std'"
            :id="modalId"
        />
        <modalUpload
            v-if="modalGld"
            @uploaded="uploaded"
            @uploadModal="uploadModal"
            :type="'gld'"
            :id="modalId"
        />
        <!--sistemare per i due valori del csv-->
        <modalHit
            v-if="modalHIT"
            :id="modalId"
            @hitModal="hitModal"
            @hitCreated="reloadAfterHIT"
            :baseDataStatus="dataPresent"
            :goldDataStatus="gldPresent"
        />
        <!--<modalRevert v-if="modalRevert" :id="modalId" @uploadModal="uploadModal" />-->
        <div class="mb-6">
            <div class="flex content-center flex-col sm:flex-row px-4">
                <svg style="width:24px;" viewBox="0 0 24 24">
                    <path
                        d="M17,17H15V13H17M13,17H11V7H13M9,17H7V10H9M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z"
                    />
                </svg>
                <h2 class="text-lg font-semibold ml-1 tracking-tight text-gray-800">Projects</h2>
            </div>
            <div class="my-1 mx-2 p-2 flex items-center flex-wrap">
                <div class="hidden w-2/3 md:flex text-center items-center flex-wrap text-lg">
                    <span class="w-1/4 font-light">Project Id</span>
                    <span class="w-1/4 font-light">Title</span>
                    <span class="hidden lg:block w-1/4 font-light">Created</span>
                    <span class="hidden xl:block w-1/4 font-light">Last edited</span>
                </div>
            </div>
            <div class="w-full flex flex-col justify-center" v-if="loadingProjects">
                <loader :type="'homePrj'" v-for="n in 2" :key="n" />
            </div>
            <div v-else>
                <div v-if="projects.length == 0">
                    <div
                        class="rounded border-4 border-dashed shadow-md my-2 mx-2 p-2 flex items-center flex-wrap bg-white relative"
                    >
                        <div
                            class="flex mx-2 contenutoPrj w-full font-bold text-center items-center flex-wrap"
                        >
                            No projects
                            <svg
                                style="width:24px;height:24px"
                                class="ml-2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="currentColor"
                                    d="M11.46 10.88L12.88 9.46L15 11.59L17.12 9.46L18.54 10.88L16.41 13L18.54 15.12L17.12 16.54L15 14.41L12.88 16.54L11.46 15.12L13.59 13L11.46 10.88M22 8V18C22 19.11 21.11 20 20 20H4C2.9 20 2 19.11 2 18V6C2 4.89 2.9 4 4 4H10L12 6H20C21.11 6 22 6.9 22 8M20 8H4V18H20V8Z"
                                />
                            </svg>
                        </div>
                        <div class="font-light mx-2">
                            Your projects will be shown here. You can create a new project
                            <router-link
                                to="create"
                                class="font-normal text-primary hover:underline"
                            >here</router-link>.
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div v-for="i in projects" :key="i.id" class="w-full mx-auto relative">
                        <projectListItem
                            :projectData="i"
                            @deleteThis="deleteModal"
                            @upload="uploadModal"
                            @createHit="hitModal"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div
            class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 justify-center"
        >
            <!--
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
            -->
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
                    v-if="loadingOther"
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
//import tabellaWorker from '../components/tabellaWorker.vue'
import modalEliminazione from '../components/modalEliminazione.vue'
import modalUpload from '../components/modalUpload.vue'
//import modalRevert from '../components/modalRevert.vue'
import modalHit from '../components/modalHIT.vue'
import loader from '../components/loader.vue'
import axios from 'axios'

export default {
    name: 'Home',
    components: {
        projectListItem,
        //tabellaWorker,
        modalEliminazione,
        //modalRevert,
        modalUpload,
        modalHit,
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
            modalHIT: false,
            modalRevert: false,
            loading: true,
            loadingProjects: true,
            loadingOther: true,
            gldPresent: 0,
            dataPresent: 0,
        }
    },

    created() {
        this.getData()
    },
    methods: {
        getPrjData() {
            axios
                .get(this.APIURL + '?action=listProjects')
                .then(res => {
                    this.projects = res.data.values
                    this.loadingProjects = false
                })
                .catch(err => {
                    console.log(err)
                })
        },
        getData() {
            axios
                .all([axios.get(this.APIURL + '?action=listProjects'), axios.get(this.APIURL + '?action=getUserInfo')])
                .then(
                    axios.spread((...responses) => {
                        this.projects = responses[0].data.values
                        this.userInfo = responses[1].data.data
                        this.$emit('sandbox', this.userInfo.use_sandbox)
                        console.log(responses[0], responses[1])
                        this.loadingProjects = false
                        this.loadingOther = false
                        console.log(this.projects)
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
        // emitSnackbar(arr) {
        //     this.$emit('snackbar', arr[0])
        //     this.toggleModal(['elim', arr[1]])
        // },
        //metodi per aprire e chiudere il modal eliminazione ed eliminare l'elemento dalla lista
        deleteModal(id) {
            this.modalId = id
            this.modalElim = !this.modalElim
        },
        deleted(msg) {
            var self = this
            this.projects = this.projects.filter(function(el) {
                return el.id != self.modalId
            })
            this.$emit('snackbar', msg)
        },
        //

        //metodo che mostra o nasconde il dialog di upload
        uploadModal(arr) {
            this.modalId = arr[1]
            if (arr[0] == 'std') {
                this.modalStd = !this.modalStd
            } else if (arr[0] == 'gld') {
                this.modalGld = !this.modalGld
            } // else {
            //     this.modalHIT = !this.modalHIT
            //     if (arr[2] != undefined) {
            //         this.dataPresent = parseInt(arr[2])
            //         this.gldPresent = parseInt(arr[3])
            //     }
            // }
        },
        uploaded(msg) {
            this.$emit('snackbar', msg)
        },
        hitModal(arr) {
            this.modalHIT = !this.modalHIT
            this.modalId = arr[0]
            if (arr[1] != undefined) {
                this.dataPresent = parseInt(arr[1])
                this.gldPresent = parseInt(arr[2])
            }
        },
        reloadAfterHIT(msg) {
            this.uploaded(msg)
            this.loadingProjects = true
            this.getPrjData()
        },
    },
}
</script>

<style scoped>
path {
    fill: rgba(45, 55, 72, 1);
}
</style>
