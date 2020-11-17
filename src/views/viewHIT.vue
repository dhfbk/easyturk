<template>
    <div class="relative lg:w-5/6 pt-2 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
        <modalEliminazione v-if="modal" @hideModal="toggleModal" />
        <div class="flex justify-between flex-wrap items-center">
            <button
                @click="$router.push({ name: 'HITlist', params: { projectId: $route.params.projectId } })"
                :content="'Back'"
                v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
                class="rounded ripple bg-transparent hover:bg-gray-400 p-2 focus:outline-none"
            >
                <svg class="inline" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                    <path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
                </svg>
                <span class="sr-only">Back to HIT list</span>
            </button>
            <p v-if="!loading" class="text-lg sm:text-xl text-primary mr-auto ml-2 overflow-ellipsis">
                HIT {{ prjData.values.id_hit }}
            </p>
            <!--
            <div class="flex relative">
                <span class="tooltip hidden md:block relative md:mr-2">
                    <button
                        type="submit"
                        class="ripple bg-primary transition duration-100 ease-out hover:bg-blue-600 flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M13,7H11V11H7V13H11V17H13V13H17V11H13V7Z"
                            />
                        </svg>
                        <span class="sr-only">Add assignment</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Add assignment
                    </span>
                </span>
                <span class="tooltip hidden md:block relative" v-if="project.status != 3">
                    <button
                        @click="$router.push({ name: 'edit', params: { projectId: id } })"
                        type="submit"
                        class="ripple transition duration-100 ease-out flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded-md border-2 border-solid border-primary hover:text-white focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
                            />
                        </svg>
                        <span class="sr-only">Edit</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Edit
                    </span>
                </span>
                <button
                    @click="dropdownOpen = !dropdownOpen"
                    v-click-outside="hide"
                    class="md:hidden ripple hover:bg-primary flex flex-row items-center py-2 px-2 bg-transparent rounded-md transition duration-100 ease-out border-2 border-solid border-primary hover:text-white focus:outline-none"
                >
                    <svg
                        class="transition duration-100 ease-out fill-current"
                        :class="{ 'transform  rotate-180': dropdownOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="black"
                        width="24px"
                        height="24px"
                    >
                        <path
                            d="M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z"
                        />
                    </svg>
                    <span class="sr-only">Open dropdown</span>
                </button>
                <transition name="slide-toggle">
                    <div
                        v-show="dropdownOpen"
                        class="absolute bottom-1 right-0 mt-16 w-56 bg-white rounded-md shadow-xl z-20"
                    >
                        <a
                            class="block cursor-pointer md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary rounded-t-md hover:text-gray-100"
                        >
                            Add assignment
                        </a>
                        <a
                            class="cursor-pointer block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-100 ease-out hover:bg-primary hover:text-gray-100"
                            >Edit</a
                        >
                    </div>
                </transition>
            </div>
            -->
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 mt-2">
            <div class="mr-0 xs2:mr-1">
                <div class="w-full flex flex-col justify-center" v-if="loading">
                    <loader :type="'cardInfoVisualizza'" v-for="n in 2" :key="n" />
                </div>
                <div v-else>
                    <cardInfo :projectData="project" :mode="'general'" />
                    <cardInfo :projectData="prjData" :mode="'hitTable'" />
                </div>
            </div>
            <div class="ml-0 xs2:ml-1">
                <div class="w-full flex flex-col justify-center" v-if="loading">
                    <loader :type="'cardInfoVisualizza'" />
                    <loader :type="'cardAnalyticsVisualizza'" :num="3" />
                </div>
                <div v-else>
                    <cardInfo :projectData="project" :mode="'payment'" />
                    <cardAnalytics :dati="analytics.cardHIT" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import modalEliminazione from '../components/modalEliminazione.vue'
import cardInfo from '../components/cardInfo.vue'
import cardAnalytics from '../components/cardAnalyticsVisualizzaProgetto.vue'
import loader from '../components/loader.vue'

export default {
    name: 'viewHIT',
    directives: {
        ClickOutside,
    },
    components: {
        modalEliminazione,
        cardInfo,
        cardAnalytics,
        loader,
    },
    data() {
        return {
            prjData: {},
            project: {},
            progressData: {
                completed: 0,
                available: 0,
                pending: 0,
            },
            analytics: {},
            dropdownOpen: false,
            modal: false,
            loading: true,
        }
    },
    created() {
        this.getData()
    },
    mounted() {
        window.addEventListener('keydown', this.keyboardEvent)
    },
    methods: {
        keyboardEvent(event) {
            if (event.code == 'Escape') {
                this.$router.push({
                    name: 'HITlist',
                    params: { projectId: this.$route.params.projectId },
                })
            }
        },
        getData() {
            this.API.get('?action=getHitInfo&hitID=' + this.$route.params.hitId)
                .then((res) => {
                    console.log(res)
                    this.prjData = res.data
                    var tmpDate = new Date(res.data.values.hit_info.Expiration)
                    var expiration = this.timeConverter(tmpDate.getTime() / 1000)
                    this.project = {
                        description: res.data.values.hit_info.Description,
                        title: res.data.values.hit_info.Title,
                        keywords: res.data.values.hit_info.Keywords,
                        created_at: res.data.values.hit_info.CreationTime,
                        layout_id: res.data.values.hit_info.HITLayoutId,
                        params: res.data.values.hit_info.MaxAssignments, //mettere il params giusto
                        reward: res.data.values.hit_info.Reward,
                        workers: res.data.values.hit_info.MaxAssignments,
                        max_time: res.data.values.hit_info.AssignmentDurationInSeconds / 60, //converti in X
                        expiry: expiration, //convertire da data a X
                        auto_approve: res.data.values.hit_info.AutoApprovalDelayInSeconds / 60, //convertire da secondi a x
                    }
                    this.convertProgress()
                    this.setAnalyticsCard()
                    this.loading = false
                })
                .catch((err) => {
                    console.error(err)
                })
        },
        timeConverter(tmp) {
            var a = new Date(tmp * 1000)
            var year = a.getFullYear()
            var month = a.getMonth() + 1 < 10 ? '0' + (a.getMonth() + 1) : a.getMonth() + 1
            var date = a.getDate() < 10 ? '0' + a.getDate() : a.getDate()
            var time =
                year +
                '-' +
                month +
                '-' +
                date +
                ' ' +
                (a.getHours() - a.getTimezoneOffset() / 60) +
                ':' +
                a.getMinutes()
            return time
        },
        //metodo che imposta i titoli e i dati da inserire nelle card della pagina
        setAnalyticsCard() {
            this.analytics = {
                cardHIT: {
                    titolo: 'Assignments',
                    totale: parseInt(this.prjData.values.hit_info.MaxAssignments),
                    type: 'assignment',
                    ellipse_progress: {
                        progress1: {
                            progress: this.progressData.completed,
                            legend_value: parseInt(this.prjData.values.hit_info.NumberOfAssignmentsCompleted),
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'Completed',
                        },
                        progress2: {
                            progress: this.progressData.available,
                            legend_value: parseInt(this.prjData.values.hit_info.NumberOfAssignmentsAvailable),
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'Available',
                        },
                        progress3: {
                            progress: this.progressData.pending,
                            legend_value: parseInt(this.prjData.values.hit_info.NumberOfAssignmentsPending),
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'Pending',
                        },
                    },
                },
            }
        },
        //metodo che mostra o nasconde il dialog
        toggleModal() {
            this.modal = !this.modal
            this.hide()
        },
        //nasconde il dropdown
        hide() {
            this.dropdownOpen = false
        },
        //calcola il numero da utilizzare nei grafici delle analytics
        convertProgress() {
            this.progressData.completed =
                (100 * parseInt(this.prjData.values.hit_info.NumberOfAssignmentsCompleted)) /
                parseInt(this.prjData.values.hit_info.MaxAssignments)
            this.progressData.available =
                (100 * parseInt(this.prjData.values.hit_info.NumberOfAssignmentsAvailable)) /
                parseInt(this.prjData.values.hit_info.MaxAssignments)
            this.progressData.pending =
                (100 * parseInt(this.prjData.values.hit_info.NumberOfAssignmentsPending)) /
                parseInt(this.prjData.values.hit_info.MaxAssignments)
        },
    },
    watch: {
        modal() {
            if (this.modal) {
                window.removeEventListener('keydown', this.keyboardEvent)
            } else {
                window.addEventListener('keydown', this.keyboardEvent)
            }
        },
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.keyboardEvent)
    },
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.25s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
