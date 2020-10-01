<template>
    <div class="relative lg:w-5/6 px-8 pt-6 pb-8 flex flex-col mt-4 mx-auto">
        <modalEliminazione
            v-if="modalElim && !loading1"
            @deleteModal="toggleModal('delete')"
            @deleted="deleted"
            :id="datiProgetto.id"
        />
        <modalUpload
            v-if="modalStd && !loading1"
            :type="'std'"
            :id="datiProgetto.id"
            @uploadModal="toggleModal('std')"
            @uploaded="uploaded"
        />
        <modalUpload
            v-if="modalGld && !loading1"
            :type="'gld'"
            :id="datiProgetto.id"
            @uploadModal="toggleModal('gld')"
            @uploaded="uploaded"
        />
        <modalHIT
            v-if="modalHIT && !loading1"
            :id="datiProgetto.id"
            :baseDataStatus="datiProgetto.baseCsvStatus"
            :goldDataStatus="datiProgetto.goldCsvStatus"
            @hitModal="toggleModal('hit')"
            @hitCreated="hitCreated"
        />
        <modalRevert
            v-if="modalRevert && !loading1"
            :id="datiProgetto.id"
            @revertModal="toggleModal('revert')"
            @reverted="reverted"
        />
        <modalLayout v-if="modalLayout && !loading1" :id="datiProgetto.id" @layoutModal="toggleModal('layout')" />
        <div class="flex justify-between items-center flex-wrap" v-if="!loading1">
            <h1 class="text-2xl mb-4 text-primary">{{ datiProgetto.nome }}</h1>
            <div class="w-full sm:w-auto flex relative justify-between content-center items-center">
                <span class="tooltip relative">
                    <button
                        v-if="datiProgetto.status == 1"
                        @click="toggleModal('layout')"
                        type="submit"
                        class="ripple hidden bg-primary hover:bg-blue-600 md:flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white mr-2 mb-1 focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12.89,3L14.85,3.4L11.11,21L9.15,20.6L12.89,3M19.59,12L16,8.41V5.58L22.42,12L16,18.41V15.58L19.59,12M1.58,12L8,5.58V8.41L4.41,12L8,15.58V18.41L1.58,12Z"
                            />
                        </svg>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                        >Set layout</span
                    >
                </span>
                <span class="tooltip relative">
                    <button
                        v-if="datiProgetto.status == 0"
                        :class="{ 'cursor-not-allowed': datiProgetto.baseCsvStatus == 0 }"
                        @click="toggleModal('hit')"
                        type="submit"
                        class="ripple hidden bg-primary hover:bg-blue-600 md:flex flex-row items-center py-2 px-4 border-2 border-solid border-primary hover:border-blue-600 bg-transparent rounded-md text-white mr-2 mb-1 focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M17,14H19V17H22V19H19V22H17V19H14V17H17V14M10,2H14A2,2 0 0,1 16,4V6H20A2,2 0 0,1 22,8V13.53C20.94,12.58 19.54,12 18,12A6,6 0 0,0 12,18C12,19.09 12.29,20.12 12.8,21H4C2.89,21 2,20.1 2,19V8C2,6.89 2.89,6 4,6H8V4C8,2.89 8.89,2 10,2M14,6V4H10V6H14Z"
                            />
                        </svg>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                        >Create HITs</span
                    >
                </span>
                <span class="tooltip relative">
                    <button
                        v-if="datiProgetto.status == 1"
                        @click="toggleModal('revert')"
                        type="submit"
                        class="ripple hidden md:flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded-md border-2 border-solid border-primary hover:text-white mr-2 mb-1 focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z"
                            />
                        </svg>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                        >Revert HIT settings</span
                    >
                </span>
                <span class="tooltip relative">
                    <button
                        @click="$router.push({ name: 'edit', params: { projectId: datiProgetto.id } })"
                        type="submit"
                        class="ripple hidden md:flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded-md border-2 border-solid border-primary hover:text-white mr-2 mb-1 focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
                            />
                        </svg>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                        >Edit</span
                    >
                </span>
                <span class="tooltip relative">
                    <button
                        @click="toggleModal('delete')"
                        type="submit"
                        class="ripple hidden md:flex flex-row hover:bg-primary items-center py-2 px-4 bg-transparent rounded-md border-2 border-solid border-primary hover:text-white mr-2 mb-1 focus:outline-none"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                            />
                        </svg>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light"
                        >Delete</span
                    >
                </span>
                <div class="relative hidden md:block" v-if="datiProgetto.status == 3">
                    <button
                        @click="open('results')"
                        type="submit"
                        class="tooltip ripple-outlined hidden md:flex flex-row items-center py-2 px-4 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:text-white focus:outline-none h-full mr-2"
                    >
                        <svg style="width:24px;" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"
                            />
                        </svg>
                        <span
                            class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-20"
                            >Results</span
                        >
                    </button>
                    <span class="flex h-3 w-3 absolute top-0 right-0 -mt-1 -mr-1">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"
                        ></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-pink-500"></span>
                    </span>
                </div>
                <span v-click-outside="hide">
                    <button
                        @click="dropdownOpen = !dropdownOpen"
                        :class="datiProgetto.status != 0 ? 'md:hidden' : ''"
                        class="ripple hover:bg-primary flex flex-row items-center py-2 px-2 bg-transparent rounded-md transition duration-150 ease-in-out border-2 border-solid border-primary hover:text-white mr-2 mb-1 focus:outline-none"
                    >
                        <svg
                            class="transition duration-300 ease-in-out fill-current"
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
                            class="absolute bottom-1 right-0 mt-1 w-56 bg-white rounded-md shadow-xl z-20"
                        >
                            <a
                                @click="toggleModal('hit')"
                                v-if="datiProgetto.status == 0"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary rounded-t-md hover:text-gray-100"
                                >Set HITs</a
                            >
                            <a
                                @click="toggleModal('layout')"
                                v-if="datiProgetto.status == 1"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary rounded-t-md hover:text-gray-100"
                                >Set layout</a
                            >
                            <a
                                v-if="datiProgetto.status == 1"
                                @click="toggleModal('revert')"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary hover:text-gray-100"
                                >Revert HIT settings</a
                            >
                            <router-link
                                v-if="datiProgetto.status == 3"
                                to="results"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary hover:text-gray-100"
                                >Results</router-link
                            >
                            <a
                                class="cursor-pointer block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary hover:text-gray-100"
                                @click="toggleModal('delete')"
                                >Delete</a
                            >
                            <router-link
                                :to="{
                                    name: 'edit',
                                    params: { projectId: datiProgetto.id },
                                }"
                                :class="datiProgetto.status == 0 ? '' : 'rounded-b-md'"
                                class="block md:hidden px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary hover:text-gray-100"
                                >Edit</router-link
                            >
                            <a
                                v-if="datiProgetto.status == 0"
                                @click="toggleModal('std')"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary md:rounded-t-md hover:text-gray-100"
                                >Base CSV upload</a
                            >
                            <a
                                v-if="datiProgetto.status == 0"
                                @click="toggleModal('gld')"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary rounded-b-md hover:text-gray-100"
                                >Gold CSV upload</a
                            >
                            <!-- <a
                                v-if="datiProgetto.status == 0 && datiProgetto.baseCsvStatus == 1"
                                @click="uploadModal(['hit', ''])"
                                class="cursor-pointer block px-4 py-2 text-sm capitalize text-gray-800 transition duration-150 ease-in-out hover:bg-primary rounded-b-md hover:text-gray-100"
                                >Load HITs</a
                            >-->
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
                    <cardInfo :titoli="titoliCard.titoli3" :dati="datiCard.dati3" />
                    <cardInfo :titoli="titoliCard.titoli5" :dati="datiCard.dati5" />
                </div>
            </div>
            <div class="mx-0 sm:mx-2">
                <div class="w-full flex flex-col justify-center" v-if="loading1">
                    <loader :type="'cardInfoVisualizza'" v-for="n in 2" :key="n" />
                    <!-- <loader :type="'cardAnalyticsVisualizza'" :num="3" />
                    <loader :type="'cardAnalyticsVisualizza'" :num="2" />-->
                </div>
                <div v-else>
                    <cardInfo :titoli="titoliCard.titoli2" :dati="datiCard.dati2" />
                    <cardInfo :titoli="titoliCard.titoli4" :dati="datiCard.dati4" />
                    <!-- <cardAnalytics :dati="datiCardAnalytics.cardHIT" />
                    <cardAnalytics :dati="datiCardAnalytics.cardAggregate" />-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import modalEliminazione from '../components/modalEliminazione.vue'
import cardInfo from '../components/cardInfo.vue'
//import cardAnalytics from '../components/cardAnalyticsVisualizzaProgetto.vue'
import modalUpload from '../components/modalUpload.vue'
import loader from '../components/loader.vue'
import modalHIT from '../components/modalHIT.vue'
import modalRevert from '../components/modalRevert.vue'
import modalLayout from '../components/modalLayout.vue'
import axios from 'axios'

export default {
    name: 'visualizzaProgetto',
    directives: {
        ClickOutside,
    },
    components: {
        modalEliminazione,
        cardInfo,
        //cardAnalytics,
        modalUpload,
        loader,
        modalHIT,
        modalRevert,
        modalLayout,
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
                status: 0,
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
                baseCsv:
                    "<div class='flex flex-row justify-between'><span class='text-red-600'>not uploaded</span></div>",
                baseCsvStatus: 0,
                goldCsv:
                    "<div class='flex flex-row justify-between'><span class='text-red-600'>not uploaded</span></div>",
                goldCsvStatus: 0,
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
            modalRevert: false,
            modalHIT: false,
            modalLayout: false,
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
                    console.log(res.data)
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
                    this.datiProgetto.status = res.data.values.status
                    if (res.data.numData > 0) {
                        this.datiProgetto.baseCsv =
                            "<div class='flex flex-row flex-wrap justify-between content-center items-center'><span class='text-green-600'>uploaded</span><a class='px-2 py-1 bg-transparent transition duration-150 hover:bg-gray-300 rounded focus:outline-none' href='#/" +
                            this.datiProgetto.id +
                            "/data/standard'>View data</a></div>"
                        this.datiProgetto.baseCsvStatus = 1
                    }
                    if (res.data.numGold > 0) {
                        this.datiProgetto.goldCsv =
                            "<div class='flex flex-row flex-wrap justify-between content-center items-center'><span class='text-green-600'>uploaded</span><a class='px-2 py-1 bg-transparent transition duration-150 hover:bg-gray-300 rounded focus:outline-none' href='#/" +
                            this.datiProgetto.id +
                            "/data/gold'>View data</a></div>"
                        this.datiProgetto.goldCsvStatus = 1
                    }
                    this.loading = false
                })
                .catch(err => {
                    console.log(err)
                })
        },
        //metodo per aprire il link dei vari button
        open(mode) {
            this.$router.push({
                name: mode,
                params: { projectId: this.datiProgetto.id },
            })
        },
        //metodo che imposta i titoli e i dati da inserire nelle card della pagina
        impostaDatiCard() {
            this.titoliCard.titoli1 = ['Title', 'Description', 'Keywords', 'Creation date']
            var status = ''
            if ((this.datiProgetto.status == 0) & (this.datiProgetto.baseCsvStatus == 1)) {
                status =
                    '<span class="text-green-500">data uploaded.</span><span> Ready to <span class="font-bold text-primary">create HITs.</span></span>'
            } else if ((this.datiProgetto.status == 0) & (this.datiProgetto.baseCsvStatus == 0)) {
                status =
                    '<span class="text-green-500">project created!</span><span> <span class="font-bold text-primary">Upload csv data </span> and  <span class="font-bold text-primary"> csv gold</span> to advance.</span>'
            } else if (this.datiProgetto.status == 1) {
                status =
                    '<span class="text-green-500">HITs created. </span><span>Ready to <span class="font-bold text-primary"> set the layout.</span></span>'
            }
            this.datiCard.dati1 = [
                this.datiProgetto.titolo,
                this.datiProgetto.descrizione,
                this.datiProgetto.keywords,
                this.datiProgetto.creazione,
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
            this.titoliCard.titoli5 = ['Status']
            this.datiCard.dati5 = [status]
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
        toggleModal(mode) {
            if (mode == 'delete') {
                this.modalElim = !this.modalElim
            } else if (mode == 'std') {
                this.modalStd = !this.modalStd
            } else if (mode == 'gld') {
                if (this.datiProgetto.baseCsvStatus == 0) {
                    this.$emit('snackbar', 'Warning. To upload the gold CSV, you first have to uplaod the standard.')
                } else {
                    this.modalGld = !this.modalGld
                }
            } else if (mode == 'revert') {
                this.modalRevert = !this.modalRevert
            } else if (mode == 'hit') {
                if (this.datiProgetto.baseCsvStatus == 0) {
                    this.$emit('snackbar', 'Warning. To create the HITs, you first have to upload CSV data.')
                } else {
                    this.modalHIT = !this.modalHIT
                }
            } else if (mode == 'layout') {
                this.modalLayout = !this.modalLayout
            }
            this.hide()
        },
        //for when an action gets completed (success or error)
        deleted(msg) {
            this.$router.push('/')
            this.$emit('snackbar', msg)
        },
        //for when an action gets completed (success or error)
        uploaded(msg) {
            this.$emit('snackbar', msg)
            this.loading = true
            this.loading1 = true
            this.getDatiPrj()
        },
        //for when an action gets completed (success or error)
        reverted(msg) {
            this.uploaded(msg)
            this.loading = true
            this.loading1 = true
            this.getDatiPrj()
        },
        //for when an action gets completed (success or error)
        hitCreated(msg) {
            this.uploaded(msg)
            this.loading = true
            this.loading1 = true
            this.getDatiPrj()
        },
        //

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
.tooltip .tooltip-text {
    visibility: hidden;
    text-align: center;
    padding: 2px 6px;
    z-index: 100;
    left: 0;
    transition: opacity 0.3s ease-in-out;
    opacity: 0;
    transition-delay: 0.15s;
}
.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 85%;
}
</style>
