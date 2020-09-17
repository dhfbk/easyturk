<template>
    <div class="relative lg:w-5/6 px-8 pt-6 pb-8 flex flex-col mt-4 mx-auto">
        <modalEliminazione v-if="modalElim" @toggleModal="toggleModal" />
        <modalUpload v-if="modalStd" @upload="toggleModal" :type="'std'" />
        <modalUpload v-if="modalGld" @upload="toggleModal" :type="'gld'" />
        <div class="flex justify-between flex-wrap">
            <h1 class="text-2xl mb-4 text-primary">{{ datiProgetto.nome }}</h1>
            <div class="flex relative">
                <button
                    type="submit"
                    class="hidden sm:inline-flex flex-row items-center py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:bg-primary mr-2 focus:outline-none"
                >
                    <svg style="width:24px;" viewBox="0 0 24 24">
                        <path d="M9,16V10H5L12,3L19,10H15V16H9M5,20V18H19V20H5Z" />
                    </svg>
                    <span class="ml-1">Pubblica</span>
                </button>
                <div class="relative hidden sm:block">
                    <button
                        @click="open('results')"
                        type="submit"
                        class="hidden sm:inline-flex flex-row items-center py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:bg-primary focus:outline-none h-full"
                    >
                        <svg style="width:24px;" viewBox="0 0 24 24">
                            <path
                                d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"
                            />
                        </svg>
                        <span class="ml-1">Risultati</span>
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
                        class="py-2 px-2 bg-transparent rounded-md transition duration-150 ease-in-out border-2 ml-2 border-solid border-primary hover:bg-primary focus:outline-none"
                    >
                        <svg
                            class="transition duration-300 ease-in-out"
                            :class="{ 'transform  rotate-180': dropdownOpen }"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="black"
                            width="24px"
                            height="24px"
                        >
                            <path
                                fill="currentColor"
                                d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z"
                            />
                        </svg>
                    </button>
                    <transition name="slide-toggle">
                        <div
                            v-show="dropdownOpen"
                            class="absolute bottom-1 right-0 mt-16 w-56 bg-white rounded-md shadow-xl z-20"
                        >
                            <a
                                class="block sm:hidden px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-primary rounded-t-md"
                            >Pubblica</a>
                            <router-link
                                to="results"
                                class="block sm:hidden px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-primary"
                            >Risultati</router-link>
                            <a
                                class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-primary rounded-t-md"
                                @click="toggleModal('elim')"
                            >Elimina</a>
                            <router-link
                                to="edit"
                                class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-primary"
                            >Modifica</router-link>
                            <a
                                @click="toggleModal('std')"
                                class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-primary"
                            >Carica dati</a>
                            <a
                                @click="toggleModal('gld')"
                                class="block px-4 py-2 text-sm capitalize text-gray-700 transition duration-150 ease-in-out hover:bg-primary rounded-b-md"
                            >Carica gold</a>
                        </div>
                    </transition>
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 mt-2">
            <div class="mx-0 sm:mx-2">
                <cardInfo :titoli="titoliCard.titoli1" :dati="datiCard.dati1" />
                <cardInfo :titoli="titoliCard.titoli2" :dati="datiCard.dati2" />
                <cardInfo :titoli="titoliCard.titoli3" :dati="datiCard.dati3" />
            </div>
            <div class="mx-0 sm:mx-2">
                <cardAnalytics :dati="datiCardAnalytics.cardHIT" />
                <cardAnalytics :dati="datiCardAnalytics.cardAggregate" />
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
    },
    data() {
        return {
            datiProgetto: {
                nome: 'nomeProgetto',
                titolo: "Italy's food",
                descrizione:
                    "If you know Italian food, we ask you to take a look at various different serving pictures and tell us if you think it's italian or not.",
                keywords: 'food, italy, image, recognition, culture',
                stato: 'In corso',
                ricompensa: '0.25',
                creazione: '2020-09-02',
                numLavoratori: 5,
                tempoMax: 60,
                scadenza: '2020-09-10',
                autoApproval: 86400,
                layoutID: 'hdf7777cv4cc34c',
                parametri: 3,
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
        }
    },
    created() {
        this.elaboraTempo(this.datiProgetto.tempoMax)
        this.elaboraTempo(this.datiProgetto.autoApproval)
        this.calcolaProgress()
        this.impostaDatiCard()
    },
    mounted() {
        this.popupItem = this.$el
    },
    methods: {
        //metodo per aprire il link dei vari button
        open(mode) {
            if (mode == 'results') {
                this.$router.push({
                    name: 'reviewResults',
                })
            } else if (mode == 'edit') {
                this.$router.push({
                    name: 'modificaProgetto',
                })
            }
        },
        //metodo che imposta i titoli e i dati da inserire nelle card della pagina
        impostaDatiCard() {
            this.titoliCard.titoli1 = [
                'Titolo',
                'Descrizione',
                'Keywords',
                'Data creazione',
                'Stato',
            ]
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
            this.titoliCard.titoli3 = ['Layout ID', 'Numero parametri']
            this.datiCard.dati3 = [this.datiProgetto.layoutID, this.datiProgetto.parametri]
            this.datiCardAnalytics = {
                cardHIT: {
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
        //metodo che mostra o nasconde il dialog
        toggleModal(type) {
            if (type == 'elim') {
                this.modalElim = !this.modalElim
            } else if (type == 'std') {
                this.modalStd = !this.modalStd
            } else {
                this.modalGld = !this.modalGld
            }
            this.hide()
        },
        //nasconde il dropdown
        hide() {
            if (this.dropdownOpen) {
                this.dropdownOpen = false
            }
        },
        //calcola il numero da utilizzare nei grafici delle analytics
        calcolaProgress() {
            this.datiProgetto.completateProgress =
                (100 * this.datiProgetto.HITcompletate) / this.datiProgetto.totaleHIT
            this.datiProgetto.disponibiliProgress =
                (100 * this.datiProgetto.HITdisponibili) / this.datiProgetto.totaleHIT
            this.datiProgetto.incorsoProgress =
                (100 * this.datiProgetto.HITinCorso) / this.datiProgetto.totaleHIT
            this.datiProgetto.siProgress =
                (100 * this.datiProgetto.risposteSI) / this.datiProgetto.HITcompletate
            this.datiProgetto.noProgress =
                (100 * this.datiProgetto.risposteNO) / this.datiProgetto.HITcompletate
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
