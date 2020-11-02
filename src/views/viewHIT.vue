<template>
    <div v-if="loading"></div>
    <div v-else class="relative lg:w-5/6 pt-2 pb-8 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
        <modalEliminazione v-if="modal" @hideModal="toggleModal" />
        <div class="flex justify-between flex-wrap">
            <h1 class="text-2xl mb-4 text-primary">HIT {{ datiProgetto.hitID }}</h1>
            <div class="flex relative">
                <button
                    type="submit"
                    class="hidden sm:inline-flex flex-row items-center py-2 px-4 bg-transparent rounded-md transition duration-100 ease-out border-2 border-solid border-primary hover:bg-primary mr-2 focus:outline-none"
                >
                    <svg style="width:24px;" viewBox="0 0 24 24">
                        <path
                            d="M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M13,7H11V11H7V13H11V17H13V13H17V11H13V7Z"
                        />
                    </svg>
                    <span class="ml-1">Add assignment</span>
                </button>
                <button
                    @click="dropdownOpen = !dropdownOpen"
                    v-click-outside="hide"
                    class="py-2 px-2 bg-transparent rounded-md transition duration-100 ease-out border-2 border-solid border-primary hover:bg-primary focus:outline-none"
                >
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" v-if="!dropdownOpen">
                        <path fill="currentColor" d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                    </svg>
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24" v-else>
                        <path fill="currentColor" d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z" />
                    </svg>
                </button>
                <transition name="slide-toggle">
                    <div
                        v-show="dropdownOpen"
                        class="absolute bottom-1 right-0 mt-16 w-56 bg-white rounded-md shadow-xl z-20"
                    >
                        <router-link
                            to="results"
                            class="block sm:hidden px-4 py-2 text-sm capitalize text-gray-700 transition duration-100 ease-out hover:bg-primary rounded-t-md"
                        >
                            Add assignment
                        </router-link>
                        <a
                            class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-100 ease-out hover:bg-primary rounded-b-md sm:rounded-md"
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
                    var expiration = this.timeDifference(new Date(res.data.values.hit_info.Expiration), new Date())
                    expiration < 0 ? (expiration = 0) : ''
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
        timeDifference(timestamp1, timestamp2) {
            var difference = timestamp1 - timestamp2
            var daysDifference = Math.floor(difference / 1000 / 60 / 60 / 24)

            return daysDifference
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
