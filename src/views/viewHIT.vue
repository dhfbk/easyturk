<template>
    <div v-if="loading"></div>
    <div v-else class="relative lg:w-5/6 pt-2 pb-8 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
        <modalEliminazione v-if="modal" @hideModal="toggleModal" />
        <div class="flex justify-between flex-wrap items-center">
            <h1 class="text-2xl text-primary">HIT {{ datiProgetto.hitID }}</h1>
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
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 mt-2">
            <div class="mr-0 xs2:mr-1">
                <cardInfo :projectData="project" :mode="'general'" />
                <cardInfo :projectData="project" :mode="'layout'" />
            </div>
            <div class="ml-0 xs2:ml-1">
                <cardInfo :projectData="project" :mode="'payment'" />
                <cardAnalytics :dati="datiCardAnalytics.cardHIT" />
            </div>
        </div>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import modalEliminazione from '../components/modalEliminazione.vue'
import cardInfo from '../components/cardInfo.vue'
import cardAnalytics from '../components/cardAnalyticsVisualizzaProgetto.vue'
import axios from 'axios'

export default {
    name: 'viewHIT',
    directives: {
        ClickOutside,
    },
    components: {
        modalEliminazione,
        cardInfo,
        cardAnalytics,
    },
    data() {
        return {
            prjData: {},
            project: {},
            datiProgetto: {
                titolo: "Italy's food",
                descrizione:
                    "If you know Italian food, we ask you to take a look at various different serving pictures and tell us if you think it's italian or not.",
                keywords: 'food, italy, image, recognition, culture',
                stato: 'Assignable',
                ricompensa: '0.25',
                creazione: '2020-09-02',
                numLavoratori: 5,
                tempoMax: 60,
                scadenza: '2020-09-10',
                autoApproval: 86400,
                layoutID: 'hdf7777cv4cc34c',
                hitID: 'dh354548435vv',
                HITTypeId: 'Questionario',
                HITGroupId: 'didfud786543',
                //parametri: 3,
                totaleAssignment: 6,
                assignmentCompletati: 2,
                assignmentDisponibili: 3,
                assignmentInCorso: 1,
                //risposteSI: 180,
                //risposteNO: 20,
                completateProgress: 0,
                disponibiliProgress: 0,
                incorsoProgress: 0,
                //siProgress: 0,
                //noProgress: 0,
            },
            datiCardAnalytics: {},
            dropdownOpen: false,
            modal: false,
            loading: true,
        }
    },
    created() {
        this.getData()
        this.elaboraTempo(this.datiProgetto.tempoMax)
        this.elaboraTempo(this.datiProgetto.autoApproval)
        this.calcolaProgress()
        this.impostaDatiCard()
    },
    mounted() {
        this.popupItem = this.$el
    },
    methods: {
        getData() {
            axios({
                method: 'get',
                url: this.APIURL + '?action=getHitInfo&hitID=' + this.$route.params.hitId,
            })
                .then(res => {
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
                    this.loading = false
                })
                .catch(err => {
                    console.log(err)
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
        impostaDatiCard() {
            this.datiCardAnalytics = {
                cardHIT: {
                    titolo: 'Totale assignment',
                    totale: this.datiProgetto.totaleAssignment,
                    type: 'assignment',
                    ellipse_progress: {
                        progress1: {
                            progress: this.datiProgetto.completateProgress,
                            legend_value: this.datiProgetto.assignmentCompletati,
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'Completati',
                        },
                        progress2: {
                            progress: this.datiProgetto.disponibiliProgress,
                            legend_value: this.datiProgetto.assignmentDisponibili,
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'Disponibili',
                        },
                        progress3: {
                            progress: this.datiProgetto.incorsoProgress,
                            legend_value: this.datiProgetto.assignmentInCorso,
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'In corso',
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
        calcolaProgress() {
            this.datiProgetto.completateProgress =
                (100 * this.datiProgetto.assignmentCompletati) / this.datiProgetto.totaleAssignment
            this.datiProgetto.disponibiliProgress =
                (100 * this.datiProgetto.assignmentDisponibili) / this.datiProgetto.totaleAssignment
            this.datiProgetto.incorsoProgress =
                (100 * this.datiProgetto.assignmentInCorso) / this.datiProgetto.totaleAssignment
        },
        //modifica il tempo da secondi a minuti/ore/giorni
        elaboraTempo(tempo) {
            var tmp = 0
            if (tempo < 60) {
                tmp = Math.floor(tempo) + ' secondi'
            } else if (tempo < 3600) {
                tmp = Math.floor(tempo / 60) + ' minuti'
            } else if (tempo < 86400) {
                tmp = Math.floor(tempo / 3600) + ' ore'
            } else {
                tmp = Math.floor(tempo / 86400) + ' giorni'
            }
            if (tempo == this.datiProgetto.tempoMax) {
                this.datiProgetto.tempoMax = tmp
            } else {
                this.datiProgetto.autoApproval = tmp
            }
        },
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
