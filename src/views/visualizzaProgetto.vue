<template>
    <div class="relative lg:w-5/6 px-8 pt-6 pb-8 flex flex-col mt-4 mx-auto">
        <modalEliminazione
            v-if="modalElim && !loading1"
            @deleteModal="deleteModal"
            @deleted="deleted"
            :id="datiProgetto.id"
        />
        <modalUpload
            v-if="modalStd && !loading1"
            :type="'std'"
            :id="datiProgetto.id"
            @uploadModal="uploadModal"
            @uploaded="uploaded"
        />
        <modalUpload
            v-if="modalGld && !loading1"
            :type="'gld'"
            :id="datiProgetto.id"
            @uploadModal="uploadModal"
            @uploaded="uploaded"
        />
        <modalHIT v-if="modalHIT && !loading1" :id="datiProgetto.id" @uploadModal="uploadModal" />
        <div class="flex justify-between flex-wrap" v-if="!loading1">
            <h1 class="text-2xl mb-4 text-primary">{{ datiProgetto.nome }}</h1>
            <div class="flex relative">
                <button
                    type="submit"
                    class="hidden sm:inline-flex flex-row items-center py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:bg-primary hover:text-white mr-2 focus:outline-none"
                >
                    <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                        <path d="M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z" />
                    </svg>
                    <span class="ml-1">Publish</span>
                </button>
                <div class="relative hidden sm:block">
                    <button
                        @click="open('results')"
                        type="submit"
                        class="hidden sm:inline-flex flex-row items-center  py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:text-white hover:bg-primary focus:outline-none h-full"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"
                            />
                        </svg>
                        <span class="ml-1">Results</span>
                    </button>
                    <span class="flex h-3 w-3 absolute top-0 right-0 -mt-1 -mr-1">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"
                        ></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-pink-500"></span>
                    </span>
                </div>
                <span v-click-outside="hide" class="flex align-center">
                    <button
                        @click="dropdownOpen = !dropdownOpen"
                        class="py-2 px-2 bg-transparent rounded-md  border-2 ml-2 border-solid border-primary hover:bg-primary hover:text-white focus:outline-none"
                    >
                        <svg
                            class="transition duration-300 ease-in-out fill-current "
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
                    </button>
                    <transition name="slide-toggle">
                        <div
                            v-show="dropdownOpen"
                            class="absolute bottom-1 right-0 mt-16 w-56 bg-white rounded-md shadow-xl z-20"
                        >
                            <a
                                class="block sm:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary rounded-t-md hover:text-gray-100"
                                >Publish</a
                            >
                            <router-link
                                to="results"
                                class="block sm:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary hover:text-gray-100"
                                >Results</router-link
                            >
                            <a
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary rounded-t-md hover:text-gray-100"
                                @click="deleteModal()"
                                >Delete</a
                            >
                            <router-link
                                :to="{
                                    name: 'edit',
                                    params: { projectId: datiProgetto.id },
                                }"
                                class="block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary hover:text-gray-100"
                                >Edit</router-link
                            >
                            <a
                                @click="uploadModal(['std'])"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary hover:text-gray-100"
                                >Base CSV upload</a
                            >
                            <a
                                @click="uploadModal(['gld'])"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary hover:text-gray-100"
                                >Gold CSV upload</a
                            >
                            <a
                                @click="uploadModal(['hit'])"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary rounded-b-md hover:text-gray-100"
                                >Load HITs</a
                            >
                        </div>
                    </transition>
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 mt-2">
            <div class="mx-0 sm:mx-2">
                <div class="w-full flex flex-col justify-center" v-if="loading1">
                    <loader :type="'cardInfoVisualizza'" v-for="n in 3" :key="n" />
                </div>
                <div v-else>
                    <cardInfo :titoli="titoliCard.titoli1" :dati="datiCard.dati1" />
                    <cardInfo :titoli="titoliCard.titoli2" :dati="datiCard.dati2" />
                    <cardInfo :titoli="titoliCard.titoli3" :dati="datiCard.dati3" />
                </div>
            </div>
            <div class="mx-0 sm:mx-2">
                <div class="w-full flex flex-col justify-center" v-if="loading1">
                    <loader :type="'cardInfoVisualizza'" />
                    <loader :type="'cardAnalyticsVisualizza'" :num="3" />
                    <loader :type="'cardAnalyticsVisualizza'" :num="2" />
                </div>
                <div v-else>
                    <cardInfo :titoli="titoliCard.titoli4" :dati="datiCard.dati4" />
                    <cardAnalytics :dati="datiCardAnalytics.cardHIT" />
                    <cardAnalytics :dati="datiCardAnalytics.cardAggregate" />
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
import modalUpload from '../components/modalUpload.vue'
import loader from '../components/loader.vue'
import modalHIT from '../components/modalHIT.vue'
import axios from 'axios'

export default {
    name: 'visualizzaProgetto',
    directives: {
        ClickOutside,
    },
    components: {
        modalEliminazione,
        cardInfo,
        cardAnalytics,
        modalUpload,
        loader,
        modalHIT,
    },
    data() {
        return {
            datiProgetto: {
                nome: '',
                titolo: '',
                descrizione: '',
                keywords: '',
                ricompensa: '',
                creazione: '',
                numLavoratori: 0,
                tempoMax: 0,
                scadenza: 0,
                autoApproval: 0,
                layoutID: '',
                stato: 'In corso',
                parametri: 0,
                totaleHIT: 400,
                HITcompletate: 200,
                HITdisponibili: 90,
                HITinCorso: 110,
                risposteSI: 180,
                risposteNO: 20,
                completateProgress: 0,
                disponibiliProgress: 0,
                incorsoProgress: 0,
                siProgress: 0,
                noProgress: 0,
                id: '',
                baseCsv: "<span class='text-red-600'>not uploaded</span>",
                goldCsv: "<span class='text-red-600'>not uploaded</span>",
            },
            titoliCard: {
                titoli1: [],
                titoli2: [],
                titoli3: [],
            },
            datiCard: {
                dati1: [],
                dati2: [],
                dati3: [],
            },
            datiCardAnalytics: {},
            dropdownOpen: false,
            modalElim: false,
            modalStd: false,
            modalGld: false,
            modalHIT: false,
            loading1: true,
            loading: true,
        }
    },
    created() {
        this.getDatiPrj()
    },
    mounted() {
        this.popupItem = this.$el
    },
    methods: {
        // emitSnackbar(arr) {
        //     if (arr[3] == 'std') {
        //         this.toggleModal('std')
        //     } else if (arr[3] == 'gld') {
        //         this.toggleModal('gld')
        //     }
        //     this.$emit('snackbar', arr)
        // },

        getDatiPrj() {
            this.datiProgetto.id = this.$route.params.projectId
            axios
                .get(this.APIURL + '?action=getProjectInfo&id=' + this.datiProgetto.id)
                .then(res => {
                    //console.log(res)
                    //console.log(responses[1].data.result)
                    //console.log(responses[2].data.result)
                    this.datiProgetto.nome = res.data.values.name
                    this.datiProgetto.titolo = res.data.values.title
                    this.datiProgetto.descrizione = res.data.values.description
                    this.datiProgetto.keywords = res.data.values.keywords
                    this.datiProgetto.ricompensa = res.data.values.reward + '$'
                    this.datiProgetto.tempoMax = parseInt(res.data.values.max_time)
                    this.datiProgetto.creazione = res.data.values.created_at
                    this.datiProgetto.scadenza = parseInt(res.data.values.expiry)
                    this.datiProgetto.autoApproval = parseInt(res.data.values.auto_approve)
                    this.datiProgetto.layoutID = res.data.values.layout_id
                    this.datiProgetto.parametri = res.data.values.params
                    this.datiProgetto.numLavoratori = res.data.values.workers
                    if (res.data.numData > 0) {
                        this.datiProgetto.baseCsv = "<span class='text-green-600'>uploaded</span>"
                    }
                    if (res.data.numGold == 'OK') {
                        this.datiProgetto.goldCsv = "<span class='text-green-600'>uploaded</span>"
                    }
                    this.loading = false
                })
                .catch(err => {
                    console.log(err)
                })
        },
        //metodo per aprire il link dei vari button
        open(mode) {
            if (mode == 'results') {
                this.$router.push({
                    name: 'results',
                    params: { projectId: this.datiProgetto.id },
                })
            } else if (mode == 'edit') {
                this.$router.push({
                    name: 'edit',
                    params: { projectId: this.datiProgetto.id },
                })
            }
        },
        //metodo che imposta i titoli e i dati da inserire nelle card della pagina
        impostaDatiCard() {
            this.titoliCard.titoli1 = ['Titolo', 'Descrizione', 'Keywords', 'Data creazione', 'Stato']
            this.datiCard.dati1 = [
                this.datiProgetto.titolo,
                this.datiProgetto.descrizione,
                this.datiProgetto.keywords,
                this.datiProgetto.creazione,
                this.datiProgetto.stato,
            ]
            this.titoliCard.titoli2 = [
                'Ricompensa per ogni assignment',
                'Numero di lavoratori/assignment per task',
                'Tempo massimo',
                'Scadenza',
                'Auto approva e paga lavoratori in',
            ]
            this.datiCard.dati2 = [
                this.datiProgetto.ricompensa,
                this.datiProgetto.numLavoratori,
                this.datiProgetto.tempoMax,
                this.datiProgetto.scadenza,
                this.datiProgetto.autoApproval,
            ]
            this.titoliCard.titoli3 = ['Layout ID', 'Number of records per HIT']
            this.datiCard.dati3 = [this.datiProgetto.layoutID, this.datiProgetto.parametri]
            this.titoliCard.titoli4 = ['Base CSV status', 'Gold CSV status']
            this.datiCard.dati4 = [this.datiProgetto.baseCsv, this.datiProgetto.goldCsv]
            this.datiCardAnalytics = {
                cardHIT: {
                    idPrj: this.datiProgetto.id,
                    titolo: 'Totale HIT',
                    totale: this.datiProgetto.totaleHIT,
                    type: 'HIT',
                    ellipse_progress: {
                        progress1: {
                            progress: this.datiProgetto.completateProgress,
                            legend_value: this.datiProgetto.HITcompletate,
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'Completate',
                        },
                        progress2: {
                            progress: this.datiProgetto.disponibiliProgress,
                            legend_value: this.datiProgetto.HITdisponibili,
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'Disponibili',
                        },
                        progress3: {
                            progress: this.datiProgetto.incorsoProgress,
                            legend_value: this.datiProgetto.HITinCorso,
                            color: '#f6ad55',
                            half: true,
                            angle: 0,
                            caption: 'In corso',
                        },
                    },
                },
                cardAggregate: {
                    titolo: 'Risposte aggregate',
                    totale: this.datiProgetto.HITcompletate,
                    type: 'aggregate',
                    ellipse_progress: {
                        progress1: {
                            progress: this.datiProgetto.siProgress,
                            legend_value: this.datiProgetto.risposteSI,
                            color: '#f6ad55',
                            half: false,
                            angle: 0,
                            caption: 'SI',
                        },
                        progress2: {
                            progress: this.datiProgetto.noProgress,
                            legend_value: this.datiProgetto.risposteNO,
                            color: '#f6ad55',
                            half: false,
                            angle: 0,
                            caption: 'NO',
                        },
                    },
                },
            }
        },
        deleteModal() {
            this.modalElim = !this.modalElim
        },
        deleted() {
            this.$router.push('/')
        },
        //metodo che mostra o nasconde il dialog
        uploadModal(type) {
            if (type[0] == 'std') {
                this.modalStd = !this.modalStd
            } else if (type[0] == 'gld') {
                this.modalGld = !this.modalGld
            } else {
                this.modalHIT = !this.modalHIT
            }
            this.hide()
        },
        uploaded(msg) {
            this.$emit('snackbar', msg)
        },
        //nasconde il dropdown
        hide() {
            if (this.dropdownOpen) {
                this.dropdownOpen = false
            }
        },
        //calcola il numero da utilizzare nei grafici delle analytics
        calcolaProgress() {
            this.datiProgetto.completateProgress = (100 * this.datiProgetto.HITcompletate) / this.datiProgetto.totaleHIT
            this.datiProgetto.disponibiliProgress =
                (100 * this.datiProgetto.HITdisponibili) / this.datiProgetto.totaleHIT
            this.datiProgetto.incorsoProgress = (100 * this.datiProgetto.HITinCorso) / this.datiProgetto.totaleHIT
            this.datiProgetto.siProgress = (100 * this.datiProgetto.risposteSI) / this.datiProgetto.HITcompletate
            this.datiProgetto.noProgress = (100 * this.datiProgetto.risposteNO) / this.datiProgetto.HITcompletate
        },
        elaboraTempo(nomeVar) {
            if (nomeVar == 'tempoMax') {
                if (this.datiProgetto.tempoMax < 60) {
                    this.datiProgetto.tempoMax += ' minuti'
                } else if (this.datiProgetto.tempoMax < 1440) {
                    this.datiProgetto.tempoMax = this.datiProgetto.tempoMax / 60 + ' ore'
                } else {
                    this.datiProgetto.tempoMax = this.datiProgetto.tempoMax / 1440 + ' giorni'
                }
            } else if (nomeVar == 'scadenza') {
                if (this.datiProgetto.scadenza < 60) {
                    this.datiProgetto.scadenza += ' minuti'
                } else if (this.datiProgetto.scadenza < 1440) {
                    this.datiProgetto.scadenza = this.datiProgetto.scadenza / 60 + ' ore'
                } else {
                    this.datiProgetto.scadenza = this.datiProgetto.scadenza / 1440 + ' giorni'
                }
            } else {
                if (this.datiProgetto.autoApproval < 60) {
                    this.datiProgetto.autoApproval += ' minuti'
                } else if (this.datiProgetto.autoApproval < 1440) {
                    this.datiProgetto.autoApproval = this.datiProgetto.autoApproval / 60 + ' ore'
                } else {
                    this.datiProgetto.autoApproval = this.datiProgetto.autoApproval / 1440 + ' giorni'
                }
            }
        },
    },
    watch: {
        loading() {
            if (!this.loading) {
                this.elaboraTempo('tempoMax')
                this.elaboraTempo('scadenza')
                this.elaboraTempo('autoApproval')
                this.calcolaProgress()
                this.impostaDatiCard()
                this.loading1 = false
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
