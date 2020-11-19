<template>
    <div class="home mt-4 mx-2 xs2:mx-4 lg:mx-auto lg:w-5/6 pt-2 pb-6">
        <modalEliminazione v-if="modalElim" @deleteModal="toggleModal" @deleted="deleted" :id="modalId" />
        <modalUpload v-if="modalStd" @uploadModal="toggleModal" @uploaded="toggleModal" :type="'std'" :id="modalId" />
        <modalUpload v-if="modalGld" @uploadModal="toggleModal" @uploaded="toggleModal" :type="'gld'" :id="modalId" />
        <modalLaunch
            v-if="modalLaunch"
            @launchModal="toggleModal(['launch', ''])"
            :id="modalId"
            :hitsSubmitted="hitsSubmitted"
            :hitsTotal="hitsTotal"
            @err="uploaded"
            @launched="launched"
            :priceData="priceData"
            :qualifications="qualifications"
            @changeQualification="changeQualification"
        />
        <!--sistemare per i due valori del csv-->
        <modalHit
            v-if="modalHIT"
            :id="modalId"
            @hitModal="toggleModal"
            @hitCreated="reloadAfterHIT"
            :baseDataStatus="dataPresent"
            :goldDataStatus="gldPresent"
            :params="params"
        />
        <modalLayout
            v-if="modalLayout"
            :project="project[0]"
            @layoutModal="toggleModal"
            @layoutSet="launched"
            @snackbar="uploaded"
        />
        <div class="mb-4 flex flex-col sm:flex-row content-center items-center">
            <span class="text-3xl sm:text-4xl font-light">Welcome, {{ $store.state.userInfo.common_name }}</span>
            <button
                class="ripple transition ease-out duration-100 bg-red-600 hover:bg-red-400 flex flex-row items-center py-2 px-4 rounded-md text-white hover:text-black focus:outline-none ml-4"
                type="button"
                @click="logout"
            >
                <svg
                    :class="loadLogout ? 'animate-spin mr-1 fill-current' : 'hidden'"
                    style="width: 24px; height: 24px"
                    viewBox="0 0 24 24"
                >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                </svg>
                Logout
            </button>
        </div>
        <!--<modalRevert v-if="modalRevert" :id="modalId" @uploadModal="uploadModal" />-->
        <div class="mb-6">
            <div class="flex content-center flex-col sm:flex-row px-4">
                <svg style="width: 24px" viewBox="0 0 24 24">
                    <path
                        d="M17,17H15V13H17M13,17H11V7H13M9,17H7V10H9M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3Z"
                    />
                </svg>
                <h2 class="text-lg font-semibold ml-1 text-gray-800">Projects</h2>
            </div>
            <div class="my-1 mx-2 p-2 flex items-center flex-wrap" v-if="projects.length != 0">
                <div class="hidden w-1/2 lg:w-3/5 xl:w-2/3 md:flex text-center items-center flex-wrap text-lg">
                    <span class="w-1/2 lg:w-1/4 font-light">Project Id</span>
                    <span class="w-1/2 lg:w-1/4 font-light">Title</span>
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
                        class="rounded-md border-4 border-dashed border-gray-600 my-2 mx-2 px-2 py-10 flex items-center flex-wrap relative justify-center"
                    >
                        <div
                            class="flex mx-2 mb-2 flex-col text-xl contenutoPrj w-full font-bold text-center items-center flex-wrap justify-center"
                        >
                            <svg style="width: 40px; height: 40px" viewBox="0 0 24 24">
                                <path
                                    d="M11.46 10.88L12.88 9.46L15 11.59L17.12 9.46L18.54 10.88L16.41 13L18.54 15.12L17.12 16.54L15 14.41L12.88 16.54L11.46 15.12L13.59 13L11.46 10.88M22 8V18C22 19.11 21.11 20 20 20H4C2.9 20 2 19.11 2 18V6C2 4.89 2.9 4 4 4H10L12 6H20C21.11 6 22 6.9 22 8M20 8H4V18H20V8Z"
                                />
                            </svg>
                            No projects
                        </div>
                        <div class="font-light mx-2 text-center">
                            Your projects will be shown here. You can create a new project
                            <router-link to="create" class="font-normal text-primary hover:underline">here</router-link>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div v-for="i in projects" :key="i.id" class="w-full relative">
                        <projectListItem
                            :projectData="i"
                            @deleteThis="toggleModal"
                            @upload="toggleModal"
                            @createHit="toggleModal"
                            @layoutModal="toggleModal"
                            @launchModal="toggleModal"
                            @snackbar="uploaded"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 justify-center">
            <!--
            <div class="rounded shadow-md m-2 p-4 col-span-3 row-span-3 bg-white">
                <div class="flex content-center flex-col sm:flex-row">
                    <svg style="width:24px;" viewBox="0 0 24 24">
                        <path
                            d="M18,19H6V17.6C6,15.6 10,14.5 12,14.5C14,14.5 18,15.6 18,17.6M12,7A3,3 0 0,1 15,10A3,3 0 0,1 12,13A3,3 0 0,1 9,10A3,3 0 0,1 12,7M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z"
                        />
                    </svg>
                    <h2 class="text-lg font-semibold m-1  text-gray-800">Workers</h2>
                </div>
                <tabellaWorker />
            </div>
            -->
            <div class="rounded shadow-md m-2 p-2 flex flex-col justify-around bg-white">
                <div class="flex content-center flex-col sm:flex-row">
                    <svg style="width: 24px" viewBox="0 0 24 24">
                        <path
                            d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z"
                        />
                    </svg>
                    <h2 class="font-semibold text-lg m-1 w-10/12 text-gray-800">Account balance</h2>
                </div>
                <svg
                    class="animate-spin mx-auto my-1"
                    style="width: 4rem; height: 4rem"
                    viewBox="0 0 24 24"
                    v-if="loadingOther"
                >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                </svg>
                <p class="text-black font-light text-2xl xs2:text-4xl m-1 text-center overflow-ellipsis" v-else>
                    ${{ $store.state.userInfo.balance }}
                </p>
            </div>
            <div class="rounded shadow-md m-2 p-2 flex flex-col justify-around bg-white">
                <div class="flex content-center flex-col sm:flex-row">
                    <svg style="width: 24px" viewBox="0 0 24 24">
                        <path
                            d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"
                        />
                    </svg>
                    <h2 class="font-semibold text-lg m-1 text-gray-800">Reviewable HITs</h2>
                </div>
                <p class="text-black font-light text-2xl xs2:text-4xl m-1 text-center">93</p>
            </div>
        </div>
    </div>
</template>

<script>
import projectListItem from '../components/projectListItem.vue'
//import tabellaWorker from '../components/tabellaWorker.vue'
import modalEliminazione from '../components/modalEliminazione.vue'
import modalUpload from '../components/modalUpload.vue'
import modalHit from '../components/modalHIT.vue'
import modalLayout from '../components/modalLayout.vue'
import loader from '../components/loader.vue'
import modalLaunch from '../components/modalLaunch.vue'

export default {
    name: 'Home',
    components: {
        projectListItem,
        //tabellaWorker,
        modalEliminazione,
        modalUpload,
        modalHit,
        modalLayout,
        modalLaunch,
        loader,
    },
    data() {
        return {
            projects: [],
            modalId: '',
            modalElim: false,
            modalStd: false,
            modalGld: false,
            modalHIT: false,
            modalRevert: false,
            modalLayout: false,
            modalLaunch: false,
            loading: true,
            loadLogout: false,
            loadingProjects: true,
            loadingOther: true,
            gldPresent: 0,
            dataPresent: 0,
            project: null,
            isError: /\bError\b/,
            hitsSubmitted: 0,
            hitsTotal: 0,
            params: 0,
            priceData: {},
            qualifications: {},
        }
    },
    created() {
        this.getData()
    },
    methods: {
        logout() {
            this.loadLogout = true
            this.API.get('?action=logout')
                .then(() => {
                    this.$router.replace({ path: '/login' })
                    this.loadLogout = false
                })
                .catch((err) => {
                    console.error(err)
                })
        },
        getPrjData() {
            this.API.get('?action=listProjects')
                .then((res) => {
                    this.projects = res.data.values
                    this.loadingProjects = false
                })
                .catch((err) => {
                    console.log(err)
                })
        },
        getData() {
            this.API.get('?action=listProjects')
                .then((res) => {
                    if (res.data.result == 'ERR') {
                        res.data.error.includes('User')
                            ? this.$emit('snackbar', 'Error. ' + res.data.error + '. Refresh to log in.')
                            : this.$emit('snackbar', 'Error. ' + res.data.error)
                    } else {
                        this.projects = res.data.values
                        this.loadingProjects = false
                        this.loadingOther = false
                    }
                })
                .catch((errors) => {
                    console.log(errors)
                })
        },
        getProject(id) {
            this.project = this.projects.filter(function (project) {
                return project.id == id
            })
            console.log(this.project)
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
        deleted(msg) {
            var self = this
            this.projects = this.projects.filter(function (el) {
                return el.id != self.modalId
            })
            this.$emit('snackbar', msg)
        },
        toggleModal(arr) {
            console.log(arr)
            this.modalId = arr[1]
            if (arr[0] == 'std') {
                this.modalStd = !this.modalStd
                if (arr[2] != 'noSnack') {
                    if (!this.isError.test(arr[2])) {
                        this.modalStd = false
                        this.reloadAfterHIT(arr[2])
                    } else {
                        this.uploaded(arr[2])
                    }
                }
            } else if (arr[0] == 'gld') {
                this.modalGld = !this.modalGld
                if (arr[2] != 'noSnack') {
                    if (!this.isError.test(arr[2])) {
                        this.modalGld = false
                        this.reloadAfterHIT(arr[2])
                    } else {
                        this.uploaded(arr[2])
                    }
                }
            } else if (arr[0] == 'delete') {
                this.modalElim = !this.modalElim
            } else if (arr[0] == 'layout') {
                this.modalLayout = !this.modalLayout
            } else if (arr[0] == 'launch') {
                if (arr[1] != '') {
                    var self = this
                    var launch = this.projects.find(function (el) {
                        return el.id == self.modalId
                    })
                    this.hitsSubmitted = parseInt(launch.hits_submitted)
                    this.hitsTotal = parseInt(launch.hits_total)
                    this.qualifications.master = 0
                    if ((launch.status == 2 || launch.status == 3) && this.hitsTotal > this.hitsSubmitted) {
                        this.priceData.reward = parseFloat(launch.reward)
                        this.priceData.assignment = parseInt(launch.workers)
                    }
                    console.log(launch)
                    if (this.hitsTotal == this.hitsSubmitted) {
                        this.$emit('snackbar', 'All HITs have already been submitted', '')
                    } else {
                        this.modalLaunch = !this.modalLaunch
                    }
                } else {
                    this.modalLaunch = !this.modalLaunch
                }
            } else {
                this.modalHIT = !this.modalHIT
                this.modalId = arr[0]
                if (arr[1] != undefined) {
                    this.dataPresent = parseInt(arr[1])
                    this.gldPresent = parseInt(arr[2])
                    this.params = parseInt(arr[3])
                }
            }
            if (this.modalLayout) {
                this.getProject(arr[1])
            }
        },
        launched(msg) {
            this.uploaded(msg)
            this.loadingProjects = true
            this.getPrjData()
        },
        reloadAfterHIT(msg) {
            this.uploaded(msg)
            this.loadingProjects = true
            this.getPrjData()
        },
        uploaded(msg) {
            this.$emit('snackbar', msg)
        },
        changeQualification(value) {
            this.qualifications.master = value
        },
    },
}
</script>

<style scoped>
path {
    fill: rgba(45, 55, 72, 1);
}
</style>
